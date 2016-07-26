<?php

/**
 * @author    Markus Tacker <m@coderbyheart.com>
 */

spl_autoload_register(function ($class) {
    if (strpos($class, 'Coderbyheart\MailChimpBundle\MailChimp') === 0) {
        $parts = explode('\\', $class);
        array_shift($parts);
        require_once __DIR__ . '/../MailChimp/' . join(DIRECTORY_SEPARATOR, $parts) . '.php';
        return true;
    }
    return false;
});
