<?php

/**
 * @author    Markus Tacker <m@coderbyheart.de>
 * @copyright 2013 Markus Tacker | coder::by(♥); // Freelance Señor Web Backend Dev | http://coderbyheart.de/
 */

namespace Coderbyheart\MailChimpBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages the bundle configuration.
 */
class CoderbyheartMailChimpExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);
        $container->setParameter('coderbyheart_mail_chimp.api_key', $config['api_key']);
        $container->setParameter('coderbyheart_mail_chimp.return_type', $config['return_type']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }
}
