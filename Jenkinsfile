pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Show Files') {
            steps {
                sh 'pwd'
                sh 'ls -la'
            }
        }
    }
}
