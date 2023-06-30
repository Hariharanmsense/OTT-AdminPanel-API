<?php

declare(strict_types=1);

namespace App\Factory;

final class UrlSettingFactory
{
    private $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function getAdminBaseURL(): string
    {
        return $this->settings['ADMIN_BASE_URL'];
    }

    public function getImagePathURL(): string
    {
        return $this->settings['IMAGE_PATH_URL'];
    }
}

?>