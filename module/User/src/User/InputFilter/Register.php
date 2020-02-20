<?php
/**
 * Created by PhpStorm.
 * User: geoffroy
 * Date: 25/07/2018
 * Time: 14:00
 */

namespace User\InputFilter;


use Zend\Filter\FilterChain;
use Zend\Filter\StringTrim;
use Zend\I18n\Validator\Alnum;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator\StringLength;
use Zend\Validator\ValidatorChain;

class Register extends  InputFilter
{
    public function __construct()
    {



        $first_name = new Input('first_name');
        $first_name->setRequired(true);
        $first_name->setValidatorChain($this->getFirstNameValidatorChain());
        $first_name->setFilterChain($this->getStringTrimFilterChain());



        $this->add($first_name);


    }

    private function getFirstNameValidatorChain() {




        $stringLength = new StringLength();
        $stringLength->setMin(5);

        $validatorChain = new ValidatorChain();
        $validatorChain->attach(new Alnum(true));
        $validatorChain->attach($stringLength);

        return $validatorChain;



    }
    private function getLastNameValidatorChain() {

        $stringLength = new StringLength();
        $stringLength->setMin(3);

        $validatorChain = new ValidatorChain();
        $validatorChain->attach($stringLength);
        $validatorChain->attach(new Alnum('true'));

        return $validatorChain;



    }
    private function getStringTrimFilterChain() {
        $filterChain = new FilterChain();
        $filterChain->attach(new StringTrim());

        return $filterChain;
    }
}