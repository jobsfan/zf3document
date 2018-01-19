<?php
namespace Blog\Controller;

use Blog\Model\PostRepositoryInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use InvalidArgumentException;

class ListController extends AbstractActionController
{
    /**
    * @var PostRepositoryInterface
    */
    private $postRepository;
    
    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }
    
    public function indexAction()
    {
        $layout = $this->layout(); //$layout->setTemplate('layout/layout');
        
        $headerView = new ViewModel();
        $headerView->setTemplate('application/index/header1');
        $layout->addChild($headerView, 'header');
        
        $footerView = new ViewModel();
        $footerView->setTemplate('application/index/footer1');
        $layout->addChild($footerView, 'footer');
        
        return new ViewModel([
            'posts' => $this->postRepository->findAllPosts(),
        ]);
    }
    
    public function detailAction()
    {
        $id = $this->params()->fromRoute('id');
        
        try {
            $post = $this->postRepository->findPost($id);
        } catch (\InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('blog');
        }
        
        $layout = $this->layout(); //$layout->setTemplate('layout/layout');
        
        $headerView = new ViewModel();
        $headerView->setTemplate('application/index/header1');
        $layout->addChild($headerView, 'header');
        
        $footerView = new ViewModel();
        $footerView->setTemplate('application/index/footer1');
        $layout->addChild($footerView, 'footer');
        
        return new ViewModel([
            'post' => $post,
        ]);
    }
}