pipeline {
    agent any

    stages {

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Composer Install') {
            steps {
                sh '''
                docker run --rm \
                  -v $WORKSPACE:/app \
                  -w /app \
                  devops-php74-composer \
                  composer install
                '''
            }
        }

        stage('SonarQube Analysis') {
            steps {
                echo 'Next step'
            }
        }

        stage('Package') {
            steps {
                echo 'Next step'
            }
        }

        stage('Upload to Nexus') {
            steps {
                echo 'Next step'
            }
        }
    }
}
