pipeline {
    agent any

    environment {
        JENKINS_VOLUME   = "devops_jenkins_data"
        WORKSPACE_PATH   = "/var/jenkins_home/workspace/multi-hospital-pipeline"

        NEXUS_URL        = "http://10.10.10.8:8081"
        NEXUS_REPOSITORY = "multi-hospital-releases"
    }

    stages {

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Composer Install') {
            steps {
                sh """
                docker run --rm \
                  -v ${JENKINS_VOLUME}:/var/jenkins_home \
                  -w ${WORKSPACE_PATH} \
                  devops-php74-composer \
                  sh -c '
                    git config --global --add safe.directory ${WORKSPACE_PATH}
                    composer install --no-interaction --prefer-dist
                  '
                """
            }
        }

        stage('SonarQube Analysis') {
            steps {
                echo "Running SonarQube analysis..."
                // We will configure the real SonarQube scanner later.
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

                archiveArtifacts artifacts: 'multi-hospital.tar.gz', fingerprint: true
            }
        }

        stage('Upload to Nexus') {
            steps {
                withCredentials([
                    usernamePassword(
                        credentialsId: 'nexus-creds',
                        usernameVariable: 'NEXUS_USER',
                        passwordVariable: 'NEXUS_PASS'
                    )
                ]) {

                    sh """
                    curl --fail --show-error --silent \
                      -u \$NEXUS_USER:\$NEXUS_PASS \
                      --upload-file multi-hospital.tar.gz \
                      ${NEXUS_URL}/repository/${NEXUS_REPOSITORY}/multi-hospital-${BUILD_NUMBER}.tar.gz
                    """

                    echo "Artifact uploaded successfully to Nexus."
                }
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

        cleanup {
            cleanWs()
        }
    }
}
