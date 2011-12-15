<?php

namespace Gregwar\ImageBundle\Extensions;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Form\FormView;

/**
 * ImageTwig extension
 *
 * @author Gregwar <g.passault@gmail.com>
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
            'image' => new \Twig_Function_Method($this, 'image', array('is_safe' => array('html')))
        );
    }

    public function image($path)
    {
        return $this->container->get('image.handling')->open($path);
    }

    public function getName()
    {
        return 'image';
    }
}

