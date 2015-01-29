<?php

class Application_Form_EditSong extends Zend_Form
{

    public function init()
    {
        $view = Zend_Layout::getMvcInstance()->getView();

        $this->setAction($view->url(array('controller' => 'Administration', 'action' => 'editsong'), null, true));
        $this->setMethod('post');
        $this->setAttrib('id', 'form1');
        $this->setEnctype('multipart/form-data');
        
        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Title')
                ->setAttrib('class', 'text')
                ->setRequired(TRUE)
                ->addValidator('Alpha', TRUE, array('allowWhiteSpace' => TRUE))
                ->addValidator('StringLength', TRUE, array('min' => 3, 'max' => 50));
        
        $hidden = new Zend_Form_Element_Hidden('songId');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Edit')
                ->setAttrib('id', 'button');
        
        $this->addElements(array($title, $hidden, $submit));
    }


}

