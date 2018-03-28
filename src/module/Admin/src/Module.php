<?php
namespace Admin;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements ConfigProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
    
    /**
    * 授权方法，统一的授权在这里进行
    * @param 入参
    * @return 出参
    * @author Jobs Fan
    * @date: 2018年3月28日
    */
    public function doAuthorization()
    {
        
    }
    
    /**
    * 为授权模块admin统一设置视图
    * @param 入参
    * @return 出参
    * @author Jobs Fan
    * @date: 2018年3月28日
    */
    public function setLayout()
    {
        
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}