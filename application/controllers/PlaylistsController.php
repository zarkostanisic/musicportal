<?php

class PlaylistsController extends Zend_Controller_Action
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
        $this->view->headTitle()->prepend('Playlists');
        
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
        /*$option =  array(
            'location' => 'http://127.0.0.1/musicportal/index.php/Service/index',
            'uri' => 'http://127.0.0.1/musicportal/index.php/Service/index'
        );
        $client = new Zend_Soap_Client(null, $option);*/
        
        $client = new Service_playlist();

        $playlists = $client->getplaylist();
        
        $this->view->playlists = $playlists;
    }

    public function showAction()
    {
        $request = $this->getRequest();
        
        $id = $request->getParam('id');
        
        /*$option =  array(
            'location' => 'http://127.0.0.1/musicportal/index.php/Service/index',
            'uri' => 'http://127.0.0.1/musicportal/index.php/Service/index'
        );
        $client = new Zend_Soap_Client(null, $option);*/

        $client = new Service_playlist();

        $songs = $client->getsong($id);
 
        $this->view->songs = $songs;
    }

    public function useraddplaylistAction()
    {
        $request = $this->getRequest();
        
        $add_form = new Application_Form_AddSongInPlaylist();
        
        /*$option =  array(
            'location' => 'http://127.0.0.1/musicportal/index.php/Service/index',
            'uri' => 'http://127.0.0.1/musicportal/index.php/Service/index'
        );
        $client = new Zend_Soap_Client(null, $option);*/

        $client = new Service_playlist();
        
        $playlists = $client->getplaylists();
        $songs = $client->getsongs();
        
        $playlist = $add_form->getElement('playlist');
        $playlist->addMultiOptions($playlists);
        
        $song = $add_form->getElement('song');
        $song->addMultiOptions($songs);
        
        if($request->getPost('submit')){
            if($add_form->isValid($request->getPost())){
                $username = $request->getPost('username');
                $password = md5($request->getPost('password'));
                $songId = $request->getPost('song');
                $playlistId = $request->getPost('playlist');
                
                echo "<p style='float:left;'>" . $client->addsong($username, $password, $playlistId, $songId) . "</p>";
                $add_form->reset();
            }
        }

        $this->view->add_form = $add_form;
    }


}





