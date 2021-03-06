<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3c0b097446d5419a7caecddf731f955c
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPForms\\' => 8,
        ),
        'T' => 
        array (
            'TijsVerkoyen\\CssToInlineStyles\\' => 31,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Component\\CssSelector\\' => 30,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPForms\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'TijsVerkoyen\\CssToInlineStyles\\' => 
        array (
            0 => __DIR__ . '/..' . '/tijsverkoyen/css-to-inline-styles/src',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Component\\CssSelector\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/css-selector',
        ),
    );

    public static $prefixesPsr0 = array (
        'G' => 
        array (
            'Goodby\\CSV' => 
            array (
                0 => __DIR__ . '/..' . '/goodby/csv/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3c0b097446d5419a7caecddf731f955c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3c0b097446d5419a7caecddf731f955c::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit3c0b097446d5419a7caecddf731f955c::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
