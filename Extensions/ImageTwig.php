<?php

namespace Gregwar\ImageBundle\Extensions;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Form\FormView;

/**
 * ImageTwig extension
 *
 * @author Gregwar <g.passault@gmail.com>
 * @author bzikarsky <benjamin.zikarsky@perbility.de>
 */
class ImageTwig extends \Twig_Extension
{
    private $container;
    private $environment;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'image' => new \Twig_Function_Method($this, 'image', array('is_safe' => array('html'))),
            'new_image' => new \Twig_Function_Method($this, 'newImage', array('is_safe' => array('html'))),
            'web_image' => new \Twig_Function_Method($this, 'webImage', array('is_safe' => array('html'))),
        );
    }

    public function webImage($path)
    {
        $directory = $this->container->getParameter('gregwar_image.web_dir') .'/';
        return $this->container->get('image.handling')->open($directory . $path);
    }

    public function image($path)
    {
        return $this->container->get('image.handling')->open($path);
    }
    
    public function newImage($width, $height)
    {
        return $this->container->get('image.handling')->create($width, $height);
    }

    public function getName()
    {
        return 'image';
    }
}

