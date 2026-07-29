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
                withSonarQubeEnv('SonarQube') {
                    script {
                        def scannerHome = tool 'SonarScanner'

                        sh """
                            ${scannerHome}/bin/sonar-scanner
                        """
                    }
                }
            }
        }

        stage('Quality Gate') {
            steps {
                timeout(time: 5, unit: 'MINUTES') {
                    waitForQualityGate abortPipeline: true
                }
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

        stage('Deploy') {
            steps {

                withCredentials([
                    usernamePassword(
                        credentialsId: 'nexus-creds',
                        usernameVariable: 'NEXUS_USER',
                        passwordVariable: 'NEXUS_PASS'
                    )
                ]) {

                    sshagent(credentials: ['deploy-key']) {

                        sh """
                        set -e

                        echo "Downloading artifact from Nexus..."

                        curl --fail --show-error --silent \
                          -u \$NEXUS_USER:\$NEXUS_PASS \
                          -o multi-hospital.tar.gz \
                          ${NEXUS_URL}/repository/${NEXUS_REPOSITORY}/multi-hospital-${BUILD_NUMBER}.tar.gz

                        echo "Copying artifact to deployment server..."

                        scp -o StrictHostKeyChecking=no \
                          multi-hospital.tar.gz \
                          root@10.10.10.8:/tmp/

                        echo "Deploying..."

                        ssh -o StrictHostKeyChecking=no root@10.10.10.8 "

                            set -e

                            RELEASE_DIR=/var/www/multi-hospital/releases/${BUILD_NUMBER}

                            mkdir -p \\\$RELEASE_DIR

                            tar -xzf /tmp/multi-hospital.tar.gz -C \\\$RELEASE_DIR

                            ln -sfn \\\$RELEASE_DIR /var/www/multi-hospital/current

                            chown -R www-data:www-data /var/www/multi-hospital

                            systemctl reload apache2

                            rm -f /tmp/multi-hospital.tar.gz

                            echo Deployment completed.

                        "
                        """
                    }
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