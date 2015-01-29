<?php

class Application_Form_EditPlaylist extends Zend_Form
{

    public function init()
    {
        $view = Zend_Layout::getMvcInstance()->getView();

        $this->setAction($view->url(array('controller' => 'Administration', 'action' => 'editplaylist'), null, true));
        $this->setMethod('post');
        $this->setAttrib('id', 'form1');
        
        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Title')
                ->setAttrib('class', 'text');
        
        $hidden = new Zend_Form_Element_Hidden('playlistId');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'button')
                ->setLabel('Edit');
        
        $this->addElements(array($title, $hidden, $submit));
    }


}

