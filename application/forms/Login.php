<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        $view = Zend_Layout::getMvcInstance()->getView();

        $this->setAction($view->url(array('controller' => 'Users', 'action' => 'login'), null, true));
        $this->setMethod('post');
        $this->setAttrib('id', 'subscribe');
        
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username');
        $username->setRequired(true);
        $username->addValidator('NotEmpty')->addErrorMessage('*');
        
        
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password');
        $username->setRequired(true);
        $username->addValidator('NotEmpty')->addErrorMessage('*');
        
        $submit = new Zend_Form_Element_Submit('login');
        $submit->setAttrib('id', 'login_btn');
        $submit->setLabel('');

        
        
        $this->addElements(array($username, $password, $submit));
        
        
        $this->setElementDecorators(array(
            'ViewHelper',
            /*'Errors',*/
            array('HtmlTag', array('tag' => 'div')),
            array('Label', array('tag' => 'p')),
            
        ));
        
        $submit->setDecorators(array(
                    'ViewHelper',
                    array(
                        array('row'=>'HtmlTag'),array('tag'=>'span')
                    )
                ));
        
        $this->setDecorators(array(
            'FormElements', array('HtmlTag', array('tag' => 'fieldset')), 'Form'
        ));
        
    }


}

