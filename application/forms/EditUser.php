<?php

class Application_Form_EditUser extends Zend_Form
{

    public function init()
    {
        $view = Zend_Layout::getMvcInstance()->getView();

        $roles_model = new Application_Model_Roles();
        
        $roles = $roles_model->getAll();
        $roles_list = array();
        foreach($roles as $role){
            $roles_list[$role['roleId']] = $role['roleTitle'];
        }
        
        $this->setAction($view->url(array('controller' => 'Administration', 'action' => 'edituser'), null, true))->setMethod('post');
        $this->setAttrib('id', 'form1');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
                ->setAttrib('class', 'text')
                ->setRequired(TRUE)
                ->addValidator('EmailAddress', TRUE)->addErrorMessage('Wrong format');
        
        $passwordsubmit = new Zend_Form_Element_Submit('passwordsubmit');
        $passwordsubmit->setAttrib('id', 'button');
        $passwordsubmit->setLabel('Edit password');
        
        $roleId = new Zend_Form_Element_Select('role');
        $roleId->setLabel('Role')
                ->setAttrib('class', 'text')
                ->addMultiOption('0','Choose')
                ->addMultiOptions($roles_list)
                ->setRequired(TRUE)
                ->addValidator('GreaterThan', TRUE, array('min' => 0))->addErrorMessage('Choose role');
        
        $hidden = new Zend_Form_Element_Hidden('userId');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'button');
        $submit->setLabel('Edit');
        
        $this->addElements(array($email, $roleId, $hidden, $submit));
    }


}

