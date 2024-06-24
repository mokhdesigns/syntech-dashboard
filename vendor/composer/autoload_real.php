<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit74d9496ff9855352a1f8fb1633b36e5e
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit74d9496ff9855352a1f8fb1633b36e5e', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit74d9496ff9855352a1f8fb1633b36e5e', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit74d9496ff9855352a1f8fb1633b36e5e::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
