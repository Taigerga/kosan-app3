module.exports = {
    apps: [{
        name: 'whatsapp-bot',
        script: './whatsapp-bot.js',
        instances: 1,
        autorestart: true,
        watch: false,
        max_memory_restart: '500M',
        env: {
            NODE_ENV: 'production'
        },
        error_file: './storage/logs/whatsapp-error.log',
        out_file: './storage/logs/whatsapp-out.log',
        log_file: './storage/logs/whatsapp-combined.log',
        time: true,
        restart_delay: 5000,
        max_restarts: 10
    }]
};