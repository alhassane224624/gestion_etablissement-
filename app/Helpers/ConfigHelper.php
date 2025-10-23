<?php
namespace App\Helpers;

class ConfigHelper
{
    public static function getPaginationCount($type = 'default')
    {
        return config("gestion.pagination.{$type}", 15);
    }

    public static function getMaxUploadSize()
    {
        return config('gestion.upload.max_size', 10240) * 1024; // Convert to bytes
    }

    public static function getAllowedImageTypes()
    {
        return config('gestion.upload.allowed_images', ['jpeg', 'png', 'jpg', 'gif']);
    }

    public static function isNotificationEnabled()
    {
        return config('gestion.notifications.enabled', true);
    }

    public static function shouldAutoNotify()
    {
        return config('gestion.notifications.auto_notify', true);
    }
}