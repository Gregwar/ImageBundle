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

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('gregwar_image.cache_dir', $config['cache_dir']);
        $container->setParameter('gregwar_image.cache_dir_mode', $config['cache_dir_mode']);
        $container->setParameter('gregwar_image.throw_exception', $config['throw_exception']);
        $container->setParameter('gregwar_image.fallback_image', $config['fallback_image']);
        $container->setParameter('gregwar_image.web_dir', $config['web_dir']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
