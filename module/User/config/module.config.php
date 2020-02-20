<?php

namespace User;

use User\Form\FieldSet\RegisterFieldSet;
use Zend\Di\ServiceLocatorInterface;
use User\Authentication\Storage;
use Lcobucci\JWT\Signer\Rsa\Sha256;
return array(
    'router' => array(
        'routes' => array(
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /user/:controller/:action
            'user' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/zf/user1/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'User\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                /*'may_terminate' => true,
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
                */
            ),
            'api_user' => array(
                'type'    => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/zf/api/user[/:slug]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'User\Controller',
                        'controller'    => 'User',
                        //'action'        => 'index',
                    ),
                ),
            ),
            /*'login' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' =>'/login',
                    'defaults' => array(
                        '__NAMESPACE__' => 'User\Controller',
                        'controller' => 'Index',
                        'action' => 'login'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' =>'/[:slug]',
                        ),
                        'defaults' => array(
                            'controller' => 'Index',
                            'action' => 'login'
                        ),


                    )
                )
            ),
            'logout' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' =>'/logout',
                    'defaults' => array(
                        '__NAMESPACE__' => 'User\Controller',
                        'controller' => 'Index',
                        'action' => 'logout'
                    )
                )
            ),
            'google-login' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' =>'/GoogleLogin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'User\Controller',
                        'controller' => 'Index',
                        'action' => 'googleLogin'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' =>'[/:action]',
                        ),
                        'defaults' => array(

                        ),
                        'constraints' => array(
                            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),

                    )
                )

            ),
            */
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'User\Controller\Index' => Controller\IndexController::class,
            'User\Controller\User' => Controller\UserController::class
        ),
    ),

    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'form_elements' => array(
        'factories' => array(
            'User\Form\Register' => 'User\Form\RegisterFormFactory',
            'User\Form\RegisterFieldSet' => 'User\Form\FieldSet\RegisterFieldSetFactory',
        ),
    ),
    'jwt_zend_auth' => [
        'signer' => Sha256::class,
        'readOnly' => false,
        'signKey' => 'grosroberttttttt',
        // Set the key to verify the token with, value is dependent on signer set.
        'verifyKey' => 'grosroberttttttt',
        'expiry' => 60000,
        'cookieOptions' => [
            'path' => '/',
            'domain' => null,
            'secure' => true,
            'httpOnly' => true,
        ],
        'storage' => [
            'adaptor' => Storage\Header::class,
            'useChainAdaptor' => false,
            'adaptors' => [Storage\Header::class, Storage\Cookie::class],
        ],
    ]
);