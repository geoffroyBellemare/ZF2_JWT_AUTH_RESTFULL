<?php
/**
 * Created by PhpStorm.
 * User: geoffroy
 * Date: 25/07/2018
 * Time: 22:53
 */

namespace User\Form\FieldSet;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegisterFieldSetFactory implements  FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sm = $serviceLocator->getServiceLocator();
        $register = new RegisterFieldSet();
        $register->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
        return $register;
    }
}