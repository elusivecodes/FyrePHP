<?php

namespace Fyre\Engine;

use
    Config\Services;

use const
    APP_PATH,
    FYRE_PATH;

use function
    define,
    error_get_last,
    error_reporting,
    ini_set,
    nl2br,
    register_shutdown_function,

    show_error,
    utility;

abstract class Engine
{

    public static function errorHandler()
    {
        $error = error_get_last();

        if ( ! $error) {
            return;
        }

        // log message
        Services::logger()->error(
            $error['message'].' - '.$error['file'].' - '.$error['line']
        );

        // show error
        show_error(
            '<p><strong>Line:</strong> '.$error['line'].'</p>'.
            '<p><strong>File:</strong> '.$error['file'].'</p>'.
            '<p>'.nl2br($error['message']).'</p>'
        );
    }

    public static function run(array $config)
    {

        /* Global Constants */

        define('BASE_PATH', realpath($config['basePath']).'/');
        define('APP_PATH', realpath($config['appPath']).'/');
        define('FROST_PATH', realpath($config['sysPath']).'/Frost/');
        define('FYRE_PATH', realpath($config['sysPath']).'/Fyre/');

        /* Error Handling */

        ini_set('display_errors', 0);
        error_reporting($config['errorLevel']);
        register_shutdown_function(static::class.'::errorHandler');

        /* Load Files */

        // constants
        require APP_PATH.'Config/Constants.php';

        // autoloader
        require FYRE_PATH.'Engine/Loader/LoaderService.php';
        require FYRE_PATH.'Engine/Loader/LoaderInterface.php';
        require FYRE_PATH.'Engine/Loader/Loader.php';

        // services
        require FYRE_PATH.'Config/Services.php';
        require APP_PATH.'Config/Services.php';

        // global
        require FYRE_PATH.'Utility/global.php';

        // utilities
        utility(
            [
                'array',
                'file',
                'number',
                'string'
            ]
        );

        /* Let's go.. */

        Services::loader() &&
        Services::config() &&
        Services::security() &&
        Services::benchmark() &&
        Services::router()->find(
            Services::request()->uriString()
        ) &&
        Services::response()->_render();
    }

}
