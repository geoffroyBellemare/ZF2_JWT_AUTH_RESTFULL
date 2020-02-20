<?php
/**
 * Created by PhpStorm.
 * User: geoffroy
 * Date: 26/07/2018
 * Time: 01:56
 */

namespace User\Form;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegisterFormFactory implements  FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        return new RegisterForm();
    }
}