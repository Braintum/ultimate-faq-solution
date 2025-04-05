<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit35bfc3cd1c7f61ceaf40d8bf9eb1470a
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
        'Mahedi\\UltimateFaqSolution\\Product_Tab' => __DIR__ . '/../..' . '/inc/Product_Tab.php',
        'Mahedi\\UltimateFaqSolution\\Rest' => __DIR__ . '/../..' . '/inc/Rest.php',
        'Mahedi\\UltimateFaqSolution\\SEO' => __DIR__ . '/../..' . '/inc/SEO.php',
        'Mahedi\\UltimateFaqSolution\\Shortcodes' => __DIR__ . '/../..' . '/inc/Shortcodes.php',
        'Mahedi\\UltimateFaqSolution\\Template' => __DIR__ . '/../..' . '/inc/Template.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit35bfc3cd1c7f61ceaf40d8bf9eb1470a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit35bfc3cd1c7f61ceaf40d8bf9eb1470a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit35bfc3cd1c7f61ceaf40d8bf9eb1470a::$classMap;

        }, null, ClassLoader::class);
    }
}
