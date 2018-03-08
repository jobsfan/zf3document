<?php
namespace Blog\Controller;

use Blog\Model\Post;
use Blog\Model\PostCommandInterface;
use Blog\Model\PostRepositoryInterface;
use InvalidArgumentException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class DeleteController extends AbstractActionController
{
    /**
    * @var PostCommandInterface
    */
    private $command;
    
    /**
    * @var PostRepositoryInterface
    */
    private $repository;
    
    /**
    * @param PostCommandInterface $command
    * @param PostRepositoryInterface $repository
    */
    public function __construct(
        PostCommandInterface $command,
        PostRepositoryInterface $repository
        ) {
            $this->command = $command;
            $this->repository = $repository;
    }
    
    public function deleteAction()
    {
        exit('666');
        $id = $this->params()->fromRoute('id');
        if (! $id) {
            return $this->redirect()->toRoute('blog');
        }
        
        try {
            $post = $this->repository->findPost($id);
        } catch (InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('blog');
        }
        
        $layout = $this->layout(); //$layout->setTemplate('layout/layout');
        
        $headerView = new ViewModel();
        $headerView->setTemplate('application/index/header1');
        $layout->addChild($headerView, 'header');
        
        $footerView = new ViewModel();
        $footerView->setTemplate('application/index/footer1');
        $layout->addChild($footerView, 'footer');
        
        $request = $this->getRequest();
        if (! $request->isPost()) {
            return new ViewModel(['post' => $post]);
        }
        
        if ($id != $request->getPost('id')
            || 'Delete' !== $request->getPost('confirm', 'no')
            ) {
                return $this->redirect()->toRoute('blog');
        }
            
        $post = $this->command->deletePost($post);
        return $this->redirect()->toRoute('blog');
    }
}
