<?php

class Application_Form_EditArtist extends Zend_Form
{

    public function init()
    {
        $view = Zend_Layout::getMvcInstance()->getView();

        $this->setAction($view->url(array('controller' => 'Administration', 'action' => 'editartist'), null, true));
        $this->setMethod('post');
        $this->setAttrib('id', 'form1');
         $this->setEnctype('multipart/form-data');
        
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
                ->setAttrib('class', 'text')
                ->setRequired(TRUE)
                ->addValidator('Alpha', TRUE, array('allowWhiteSpace' => TRUE))
                ->addValidator('StringLength', TRUE, array('min' => 3, 'max' => 50));
        
        $about = new Zend_Form_Element_Textarea('about');
        $about->setLabel('About')
                ->setRequired(TRUE)
                ->addValidator('StringLength', TRUE, array('min' => 5, 'max' => 200));
        
        $hidden = new Zend_Form_Element_Hidden('artistId');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Edit')
                ->setAttrib('id', 'button');
        
        $this->addElements(array($name, $about, $hidden, $submit));
    }


}

