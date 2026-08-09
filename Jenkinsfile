pipeline {
    agent any

    environment {
        ANSIBLE_CREDENTIALS_ID = 'aws-ec2-key'
    }

    stages {
        stage('Checkout SCM') {
            steps {
                echo 'Fetching source code and deployment playbooks from GitHub...'
                checkout scm
            }
        }

        stage('Automated Testing & Syntax Check') {
            steps {
                echo 'Validating Ansible Playbook syntax...'
                sh 'ansible-playbook --syntax-check ansible/playbooks/deploy.yml'
            }
        }

        stage('Run Ansible Deployment') {
            steps {
                withCredentials([sshUserPrivateKey(
                    credentialsId: "${ANSIBLE_CREDENTIALS_ID}",
                    keyFileVariable: 'SSH_KEY',
                    usernameVariable: 'SSH_USER'
                )]) {
                    echo 'Deploying application to AWS EC2 via Ansible...'
                    sh '''
                        ansible-playbook -i ansible/inventory/hosts.yml ansible/playbooks/deploy.yml \
                        --private-key "${SSH_KEY}" \
                        -u "${SSH_USER}" \
                        --ssh-common-args='-o StrictHostKeyChecking=no'
                    '''
                }
            }
        }
    }

    post {
        success {
            echo '✅ Deployment finished successfully!'
        }
        failure {
            echo '❌ Deployment failed! Check the log details above.'
        }
    }
}