<?php
namespace Blog\Factory;

use Blog\Controller\WriteController;
use Blog\Form\PostForm;
use Blog\Model\PostCommandInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class WriteControllerFactory implements FactoryInterface
{
    /**
    * @param ContainerInterface $container
    * @param string $requestedName
    * @param null|array $options
    * @return WriteController
    */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $formManager = $container->get('FormElementManager');
        return new WriteController(
            $container->get(PostCommandInterface::class),
            $formManager->get(PostForm::class)
        );
    }
}