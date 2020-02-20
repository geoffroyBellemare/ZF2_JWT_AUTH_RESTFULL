<?php

namespace User;


return array(
    'form_elements' => array(
        'factories' => array(
            'User\Form\Register' => 'User\Form\RegisterFormFactory',
            'User\Form\RegisterFieldSet' => 'User\Form\FieldSet\RegisterFieldSetFactory',
        ),
    )
);