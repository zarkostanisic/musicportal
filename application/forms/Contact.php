<?php

class Application_Form_Contact extends Zend_Form
{

    public function init()
    {
        $view = Zend_Layout::getMvcInstance()->getView();

        $this->setAction($view->url(array('controller' => 'Contact', 'action' => 'index'), null, true));
        $this->setMethod('post');
        $this->setAttrib('id', 'form1');
        
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
                ->setAttrib('class', 'text')
                ->setRequired(TRUE)
                ->addValidator('NotEmpty', TRUE)
                ->addValidator('Alpha', TRUE)
                ->addValidator('StringLength', TRUE, array('min' => 3, 'max' => 30));
        
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
                ->setAttrib('class', 'text')
                ->setRequired(true)
                ->addValidator('EmailAddress', TRUE)->addErrorMessage('Wrong format');
        
        $message = new Zend_Form_Element_Textarea('message');
        $message->setLabel('Message')
                    ->setRequired(TRUE)
                    ->addValidator('StringLength', TRUE, array('min' => 10, 'max' => 1000));
        
        $captcha = new Zend_Form_Element_Captcha('captcha',array(
            'captcha' => array(
                'captcha' => 'Figlet',
                'wordlen' => 6
            )
        ));
        
        $captcha->setLabel('Verification code');
        $captcha->setAttrib('class', 'text');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Send')
                ->setAttrib('id', 'button');
        
        $this->addElements(array($name, $email, $message, $captcha, $submit));
    }


}

