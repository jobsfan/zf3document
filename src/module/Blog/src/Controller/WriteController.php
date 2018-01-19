<?php
namespace Blog\Controller;

use Blog\Form\PostForm;
use Blog\Model\Post;
use Blog\Model\PostCommandInterface;
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
    * @param PostCommandInterface $command
    * @param PostForm $form
    */
    public function __construct(PostCommandInterface $command, PostForm $form)
    {
        $this->command = $command;
        $this->form = $form;
    }
    
    public function addAction()
    {
        $request   = $this->getRequest();
        $viewModel = new ViewModel(['form' => $this->form]);
        
        if (! $request->isPost()) {
            return $viewModel;
        }
        
        $this->form->setData($request->getPost());
        
        if (! $this->form->isValid()) {
            return $viewModel;
        }
        
        //$data = $this->form->getData()['post'];
        //$post = new Post($data['title'], $data['text']);
        $post = $this->form->getData();
        
        try {
            $post = $this->command->insertPost($post);
        } catch (\Exception $ex) {
            // An exception occurred; we may want to log this later and/or
            // report it to the user. For now, we'll just re-throw.
            throw $ex;
        }
        
        $layout = $this->layout(); //$layout->setTemplate('layout/layout');
        
        $headerView = new ViewModel();
        $headerView->setTemplate('application/index/header1');
        $layout->addChild($headerView, 'header');
        
        $footerView = new ViewModel();
        $footerView->setTemplate('application/index/footer1');
        $layout->addChild($footerView, 'footer');
        
        return $this->redirect()->toRoute(
            'blog/detail',
            ['id' => $post->getId()]
        );
    }
}