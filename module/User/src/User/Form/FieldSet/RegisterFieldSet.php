<?php
/**
 * Created by PhpStorm.
 * User: geoffroy
 * Date: 24/07/2018
 * Time: 15:12
 */

namespace User\Form\FieldSet;


use User\Entity\User;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class RegisterFieldSet extends Fieldset implements InputFilterProviderInterface, AdapterAwareInterface
{

use AdapterAwareTrait;

public function init()
{

    $this->setHydrator(new ClassMethods());
    $this->setObject(new User());
    $firstname = new Element\Text('first_name');
    $firstname->setAttribute('class', 'form-control');
    $firstname->setLabel('First Name');

    $lastname = new Element\Text('last_name');
    $lastname->setAttribute('class', 'form-control');
    $lastname->setLabel('Lats Name');

    $email = new Element\Email('email');
    $email->setAttribute('class', 'form-control');
    $email->setLabel('Email');

    $password = new Element\Password('password');
    $password->setLabel('Password');
    $password->setAttribute('class', 'form-control');

    $repeatedPassword = new Element\Password('repeatPassword');
    $repeatedPassword->setLabel('Confirm Password');
    $repeatedPassword->setAttribute('class', 'form-control');


    $this->add($firstname);
    $this->add($lastname);
    $this->add($email);
    $this->add($password);
    $this->add($repeatedPassword);
}

    public function getInputFilterSpecification()
    {
        return [
            'first_name' => [
                'required' => true,
                'filters' => [
                    [
                        'name' => 'string_trim',
                    ],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                    [
                        'name' => 'Alnum',
                        'options' =>
                            [
                            'allowWhiteSpace' => true
                            ]
                    ],
                    [
                        'name' => 'StringLength',
                        'options' =>
                            [
                                'min' => 5
                            ]
                    ]
                ]
            ],
            'last_name' => [
                'required' => true
            ],
            'email' => [
                'required' => false,
/*                'validators' => [
                    [
                        'name' => 'Db\NoRecordExists',
                        'options' =>
                            [
                                'table' => 'user',
                                'field' => 'email',
                                'adapter' => $this->adapter,
                                'exclude' => array(
                                    'field' => 'id',
                                    'value' => '',
                                ),

                            ]

                    ]
                ]*/
            ],
            'password' => [
                'required' => false,
                'filters' => [
                    [
                        'name' => 'string_trim'
                    ]
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 3
                        ]
                    ],
                    [
                        'name' => 'Regex',
                        'options' => [
                            'pattern' => '/\d/',
                            'message' => 'It must contain at least one number'
                        ]
                    ]
                ]

            ]
        ];
    }
}