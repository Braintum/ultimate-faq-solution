<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5f86cbe3094b65deb692b3245cb11019
{
    public static $files = array (
        '36cee1082cb03d70f851fddb19f95211' => __DIR__ . '/..' . '/cmb2/cmb2/init.php',
    );

    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Mahedi\\UltimateFaqSolution\\' => 27,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Mahedi\\UltimateFaqSolution\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Mahedi\\UltimateFaqSolution\\Assets' => __DIR__ . '/../..' . '/inc/Assets.php',
        'Mahedi\\UltimateFaqSolution\\Custom_Resources' => __DIR__ . '/../..' . '/inc/Custom_Resources.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5f86cbe3094b65deb692b3245cb11019::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5f86cbe3094b65deb692b3245cb11019::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5f86cbe3094b65deb692b3245cb11019::$classMap;

        }, null, ClassLoader::class);
    }
}