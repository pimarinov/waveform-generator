<?php
declare(strict_types = 1);

use Pimarinov\WaveformGenerator\Cli\Handler;

(static function (): void
{

    $composerAutoloadFiles = [
        dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php',
    ];

    foreach ($composerAutoloadFiles as $file)
    {
        if (file_exists($file))
        {
            require_once $file;

            break;
        }
    }

    $opts = getopt('f', ['user:', 'customer:', 'file']);

    try
    {

        if (empty($opts['user']))
        {
            throw new Exception('The user parameter is mandatory.');
        }
        if (empty($opts['customer']))
        {
            throw new Exception('The customer parameter is mandatory.');
        }

        $args = (new Pimarinov\WaveformGenerator\Data\WaveformCliArgs(
                $opts['user'], $opts['customer']
        ));

        $handler = new Handler($args);

        print_r($handler->execute());
    } catch (Exception $e)
    {

        print_r('ERROR: ' . $e->getMessage());
    }
})();
