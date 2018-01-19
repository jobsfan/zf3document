<?php
namespace Blog\Controller;

use Blog\Form\PostForm;
use Blog\Model\Post;
use Blog\Model\PostCommandInterface;
use Blog\Model\PostRepositoryInterface;
use InvalidArgumentException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class WriteController extends AbstractActionController
{
    /**
    * @var PostCommandInterface
    */
    private $command;
    
    /**
    * @var PostForm
    */
    private $form;
    
    /**
    * @var PostRepositoryInterface
    */
    private $repository;
    
    /**
    * @param PostCommandInterface $command
    * @param PostForm $form
    * @param PostRepositoryInterface $repository
    */
    public function __construct(
        PostCommandInterface $command,
        PostForm $form,
        PostRepositoryInterface $repository
        ) {
            $this->command = $command;
            $this->form = $form;
            $this->repository = $repository;
    }
    
    public function addAction()
    {
        $layout = $this->layout(); //$layout->setTemplate('layout/layout');
        
        $headerView = new ViewModel();
        $headerView->setTemplate('application/index/header1');
        $layout->addChild($headerView, 'header');
        
        $footerView = new ViewModel();
        $footerView->setTemplate('application/index/footer1');
        $layout->addChild($footerView, 'footer');
        
        $request   = $this->getRequest();
        $viewModel = new ViewModel(['form' => $this->form]);
        
        if (! $request->isPost()) {
            return $viewModel;
        }
        
        $this->form->setData($request->getPost());
        
        if (! $this->form->isValid()) {
            return $viewModel;
        }
        
        $post = $this->form->getData();
        
        try {
            $post = $this->command->insertPost($post);
        } catch (\Exception $ex) {
            // An exception occurred; we may want to log this later and/or
            // report it to the user. For now, we'll just re-throw.
            throw $ex;
        }
        
        return $this->redirect()->toRoute(
            'blog/detail',
            ['id' => $post->getId()]
        );
    }
    
    public function editAction()
    {
        $layout = $this->layout(); //$layout->setTemplate('layout/layout');
        
        $headerView = new ViewModel();
        $headerView->setTemplate('application/index/header1');
        $layout->addChild($headerView, 'header');
        
        $footerView = new ViewModel();
        $footerView->setTemplate('application/index/footer1');
        $layout->addChild($footerView, 'footer');
        
        $id = $this->params()->fromRoute('id');
        if (! $id) {
            return $this->redirect()->toRoute('blog');
        }
        
        try {
            $post = $this->repository->findPost($id);
        } catch (InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('blog');
        }
        
        $this->form->bind($post);
        $viewModel = new ViewModel(['form' => $this->form]);
        
        $request = $this->getRequest();
        if (! $request->isPost()) {
            return $viewModel;
        }
        
        $this->form->setData($request->getPost());
        
        if (! $this->form->isValid()) {
            return $viewModel;
        }
        
        $post = $this->command->updatePost($post);
        return $this->redirect()->toRoute(
            'blog/detail',
            ['id' => $post->getId()]
        );
    }
}