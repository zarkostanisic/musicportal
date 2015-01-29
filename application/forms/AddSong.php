<?php

class Application_Form_AddSong extends Zend_Form
{

    public function init()
    {
        $view = Zend_Layout::getMvcInstance()->getView();

        $artist_model = new Application_Model_Artists();
        $artists = $artist_model->getAdmin();
        
        $artists_list = array();
        
        foreach($artists as $artist){
            $artists_list[$artist['artistId']] = $artist['artistName'];
        }
        
        $this->setAction($view->url(array('controller' => 'Administration', 'action' => 'songs'), null, true));
        $this->setMethod('post');
        $this->setAttrib('id', 'form1');
        $this->setEnctype('multipart/form-data');
        
        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Title')
                ->setAttrib('class', 'text')
                ->setRequired(TRUE)
                ->addValidator('Alpha', TRUE, array('allowWhiteSpace' => TRUE))
                ->addValidator('StringLength', TRUE, array('min' => 3, 'max' => 50))
                ->addValidator('Db_NoRecordExists', TRUE, array('songs', 'songTitle'));
        
        $file = new Zend_Form_Element_File('file');
        $file->setLabel('File')
                ->setAttrib('class', 'image')
                ->setMaxFileSize(8388608)
                ->setRequired(TRUE)
                ->addValidator('Extension', FALSE, array('mp3'))
                ->addValidator('Size', TRUE, array('max' => 8388608));
        
        $artist = new Zend_Form_Element_Select('artist');
        $artist->setLabel('Artist')
                ->setAttrib('class', 'text')
                ->addMultiOption('0','Choose')
                ->addMultiOptions($artists_list)
                ->setRequired(TRUE)
                ->addValidator('GreaterThan', TRUE, array('min' => 0))->addErrorMessage('Choose artist');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Add')
                ->setAttrib('id', 'button');
        
        $this->addElements(array($title, $artist, $file, $submit));
    }


}

