<?php

class Application_Form_AddPlaylist extends Zend_Form
{

    public function init()
    {
        $view = Zend_Layout::getMvcInstance()->getView();

        $this->setAction($view->url(array('controller' => 'Administration', 'action' => 'playlists'), null, true));
        $this->setMethod('post');
        $this->setAttrib('id', 'form1');
        
        $song = new Zend_Form_Element_Text('song');
        $song->setLabel('Song name')
                ->setAttrib('class', 'text')
                ->setAttrib('id', 'searchsong');
        
        $this->addElements(array($song));
    }


}

