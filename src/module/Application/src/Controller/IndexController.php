<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $layout = $this->layout(); //$layout->setTemplate('layout/layout');
         
        $headerView = new ViewModel();
        $headerView->setTemplate('application/index/header1');
        $layout->addChild($headerView, 'header');
         
        $footerView = new ViewModel();
        $footerView->setTemplate('application/index/footer1');
        $layout->addChild($footerView, 'footer');
        
        return new ViewModel();
    }
    
    /**
    * 文档规范
    * @param 
    * @return what return
    * @author Jobs Fan
    * @date: 上午11:10:54
    */
    public function specificationAction()
    {
        $layout = $this->layout(); //$layout->setTemplate('layout/layout');
         
        $headerView = new ViewModel();
        $headerView->setTemplate('application/index/header1');
        $layout->addChild($headerView, 'header');
         
        $footerView = new ViewModel();
        $footerView->setTemplate('application/index/footer1');
        $layout->addChild($footerView, 'footer');
        
        return new ViewModel();
    }
    
    /**
    * 踩过的坑
    * @param 
    * @return what return
    * @author Jobs Fan
    * @date: 上午11:11:27
    */
    public function trapAction()
    {
        $layout = $this->layout(); //$layout->setTemplate('layout/layout');
         
        $headerView = new ViewModel();
        $headerView->setTemplate('application/index/header1');
        $layout->addChild($headerView, 'header');
         
        $footerView = new ViewModel();
        $footerView->setTemplate('application/index/footer1');
        $layout->addChild($footerView, 'footer');
        
        return new ViewModel();
    }
    
    /**
    * 计划列表
    * @param
    * @return what return
    * @author Jobs Fan
    * @date: 上午11:11:27
    */
    public function todolistAction()
    {
        $layout = $this->layout(); //$layout->setTemplate('layout/layout');
        
        $headerView = new ViewModel();
        $headerView->setTemplate('application/index/header1');
        $layout->addChild($headerView, 'header');
        
        $footerView = new ViewModel();
        $footerView->setTemplate('application/index/footer1');
        $layout->addChild($footerView, 'footer');
        
        return new ViewModel();
    }
}
