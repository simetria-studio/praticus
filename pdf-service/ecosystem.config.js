module.exports = {
  apps: [
    {
      name: 'pdf-service',
      script: './server.js',
      instances: 1,
      exec_mode: 'fork',
      env: {
        NODE_ENV: 'development',
        PORT: 3001
      },
      env_production: {
        NODE_ENV: 'production',
        PORT: 3001
      },
      // Configurações de log
      log_file: './logs/pdf-service.log',
      out_file: './logs/pdf-service-out.log',
      error_file: './logs/pdf-service-error.log',
      log_date_format: 'YYYY-MM-DD HH:mm:ss',

      // Configurações de restart
      max_restarts: 10,
      min_uptime: '10s',
      max_memory_restart: '512M',

      // Configurações específicas para Puppeteer
      node_args: '--max-old-space-size=512',

      // Auto restart em caso de crash
      autorestart: true,

      // Configurações de monitoramento
      watch: false,
      ignore_watch: ['node_modules', 'logs'],

      // Configurações de cluster (se necessário)
      // instances: 'max', // descomente para usar todos os cores
      // exec_mode: 'cluster' // descomente para modo cluster
    }
  ]
};
