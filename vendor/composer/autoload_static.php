<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit589b891d300166a252925bfbf347b2b4
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Cmfcmf\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Cmfcmf\\' => 
        array (
            0 => __DIR__ . '/..' . '/cmfcmf/openweathermap-php-api/Cmfcmf',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit589b891d300166a252925bfbf347b2b4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit589b891d300166a252925bfbf347b2b4::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
