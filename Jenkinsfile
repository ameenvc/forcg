def COMPANIES = [
    'sptc': [
        stage: [host: '65.20.72.209',   path: '/home/admin/web/stage-sptc.cgbod.com/public_html/quickqmlaravel', branch: 'staging'],
        prod:  [host: '65.20.72.209',   path: '/home/admin/web/sptc.cgbod.com/public_html/quickqmlaravel',       branch: 'master']
    ],
    'delmon': [
        stage: [host: '139.84.140.187', path: '/home/delmon/web/delmon-stage.cgbod.com/public_html/quickqmlaravel', branch: 'staging'],
        prod:  [host: '139.84.140.187', path: '/home/delmon/web/delmon.cgbod.com/public_html/quickqmlaravel',       branch: 'master']
    ]
]

pipeline {
    agent any
    parameters {
        choice(name: 'COMPANY', choices: ['sptc', 'delmon'], description: 'Which client')
        choice(name: 'ENVIRONMENT', choices: ['stage', 'prod'], description: 'Target environment')
    }
    environment {
        DEPLOY_USER = 'minhaj'
        PHP_BIN     = 'php'
        SSH_CRED_ID = 'sptc-minhaj-deploy'
    }
    stages {
        stage('Resolve target') {
            steps {
                script {
                    if (!COMPANIES.containsKey(params.COMPANY) || !COMPANIES[params.COMPANY].containsKey(params.ENVIRONMENT)) {
                        error "No config found for ${params.COMPANY} / ${params.ENVIRONMENT}"
                    }
                    def cfg = COMPANIES[params.COMPANY][params.ENVIRONMENT]
                    env.DEPLOY_HOST     = cfg.host
                    env.DEPLOY_PATH     = cfg.path
                    env.GIT_BRANCH_NAME = cfg.branch
                    echo "Deploying ${params.COMPANY} (${params.ENVIRONMENT}) -> ${env.DEPLOY_HOST}:${env.DEPLOY_PATH} [${env.GIT_BRANCH_NAME}]"
                }
            }
        }
        stage('Git Pull') {
            steps {
                sshagent(credentials: [SSH_CRED_ID]) {
                    sh """
                        ssh -o StrictHostKeyChecking=no ${DEPLOY_USER}@${env.DEPLOY_HOST} '
                            cd ${env.DEPLOY_PATH} &&
                            sudo chmod -R 777 storage bootstrap/cache &&
                            git fetch origin &&
                            git checkout ${env.GIT_BRANCH_NAME} &&
                            git reset --hard origin/${env.GIT_BRANCH_NAME} &&
                            git pull origin ${env.GIT_BRANCH_NAME}
                        '
                    """
                }
            }
        }
        stage('Composer Install') {
            steps {
                sshagent(credentials: [SSH_CRED_ID]) {
                    sh """
                        ssh -o StrictHostKeyChecking=no ${DEPLOY_USER}@${env.DEPLOY_HOST} '
                            cd ${env.DEPLOY_PATH} &&
                            composer install --no-dev --optimize-autoloader --no-interaction
                        '
                    """
                }
            }
        }
        stage('Artisan: cache + migrate') {
            steps {
                sshagent(credentials: [SSH_CRED_ID]) {
                    sh """
                        ssh -o StrictHostKeyChecking=no ${DEPLOY_USER}@${env.DEPLOY_HOST} '
                            cd ${env.DEPLOY_PATH} &&
                            ${PHP_BIN} artisan config:cache &&
                            ${PHP_BIN} artisan view:cache &&
                            ${PHP_BIN} artisan migrate --force
                        '
                    """
                }
            }
        }
    }
    post {
        failure { echo "Deploy to ${params.COMPANY} (${params.ENVIRONMENT}) FAILED" }
        success { echo "Deploy to ${params.COMPANY} (${params.ENVIRONMENT}) succeeded" }
    }
}
