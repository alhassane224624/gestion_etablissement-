
<?php

return [
    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        'photos' => [
            'driver' => 'local',
            'root' => storage_path('app/public/photos'),
            'url' => env('APP_URL').'/storage/photos',
            'visibility' => 'public',
        ],

        'exports' => [
            'driver' => 'local',
            'root' => storage_path('app/exports'),
            'visibility' => 'private',
        ],

        'imports' => [
            'driver' => 'local',
            'root' => storage_path('app/imports'),
            'visibility' => 'private',
        ],

        'backups' => [
            'driver' => 'local',
            'root' => storage_path('app/backups'),
            'visibility' => 'private',
        ],
    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],
];
