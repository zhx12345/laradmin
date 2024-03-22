<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8059726beda852d1cebb331c72c3fb68
{
    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'Zhxlan\\Laradmin\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Zhxlan\\Laradmin\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8059726beda852d1cebb331c72c3fb68::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8059726beda852d1cebb331c72c3fb68::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8059726beda852d1cebb331c72c3fb68::$classMap;

        }, null, ClassLoader::class);
    }
}
