<?php
namespace Admin;

use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Segment;

return [
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
        'invokables' => [
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
        ],
    ],
    
    'router' => [
        'routes' => [
            'admin' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin/[:controller[/:action]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*[a-zA-Z0-9]',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*[a-zA-Z0-9]',
                    ),
                    'defaults' => [
                        '__NAMESPACE__' => 'Admin\Controller', //这个好像是必须的
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    
    'view_manager' => [
        'template_path_stack' => [
            'admin' => __DIR__ . '/../view',
        ],
    ],
];
