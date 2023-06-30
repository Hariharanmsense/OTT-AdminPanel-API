<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use App\Factory\LoggerFactory;
use App\Factory\MailerFactory;
use App\Factory\UrlSettingFactory;
use App\Factory\DBConFactory;
use App\Application\Auth\JwtToken;
use App\Application\Auth\Crypto;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        /*
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        */
        LoggerFactory::class => function (ContainerInterface $container) {
			
            return new LoggerFactory($container->get(SettingsInterface::class)->get('logger')['path'],$container->get(SettingsInterface::class)->get('logger')['level']);
		},
        JwtToken::class =>function (ContainerInterface $container) {
			
            return new JwtToken($container->get(SettingsInterface::class)->get('JWT'));
		},
        Crypto::class =>function (ContainerInterface $container) {
			
            return new Crypto($container->get(SettingsInterface::class)->get('cryptography'));
		},
        MailerFactory::class => function (ContainerInterface $container) {
			return new MailerFactory($container->get(SettingsInterface::class)->get('mailer'));
		},
        UrlSettingFactory::class => function (ContainerInterface $container) {
			return new UrlSettingFactory($container->get(SettingsInterface::class)->get('urlSettings'));
		},
        DBConFactory::class => function (ContainerInterface $container) {
			return new DBConFactory($container->get(SettingsInterface::class)->get('db'));
		  },
    ]);
};
