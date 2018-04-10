<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
    * 管理的首页
    * @param 入参
    * @return 出参
    * @author Jobs Fan
    * @date: 2018年4月10日
    */
    public function indexAction()
    {
        return new ViewModel();
    }
    
    /**
    * 登录页
    * @param 入参
    * @return 出参
    * @author Jobs Fan
    * @date: 2018年4月10日
    */
    public function loginAction()
    {
        return new ViewModel();
    }
    
    /**
    * 注销页面
    * @param 入参
    * @return 出参
    * @author Jobs Fan
    * @date: 2018年4月10日
    */
    public function logoutAction()
    {
        return new ViewModel();
    }
    
    /**
    * 注册页面
    * @param 入参
    * @return 出参
    * @author Jobs Fan
    * @date: 2018年4月10日
    */
    public function registerAction()
    {
        return new ViewModel();
    }
    
    /**
    * 忘记密码
    * @param 入参
    * @return 出参
    * @author Jobs Fan
    * @date: 2018年4月10日
    */
    public function forgotAction()
    {
        return new ViewModel();
    }
}