<?php

class Application_Form_Registration extends Zend_Form
{

    public function init()
    {
        $view = Zend_Layout::getMvcInstance()->getView();

        $cities_model = new Application_Model_Cities();
        $cities = $cities_model->getList();
        
        $cities_list = array();
        
        foreach($cities as $c){
            $cities_list[$c['cityId']] = $c['cityName'];
        }
        
        $this->setAction($view->url(array('controller' => 'Users', 'action' => 'registration'), null, true))->setMethod('post');
        $this->setAttrib('id', 'form1');
        
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username')
                    ->setAttrib('class', 'text')
                    ->setRequired(TRUE)
                    ->addValidator('NotEmpty', TRUE)
                    ->addValidator('Alnum', TRUE)
                    ->addValidator('StringLength', TRUE, array('min' => 5, 'max' => 30))
                    ->addValidator('Db_NoRecordExists', TRUE, array('users', 'username'));
        
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
        
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
                ->setAttrib('class', 'text')
                ->setRequired(TRUE)
                ->addValidator('EmailAddress', TRUE)->addErrorMessage('Wrong format');
        
        $city = new Zend_Form_Element_Select('city');
        $city->setLabel('City')
                ->setAttrib('class', 'text')
                ->addMultiOption('0', 'Choose')
                ->addMultiOptions($cities_list)
                ->addValidator('GreaterThan', TRUE, array('min' => '0'))->addErrorMessage('Choose city');
        
        $captcha = new Zend_Form_Element_Captcha('captcha',array(
            'captcha' => array(
                'captcha' => 'Figlet',
                'wordlen' => 6
            )
        ));
        
        $captcha->setLabel('Verification code');
        $captcha->setAttrib('class', 'text');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'button');
        $submit->setLabel('Register');
        
        $this->addElements(array($username, $password, $password2, $email, $city, $captcha, $submit));
    }


}

