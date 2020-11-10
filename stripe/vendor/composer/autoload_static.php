<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf13ba8247a31b31fb87a4fb06e9bb083
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf13ba8247a31b31fb87a4fb06e9bb083::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf13ba8247a31b31fb87a4fb06e9bb083::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
