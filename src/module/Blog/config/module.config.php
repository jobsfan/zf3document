<?php
namespace Blog;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'aliases' => [
            //Model\PostRepositoryInterface::class => Model\PostRepository::class,
            Model\PostRepositoryInterface::class => Model\ZendDbSqlRepository::class,
            //Model\PostCommandInterface::class => Model\PostCommand::class,
            Model\PostCommandInterface::class => Model\ZendDbSqlCommand::class,
        ],
        'factories' => [
            Model\PostRepository::class => InvokableFactory::class,
            Model\ZendDbSqlRepository::class => Factory\ZendDbSqlRepositoryFactory::class,
            Model\PostCommand::class => InvokableFactory::class,
            Model\ZendDbSqlCommand::class => Factory\ZendDbSqlCommandFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            //Controller\ListController::class => InvokableFactory::class,
            Controller\ListController::class => Factory\ListControllerFactory::class,
            Controller\WriteController::class => Factory\WriteControllerFactory::class,
        ],
    ],
    // This lines opens the configuration for the RouteManager
    'router'          => [
        'routes' => [
            'blog' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/blog',
                    'defaults' => [
                        'controller' => Controller\ListController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'detail' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/:id',
                            'defaults' => [
                                'action' => 'detail',
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*',
                            ],
                        ],
                    ],
                    'add' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/add',
                            'defaults' => [
                                'controller' => Controller\WriteController::class,
                                'action'     => 'add',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];