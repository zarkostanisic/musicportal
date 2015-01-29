<?php

class Application_Form_AddSongInPlaylist extends Zend_Form
{

    public function init()
    {
        $view = Zend_Layout::getMvcInstance()->getView();

        $this->setAction($view->url(array('controller' => 'Playlists', 'action' => 'useraddplaylist'), null, true));
        $this->setMethod('post');
        $this->setAttrib('id', 'form1');
        $this->setEnctype('multipart/form-data');
        
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username');
        $username->setAttrib('class', 'text');
        $username->setRequired(true);
        $username->addValidator('NotEmpty')->addErrorMessage('*');
        
        
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password');
        $password->setAttrib('class', 'text');
        $password->setRequired(true);
        $password->addValidator('NotEmpty')->addErrorMessage('*');

        $playlist = new Zend_Form_Element_Select('playlist');
        $playlist->setLabel('Playlist')
                ->setAttrib('class', 'text')
                ->addMultiOption('0','Choose')
                ->setRequired(TRUE)
                ->addValidator('GreaterThan', TRUE, array('min' => 0))->addErrorMessage('Choose playlist');
        
        $song = new Zend_Form_Element_Select('song');
        $song->setLabel('Song')
                ->setAttrib('class', 'text')
                ->addMultiOption('0','Choose')
                ->setRequired(TRUE)
                ->addValidator('GreaterThan', TRUE, array('min' => 0))->addErrorMessage('Choose song');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Add')
                ->setAttrib('id', 'button');
        
        $this->addElements(array($username, $password, $playlist, $song, $submit));
    }


}

