<?php

class UsersController extends Zend_Controller_Action
{

    public function preDispatch()
    {
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
        // action body
    }

    public function registrationAction()
    {
        $users_model = new Application_Model_Users();
        
        $this->view->headTitle()->prepend('Registration');
        
        $registration = new Application_Form_Registration();
        
        $request = $this->getRequest();
        
        if($request->getPost()){
            if($registration->isValid($request->getPost()) == 1){
                $username = $request->getPost('username');
                $password = $request->getPost('password');
                $email = $request->getPost('email');
                $city = $request->getPost('city');
                
                $insert = $users_model->register($username, $password, $email, $city);
                
                if($insert){
                   echo "Registration success";
                   $registration->reset();
                }
            }
        }
        $this->view->registration = $registration;
        
        
    }

    public function loginAction()
    {
        $request = $this->getRequest();
        
        $username = $request->getParam('username');
        $password = md5($request->getParam('password'));
        
        $users_model = new Application_Model_Users();
        
        $role = $users_model->login($username, $password);
        
        if($role == 'admin'){
            $this->_redirect('/Administration/index');
        }else{
            $this->_redirect('/Index/index');
        }
    }

    public function logoutAction()
    {
        $session = new Zend_Auth_Storage_Session();
        $session->clear();
        
        $this->_redirect('/Index/Index');
    }


}







