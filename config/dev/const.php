<?php
    define( 'LOGS_DIR', ROOT .'logs/' );
    define( 'UPLOAD_DIR', ROOT .'static/' );

    define( 'CONFIG_CLASSES_DIR', CONFIG_DIR. PLATFORM .'/classes/' );
    define( 'CONFIG_DYNAMIC_DIR', CONFIG_DIR. PLATFORM .'/dynamic/' );
    define( 'CONFIG_NGINX_DIR', CONFIG_DYNAMIC_DIR .'nginx/' );
    define( 'CONFIG_REDIS_DIR', CONFIG_DYNAMIC_DIR .'redis/' );

    define( 'REDIS_LOCAL_HOST', '127.0.0.1' );
    define( 'REDIS_LOCAL_PORT', '6379' );
    define( 'REDIS_LOCAL_DB',   0 );
