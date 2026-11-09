<?php

function role_access_config($item)
{
    $CI = get_instance();
    $CI->config->load('role_access', true);
    return $CI->config->item($item, 'role_access');
}

// Returns the access rules of the logged-in user, or null when none apply.
function role_access_rules()
{
    $CI = get_instance();
    if (!isset($CI->ion_auth) || !$CI->ion_auth->logged_in()) {
        return null;
    }
    foreach (role_access_config('role_access') as $role => $rules) {
        if ($CI->ion_auth->in_group($role)) {
            return $rules;
        }
    }
    return null;
}

function role_can_open($class, $method = 'index')
{
    $rules = role_access_rules();
    if ($rules === null) {
        return true;
    }

    $class = strtolower($class);
    $method = strtolower($method);
    $page = $class . '/' . $method;
    if (in_array($page, $rules['blocked'])) {
        return false;
    }

    $pages = array_merge(role_access_config('common_pages'), $rules['pages']);
    return in_array($class, $pages) || in_array($page, $pages);
}

function hidden_modules()
{
    return role_access_config('hidden_modules');
}

function role_access_guard($CI, $RTR)
{
    if (!$CI->ion_auth->logged_in()) {
        return;
    }

    if ($CI->ion_auth->in_group(role_access_config('disabled_roles'))) {
        if (strtolower($RTR->class) != 'auth') {
            $CI->ion_auth->logout();
            redirect('auth/login');
        }
        return;
    }

    $class = strtolower($RTR->class);
    if (role_can_open($class, $RTR->method)) {
        return;
    }

    if ($CI->input->is_ajax_request()) {
        // AJAX stays open because allowed screens read data from these controllers.
        if (in_array($class, role_access_config('strict_classes'))) {
            show_404();
        }
        return;
    }

    redirect('home');
}

// Keeps only the sidebar links allowed for the current role.
function role_menu_filter($html)
{
    $rules = role_access_rules();
    if ($rules === null) {
        return $html;
    }

    $allowed = array_map('strtolower', array_merge(role_access_config('common_menu'), $rules['menu']));

    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    $doc->loadHTML('<?xml encoding="UTF-8"?><div id="role-menu">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();
    $xpath = new DOMXPath($doc);

    foreach (iterator_to_array($xpath->query('//a[@href]')) as $link) {
        $href = trim($link->getAttribute('href'));
        if ($href === '#') {
            continue;
        }
        $route = strtolower(strtok($href, '?'));
        if (!in_array($route, $allowed)) {
            $item = $xpath->query('ancestor::li[1]', $link)->item(0);
            $target = $item ? $item : $link;
            if ($target->parentNode) {
                $target->parentNode->removeChild($target);
            }
        }
    }

    foreach (iterator_to_array($xpath->query('//comment()')) as $comment) {
        $comment->parentNode->removeChild($comment);
    }

    // Remove menu groups left without any link, innermost first.
    do {
        $removed = false;
        foreach (iterator_to_array($xpath->query('//li[not(.//a[@href!="#"])]')) as $item) {
            if ($item->parentNode) {
                $item->parentNode->removeChild($item);
                $removed = true;
            }
        }
    } while ($removed);

    role_menu_group($doc, $xpath);

    $output = '';
    foreach ($xpath->query('//div[@id="role-menu"]')->item(0)->childNodes as $node) {
        $output .= $doc->saveHTML($node);
    }
    return $output;
}

// Sorts top-level menu items into titled sections.
function role_menu_group($doc, $xpath)
{
    $root = $xpath->query('//div[@id="role-menu"]')->item(0);
    $groups = role_access_config('menu_groups');
    $sections = array_fill_keys(array_keys($groups), array());

    foreach (iterator_to_array($xpath->query('./li', $root)) as $item) {
        $link = $xpath->query('.//a[@href!="#"]', $item)->item(0);
        $class = strtolower(strtok($link->getAttribute('href'), '/?'));
        $section = 'menu_group_administration';
        foreach ($groups as $key => $classes) {
            if (in_array($class, $classes)) {
                $section = $key;
                break;
            }
        }
        $sections[$section][] = $item;
        $root->removeChild($item);
    }

    foreach ($sections as $key => $items) {
        if (empty($items)) {
            continue;
        }
        $header = $doc->createElement('li');
        $header->setAttribute('class', 'nav-header');
        $header->appendChild($doc->createTextNode(lang($key)));
        $root->appendChild($header);
        foreach ($items as $item) {
            $root->appendChild($item);
        }
    }

    // The sidebar stylesheet sets these colors.
    foreach (iterator_to_array($xpath->query('//*[contains(@class, "text-white") or contains(@class, "text-secondary")]')) as $node) {
        $node->setAttribute('class', trim(str_replace(array('text-white', 'text-secondary'), '', $node->getAttribute('class'))));
    }
}

// Data used by role_links.php to remove links to hidden pages from the screens.
function role_link_rules()
{
    $rules = role_access_rules();
    if ($rules === null) {
        return null;
    }

    $hidden = array();
    foreach (glob(APPPATH . 'modules/*', GLOB_ONLYDIR) as $module) {
        $class = strtolower(basename($module));
        if (!role_can_open($class, 'index')) {
            $hidden[] = $class;
        }
    }

    $blocked = $rules['blocked'];
    if (!role_can_open('pharmacy', 'payment')) {
        $blocked[] = 'finance/pharmacy';
    }

    return array(
        'hidden' => $hidden,
        'pages' => $rules['pages'],
        'blocked' => $blocked,
        'readonly' => isset($rules['readonly']) ? $rules['readonly'] : array(),
        'hiddenModules' => hidden_modules(),
    );
}
