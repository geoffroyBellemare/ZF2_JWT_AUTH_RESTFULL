<?php

namespace Prestation;

return array(
    'router' => array(
        'routes' => array(
/*            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/zf/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),*/
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /zf/:controller/:action
/*            'api' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/api[/:slug]',
                    'constraints' => array(
                        'slug' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Prestation\Controller',
                        'controller'    => 'Prestation'
                    ),
                ),
            ),*/
            'apiii' => array(
                'type'    => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/zf/api2/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Prestation\Controller',
                        'controller'    => 'Prestation',
                        //'action'        => 'index',
                    ),
                ),
            ),
            'apii' => array(
                'type'    => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/zf/api/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Prestation\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Prestation\Controller\Index' => Controller\IndexController::class,
            'Prestation\Controller\Prestation' => Controller\PrestationRestController::class
        ),
    ),

    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
/*        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),*/
    ),
);