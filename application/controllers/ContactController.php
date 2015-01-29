<?php

class ContactController extends Zend_Controller_Action
{
    public function preDispatch(){
        $categories_model = new Application_Model_Categories();
        $categories = $categories_model->getAll();
        $this->view->categories = $categories;
        
        $this->view->render('/placeholders/_left_menu.phtml');
        $this->view->render('/placeholders/_menu.phtml');
        $this->view->render('/placeholders/_footer.phtml');
        
    }

    public function init()
    {
        $session = new Zend_Auth_Storage_Session();
        $sess = $session->read();
        $role_title = $sess['roleTitle'];
        $username_title = $sess['username'];
        $this->view->role_title = $role_title;
        $this->view->username_title = $username_title;
        
        $urls['HOME'] = $this->view->url(array('controller' => 'Index', 'action' => 'index'), null, true);
        $urls['PLAYLISTS'] = $this->view->url(array('controller' => 'Playlists', 'action' => 'index'), null, true);
        if(isset($role_title) && $role_title == 'admin'){
            $urls['ADMIN'] = $this->view->url(array('controller' => 'Administration', 'action' => 'index'), null, true);
        }
        if(!isset($role_title)){
            $urls['REGISTRATION'] = $this->view->url(array('controller' => 'Users', 'action' => 'registration'), null, true);
        }
        $urls['WISHES'] = $this->view->url(array('controller' => 'Songs', 'action' => 'wishes'), null, true);
        $urls['ABOUT'] = $this->view->url(array('controller' => 'Index', 'action' => 'about'), null, true);
        $urls['CONTACT'] = $this->view->url(array('controller' => 'Contact', 'action' => 'index'), null, true);

        
        foreach($urls as $url=>$v){
            $this->view->placeholder('menu')->append('<li><a href="' . $v . '">' . $url . '</a></li>');
            $this->view->placeholder('footer')->append('<a href="' . $v . '">' . $url . '</a>');
        }
        
        $artists_model = new Application_Model_Artists();
        $popular = $artists_model->getPopular();
        $this->view->popular = $popular;
    }

    public function indexAction()
    {
        $this->view->headTitle()->prepend('Contact');
        
        $contact = new Application_Form_Contact();
        
        $request = $this->getRequest();
        
        if($request->getPost()){
            if($contact->isValid($request->getPost()) == 1){
                echo "Email success send";
                $contact->reset();
            }
        }
        $this->view->contact = $contact;
    }


}

