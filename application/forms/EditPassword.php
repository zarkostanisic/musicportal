<?php

class Application_Form_EditPassword extends Zend_Form
{

    public function init()
    { 
        $view = Zend_Layout::getMvcInstance()->getView();

        $this->setAction($view->url(array('controller' => 'Administration', 'action' => 'edituser'), null, true))->setMethod('post');
        $this->setAttrib('id', 'form1');
        
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
                    ->setAttrib('class', 'text')
                    ->setRequired(TRUE)
                    ->addValidator('NotEmpty', TRUE)
                    ->addValidator('Alnum', TRUE)
                    ->addValidator('StringLength', TRUE, array('min' => 5, 'max' => 32));
                    
        
        $password2 = new Zend_Form_Element_Password('password2');
        $password2->setLabel('Repeat password')
                    ->setAttrib('class', 'text')
                    ->setRequired(TRUE)
                    ->addValidator('Identical', TRUE, array('token' => 'password'));
        
        $passwordsubmit = new Zend_Form_Element_Submit('passwordsubmit');
        $passwordsubmit->setAttrib('id', 'button');
        $passwordsubmit->setLabel('Edit password');
        
        $hidden = new Zend_Form_Element_Hidden('userId');
        
        $this->addElements(array($password, $password2, $hidden, $passwordsubmit));
    }


}

