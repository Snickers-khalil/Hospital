pipeline {
    agent any

    environment {
        WORKSPACE_DIR = "/var/jenkins_home/workspace/multi-hospital-pipeline"
    }

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
                  -w $WORKSPACE_DIR \
                  devops-php74-composer \
                  sh -c "
                    git config --global --add safe.directory $WORKSPACE_DIR &&
                    composer install --no-interaction --prefer-dist
                  "
                '''
            }
        }

        stage('SonarQube Analysis') {
            steps {
                echo "Running SonarQube analysis..."

                // Uncomment after Sonar Scanner is installed
                /*
                withSonarQubeEnv('SonarQube') {
                    sh '''
                    sonar-scanner
                    '''
                }
                */
            }
        }

        stage('Package') {
            steps {
                echo "Packaging application..."

                sh '''
                tar -czf multi-hospital.tar.gz \
                    application \
                    system \
                    vendor \
                    adminlte \
                    common \
                    front \
                    front-end \
                    hmz \
                    new-fnt \
                    index.php \
                    composer.json \
                    composer.lock
                '''
            }
        }

        stage('Upload to Nexus') {
            steps {
                echo "Uploading artifact to Nexus..."

                // Replace after Nexus is configured
                /*
                sh '''
                curl -u admin:password \
                  --upload-file multi-hospital.tar.gz \
                  http://nexus:8081/repository/multi-hospital/multi-hospital.tar.gz
                '''
                */
            }
        }
    }

    post {

        success {
            echo "Pipeline completed successfully."
        }

        failure {
            echo "Pipeline failed."
        }

        always {
            archiveArtifacts artifacts: '*.tar.gz', fingerprint: true
        }
    }
}
