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
                  -v devops_jenkins_data:/var/jenkins_home \
                  -w /var/jenkins_home/workspace/multi-hospital-pipeline \
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

    post {
        success {
            echo 'Pipeline completed successfully.'
        }

        failure {
            echo 'Pipeline failed.'
        }
    }
}
