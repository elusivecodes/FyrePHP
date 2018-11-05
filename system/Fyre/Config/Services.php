<?php

namespace Fyre\Config;

use
    Fyre\Config\ConfigService,
    Fyre\Component\Cache\CacheHandlerInterface,
    Fyre\Component\Cache\CacheService,
    Fyre\Component\Encryption\EncryptionHandlerInterface,
    Fyre\Component\Encryption\EncryptionService,
    Fyre\Component\Image\ImageHandlerInterface,
    Fyre\Component\Image\ImageService,
    Fyre\Component\Mail\MailHandlerInterface,
    Fyre\Component\Mail\MailService,
    Fyre\Component\Parser\ParserInterface,
    Fyre\Component\Parser\ParserService,
    Fyre\Component\Session\Session,
    Fyre\Component\Session\SessionService,
    Fyre\Component\Upload\UploadInterface,
    Fyre\Component\Upload\UploadService,
    Fyre\Component\Validation\ValidationInterface,
    Fyre\Component\Validation\ValidationService,
    Fyre\Database\DatabaseService,
    Fyre\Database\DatabaseHandler,
    Fyre\Engine\Benchmark\BenchmarkInterface,
    Fyre\Engine\Benchmark\BenchmarkService,
    Fyre\Engine\Lang\LangService,
    Fyre\Engine\Lang\LangInterface,
    Fyre\Engine\Loader\LoaderService,
    Fyre\Engine\Loader\LoaderInterface,
    Fyre\Engine\Logger\LoggerService,
    Fyre\Engine\Logger\LoggerInterface,
    Fyre\Engine\Renderer\RendererService,
    Fyre\Engine\Renderer\RendererInterface,
    Fyre\Engine\Request\RequestService,
    Fyre\Engine\Request\RequestInterface,
    Fyre\Engine\Response\ResponseService,
    Fyre\Engine\Response\ResponseInterface,
    Fyre\Engine\Router\RouterService,
    Fyre\Engine\Router\RouterInterface,
    Fyre\Engine\Security\SecurityService,
    Fyre\Engine\Security\SecurityInterface,
    Fyre\Engine\Controller,
    Fyre\Engine\Model,
    Fyre\Engine\ModelService;

abstract class Services {
    protected static $instance;

    public static function &getSharedInstance(): ?Controller
    {
        return static::$instance;
    }

    public static function setSharedInstance(&$instance): void
    {
        if ( ! static::$instance) {
            static::$instance = &$instance;
        }
    }

    // config service

    public static function config(...$args)
    {
        return ConfigService::load(...$args);
    }

    // database service

    public static function database(...$args): DatabaseHandler
    {
        return DatabaseService::load(...$args);
    }

    public static function forge(...$args)
    {
        return new \Fyre\Database\Forge\Forge(...$args);
    }

    // model service

    public static function model(...$args): Model
    {
        return ModelService::load(...$args);
    }

    // engine services

    public static function benchmark(...$args): BenchmarkInterface
    {
        return BenchmarkService::load(...$args);
    }

    public static function lang(...$args): LangInterface
    {
        return LangService::load(...$args);
    }

    public static function loader(...$args): LoaderInterface
    {
        return LoaderService::load(...$args);
    }

    public static function logger(...$args): LoggerInterface
    {
        return LoggerService::load(...$args);
    }

    public static function renderer(...$args): RendererInterface
    {
        return RendererService::load(...$args);
    }

    public static function request(...$args): RequestInterface
    {
        return RequestService::load(...$args);
    }

    public static function response(...$args): ResponseInterface
    {
        return ResponseService::load(...$args);
    }

    public static function router(...$args): RouterInterface
    {
        return RouterService::load(...$args);
    }

    public static function security(...$args): SecurityInterface
    {
        return SecurityService::load(...$args);
    }

    // component services

    public static function cache(...$args): CacheHandlerInterface
    {
        return CacheService::load(...$args);
    }

    public static function encryption(...$args): EncryptionHandlerInterface
    {
        return EncryptionService::load(...$args);
    }

    public static function image(...$args): ImageHandlerInterface
    {
        return ImageService::load(...$args);
    }

    public static function mail(...$args): MailHandlerInterface
    {
        return MailService::load(...$args);
    }

    public static function parser(...$args): ParserInterface
    {
        return ParserService::load(...$args);
    }

    public static function session(...$args): Session
    {
        return SessionService::load(...$args);
    }

    public static function upload(...$args): UploadInterface
    {
        return UploadService::load(...$args);
    }

    public static function validation(...$args): ValidationInterface
    {
        return ValidationService::load(...$args);
    }

}
