<?php

namespace User\Controller;


use User\Entity\User;
use User\Form\LoginForm;
use User\Form\RegisterForm;
use User\InputFilter\Register;
use User\Service\UserService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {

        /**
         * @var UserService $userService
         */
        $userService = $this->getServiceLocator()->get('User\Service\UserService');
        $form = $this->getServiceLocator()->get( 'FormElementManager' )->get('User\Form\Register');
        return new ViewModel(array('form' => $form));
    }

} 