<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Zend\Authentication\AuthenticationService' => function(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
                /**
                 * @var \User\Service\UserService $userService
                 */
                $userService = $serviceLocator->get('User\Service\UserService');
                return $userService->getAuthenticationService();
            },
        )
    ),
    'jwt_zend_auth' => array (
        // Choose signing method for the tokens
        'signer' => \Lcobucci\JWT\Signer\Rsa\Sha256::class,
        /*
            You need to specify either a signing key or set read only to true.
            If tokens are read only, the implementation will not automatically
            refresh tokens which are close to expiry so you will need to handle
            this yourself.
        */
        'readOnly' => false,
        // Set the key to sign the token with, value is dependent on signer set.
        'signKey' => 'grosroberttttttt',
        // Set the key to verify the token with, value is dependent on signer set.
        'verifyKey' => 'grosroberttttttt',
        /*
            Default expiry for tokens. A token will expire after not being used
            for this number of seconds. A token which is used will automatically
            be extended provided a sign key is provided.
        */
        'expiry' => 60000
    )
);