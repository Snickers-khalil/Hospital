pipeline {
    agent any

    stages {

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Docker Test') {
            steps {
                sh 'docker version'
                sh 'docker ps'
            }
        }
    }
}
