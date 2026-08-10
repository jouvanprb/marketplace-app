pipeline {
    agent any

    environment {
        SSH_CRED = 'ec2-ssh-key' // ID Credentials SSH Key Anda
        SSH_USER = 'ec2-user'
    }

    stages {
        stage('Checkout SCM') {
            steps {
                checkout scm
            }
        }

        stage('Automated Testing & Syntax Check') {
            steps {
                sh 'ansible-playbook --syntax-check ansible/playbooks/deploy.yml'
            }
        }

        stage('Run Ansible Deployment') {
            steps {
                withCredentials([
                    sshUserPrivateKey(credentialsId: env.SSH_CRED, keyFileVariable: 'SSH_KEY'),
                    string(credentialsId: 'db-password', variable: 'DB_PASS')
                    string(credentialsId: 'MYSQL_ROOT_PASSWORD', variable: 'DB_ROOT_PASS')
                ]) {
                    sh '''
                        ansible-playbook -i ansible/inventory/hosts.yml ansible/playbooks/deploy.yml \
                        --private-key "${SSH_KEY}" -u "${SSH_USER}" \
                        --ssh-common-args='-o StrictHostKeyChecking=no' \
                        -e "db_password=${DB_PASS}"
                    '''
                }
            }
        }
    }
}