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
        
        $eventManager->attach('dispatch', array($this, 'setLayout'), 88);
        $eventManager->attach('dispatch', array($this, 'doAuthorization'), 99);
        
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
    public function setLayout(MvcEvent $e)
    {
        
        /* $matches    = $e->getRouteMatch();
        $controller = $matches->getParam('controller');
        if (false === strpos($controller, __NAMESPACE__)) {
            return;
        }
        
        $viewModel = $e->getViewModel();
        $viewModel->setTemplate('content/layout'); */
        
        
        $viewModel = $e->getViewModel();
        
        $matches = $e->getRouteMatch();
        $controller = $matches->getParam('controller');
        print_r($controller);exit;
        $action = $matches->getParam('action');
        if ($controller == 'Members\Controller\Index' && ($action=='login' || $action=='register'))
        {
            $viewModel->setTemplate('layout/simple');
        }
        elseif ($controller == 'Members\Controller\Works' && $action=='stage')
        {
            $viewModel->setTemplate('layout/stage');
        }
        else
        {
            $viewModel->setTemplate('layout/members');
            
            $headerView = new ViewModel();
            $headerView->setTemplate('members/index/header');
            $viewModel->addChild($headerView, 'header');
            
            $asideView = new ViewModel();
            $asideView->setTemplate('members/index/aside');
            $viewModel->addChild($asideView, 'aside');
            
            $footerView = new ViewModel();
            $footerView->setTemplate('members/index/footer');
            $viewModel->addChild($footerView, 'footer');
        }
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}