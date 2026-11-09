<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sends one chat request to the AI service and retries temporary errors.
 * Returns the HTTP code, the raw response and any cURL error.
 */
function ai_chat_request($api_key, $data)
{
    $retry_codes = array(0, 429, 500, 503);
    $attempts = 3;

    for ($attempt = 1; $attempt <= $attempts; $attempt++) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, AI_API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        ));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($http_code === 200) {
            break;
        }
        error_log('AI request failed, attempt ' . $attempt . ', HTTP ' . $http_code . ': ' . ($error ?: substr((string) $response, 0, 1200)));
        // A daily quota will not recover in a few seconds: use the fallback
        // model, or stop at once if it has run out too.
        $daily_quota = stripos((string) $response, 'per day') !== false;
        if ($daily_quota && $data['model'] !== AI_FALLBACK_MODEL) {
            $data['model'] = AI_FALLBACK_MODEL;
            continue;
        }
        if ($daily_quota || !in_array($http_code, $retry_codes) || $attempt === $attempts) {
            break;
        }
        // A 429 is a per-minute limit, so it needs a longer wait.
        sleep($http_code === 429 ? 20 : $attempt * 2);
    }

    return array('http_code' => $http_code, 'response' => $response, 'error' => $error);
}

/**
 * Short message for the user. The full error is already in the PHP log.
 */
function ai_error_message($http_code)
{
    if ($http_code === 429) {
        return "Le quota de l'IA est atteint. Réessayez plus tard.";
    }
    return "Le service d'IA est indisponible. Réessayez dans quelques instants.";
}
