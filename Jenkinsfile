pipeline {
    agent any

    stages {

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('PHP') {
            steps {
                sh 'php -v'
            }
        }

        stage('Composer') {
            steps {
                sh 'composer --version'
            }
        }

        stage('Install Dependencies') {
            steps {
                sh 'composer install --no-interaction'
            }
        }
    }
}
