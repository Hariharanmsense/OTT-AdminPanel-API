<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => false,
                'logErrorDetails'     => false,
                'logger' => [
                    'name' => 'app',
                    'path' => __DIR__ . '/../logs',
                    'filename' => 'app.log',
                    'level' => \Monolog\Logger::DEBUG,
                    'file_permission' => 0775,
                ],
                'db' => [
                    'host' => '192.168.2.151',
                    'database' => 'ottproduct',
                    'username' => 'hariharan',
                    'password' => 'Hari$123@#',
                    ],
                'mailer' => [
                    'host' => 'smtp.gmail.com',
                    'port' => 465,
                    'username' => 'hariharan.r@microsensenetworks.com',
                    'password' => 'HARI1605',
                    'frommail' => 'hariharan.r@microsensenetworks.com',
                    'fromname' => 'Hari',
                    'SMTPSecure' => 'ssl',
                ],
                'JWT' =>[
                    'JWT_SECRET_KEY' => 'OTTPRODUCT_KeY',
                    'hash' => 'HS256',
                ],
				'cryptography' =>[
                    'ENDECRYPT_SECRET_KEY' => 'msense@2023@ibushdnkey#$%',
                    'hash' => 'sha256',
                    'method' => 'AES-256-CBC',
                    'iv' => '19052023',
                    'length' => 16,
                ],
                'urlSettings' =>[
                    'ADMIN_BASE_URL' => 'http://116.212.176.158:8083/projects/ottproduct/frted/v1/', 
                    'IMAGE_PATH_URL' => 'http://116.212.176.158:8083/projects/ottproduct/frted/v1/',                    
                ] 
            ]);
        }
    ]);
};
