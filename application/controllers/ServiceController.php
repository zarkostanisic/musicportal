<?php

class ServiceController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
        
        $option =  array(
            'uri' => 'http://127.0.0.1/musicportal/index.php/Service/index'
        );
        
        $server = new Zend_Soap_Server(null, $option);
        $server->setClass('Service_playlist');
        
        $server->handle();
    }
}

