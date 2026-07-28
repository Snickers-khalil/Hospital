pipeline {
    agent any

    stages {

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Environment Check') {
            steps {
                sh 'pwd'
                sh 'php -v'
                sh 'composer --version'
                sh 'git --version'
                sh 'docker --version'
            }
        }

        stage('Composer Install') {
            steps {
                sh 'composer install --no-interaction'
            }
        }
    }

    post {
        success {
            echo 'Environment is ready!'
        }

        failure {
            echo 'Pipeline failed.'
        }
    }
}
