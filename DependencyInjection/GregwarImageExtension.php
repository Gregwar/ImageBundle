<?php

namespace Gregwar\ImageBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * Loading configuration
 *
 * @author Gregwar <g.passault@gmail.com>
 */
class GregwarImageExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $cacheDir = 'cache';

        $config = $configs[0];

        if (isset($config['cache_dir']))
            $cacheDir = $config['cache_dir'];

        $container->setParameter('gregwar_image.cache_dir', $cacheDir);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}

