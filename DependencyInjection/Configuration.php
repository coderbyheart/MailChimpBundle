<?php

/**
 * @author    Markus Tacker <m@coderbyheart.com>
 * @author    Michael Hirschler
 */

namespace Coderbyheart\MailChimpBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from the app/config files.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('coderbyheart_mail_chimp');
        $rootNode
            ->children()
                ->scalarNode('api_key')
                    ->isRequired()
                    ->info('The API key provided by MailChimp')
                ->end()
                ->scalarNode('return_type')
                    ->defaultValue('object')
                    ->validate()
                    ->ifNotInArray(array('object', 'array'))
                        ->thenInvalid('Invalid return_type "%s"')
                    ->end()
                    ->info('Whether to return MailChimp API return values as object (default) or as array')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
