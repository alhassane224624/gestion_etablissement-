
<?php

return [
    'pagination' => [
        'default' => env('DEFAULT_PAGINATION', 15),
        'admin' => env('ADMIN_PAGINATION', 25),
    ],

    'upload' => [
        'max_size' => env('MAX_UPLOAD_SIZE', 10240), // en KB
        'allowed_images' => explode(',', env('ALLOWED_IMAGE_TYPES', 'jpeg,png,jpg,gif')),
        'allowed_imports' => explode(',', env('ALLOWED_IMPORT_TYPES', 'xlsx,csv,xls')),
    ],

    'paths' => [
        'exports' => env('EXPORT_PATH', 'exports'),
        'backups' => env('BACKUP_PATH', 'backups'),
        'photos' => 'photos',
    ],

    'notifications' => [
        'enabled' => env('NOTIFICATION_ENABLED', true),
        'auto_notify' => env('AUTO_NOTIFICATION', true),
    ],

    'system' => [
        'maintenance_mode' => false,
        'auto_backup' => true,
        'backup_frequency' => 'daily', // daily, weekly, monthly
    ],
];