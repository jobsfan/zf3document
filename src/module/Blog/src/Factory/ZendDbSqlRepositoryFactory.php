<?php
namespace Blog\Factory;

use Interop\Container\ContainerInterface;
use Blog\Model\ZendDbSqlRepository;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ZendDbSqlRepositoryFactory implements FactoryInterface
{
    /**
    * @param ContainerInterface $container
    * @param string $requestedName
    * @param null|array $options
    * @return ZendDbSqlRepository
    */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ZendDbSqlRepository($container->get(AdapterInterface::class));
    }
}