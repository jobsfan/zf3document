<?php
namespace Blog\Factory;

use Interop\Container\ContainerInterface;
use Blog\Model\ZendDbSqlCommand;
//use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Db\Adapter\Adapter;

class ZendDbSqlCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $tutorialDb = $config['tutorial'];
        $dbAdapter = new Adapter($tutorialDb);
        return new ZendDbSqlCommand($dbAdapter); //$container->get(AdapterInterface::class)
    }
}