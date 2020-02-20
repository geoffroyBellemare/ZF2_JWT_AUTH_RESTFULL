<?php
namespace User;

use User\Authentication\Storage;
use User\Service;
//use ZfrStripe\Client\StripeClient;

return array(
    'initializers' => array(
        function($instance, \Zend\ServiceManager\ServiceLocatorInterface $sm) {
            if ( $instance instanceof \Zend\Db\Adapter\AdapterAwareInterface ) {
                $instance->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
            }

        }
    ),
    'invokables' => array(
        'User\Repository\UserRepository' => 'User\Repository\UserRepositoryImpl',

    ),
    'factories' => array(
        'User\Service\UserService' => function(\Zend\ServiceManager\ServiceLocatorInterface $sm) {
            $userService = new \User\Service\UserServiceImpl();
            $userService->setUserRepository($sm->get('User\Repository\UserRepository'));
            $userService->setJwtStorage($sm->get(Storage\Jwt::class));
            return $userService;
        },
            Storage\Jwt::class => Storage\JwtFactory::class,
            Service\Jwt::class => Service\JwtFactory::class,
            Storage\Header::class => Storage\HeaderFactory::class,
            Storage\Cookie::class => Storage\CookieFactory::class
/*        'GoogleClient' => function(\Zend\ServiceManager\ServiceLocatorInterface $sm) {
            //$googleConfig = $sm->get('config')['google'];
            //$client = new \Google_Client();
            $client = new \Google_Client();
            $client->setClientId('499862033199-dqucrv6pqpkgtlbvskcmuivekfah8cf4.apps.googleusercontent.com');
            $client->setClientSecret('1mLLkdvNDm_j37HEKC_R5CcN');
            $client->setApplicationName('slideguide-dev');
            $client->setRedirectUri('http://zf2.com/GoogleLogin/callback');
            $client->addScope('https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email');

            return $client;
        },
        'stripeClient' => function( \Zend\ServiceManager\ServiceLocatorInterface $sm) {

            $stripeConfig = $sm->get('config');
            $client = new StripeClient($stripeConfig['stripe']['secret_key'],'2015-10-16');
            return $client;
        }*/

    ),
    'form_elements' => array(
    'factories' => array(
        'RegisterFieldSet' => function(\Zend\ServiceManager\ServiceLocatorInterface $sm) {
            $userService = new \User\Service\UserServiceImpl();
            $userService->setUserRepository($sm->get('User\Repository\UserRepository'));
            return $userService;
        }

    )

)
);