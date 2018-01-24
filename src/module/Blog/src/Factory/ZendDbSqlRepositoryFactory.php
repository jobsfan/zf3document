<?php
namespace Blog\Factory;

use Interop\Container\ContainerInterface;
use Blog\Model\Post;
use Blog\Model\ZendDbSqlRepository;
//use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Db\Adapter\Adapter;

class ZendDbSqlRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $tutorialDb = $config['tutorial'];
        $dbAdapter = new Adapter($tutorialDb);
        
        return new ZendDbSqlRepository(
            $dbAdapter, //$container->get(AdapterInterface::class)
            new ReflectionHydrator(),
            new Post('', '')
            );
    }
}