<?php

class AdministrationController extends Zend_Controller_Action
{

    public $categories_model = null;

    public $artists_model = null;

    public $songs_model = null;

    public $playlists_model = null;

    public $users_model = null;

    public function preDispatch()
    {
        $this->categories_model = new Application_Model_Categories();
        $categories = $this->categories_model->getAll();
        $this->view->categories = $categories;
        
        $this->view->render('/placeholders/_left_menu.phtml');
        $this->view->render('/placeholders/_menu.phtml');
        $this->view->render('/placeholders/_footer.phtml');
        
    }

    public function init()
    {
        $session = new Zend_Auth_Storage_Session();
        $sess = $session->read();
        if(isset($sess['roleTitle'])){
            $role = $sess['roleTitle'];
            
            if($role != "admin"){
                $this->_redirect('Index/index');
            }
            $role_title = $sess['roleTitle'];
            $username_title = $sess['username'];
            $this->view->role_title = $role_title;
            $this->view->username_title = $username_title;
        }else{
            $this->_redirect('Index/index');
        }
        
        $this->view->headTitle()->prepend('Administration');
        
        $this->artists_model = new Application_Model_Artists();
        $this->songs_model = new Application_Model_Songs();
        $this->playlists_model = new Application_Model_Playlists();
        $this->users_model = new Application_Model_Users();
          
        $urls['HOME'] = $this->view->url(array('controller' => 'Index', 'action' => 'index'), null, true);
        $urls['CATEGORIES'] = $this->view->url(array('controller' => 'Administration', 'action' => 'index'), null, true);
        $urls['ARTISTS'] = $this->view->url(array('controller' => 'Administration', 'action' => 'artists'), null, true);
        $urls['SONGS'] = $this->view->url(array('controller' => 'Administration', 'action' => 'showsongs'), null, true);
        $urls['PLAYLISTS'] = $this->view->url(array('controller' => 'Administration', 'action' => 'showplaylists'), null, true);
        $urls['USERS'] = $this->view->url(array('controller' => 'Administration', 'action' => 'users'), null, true);
        $urls['WISHES'] = $this->view->url(array('controller' => 'Administration', 'action' => 'wishes'), null, true);

        
        foreach($urls as $url=>$v){
            $this->view->placeholder('menu')->append('<li><a href="' . $v . '" class="adminlink">' . $url . '</a></li>');
            $this->view->placeholder('footer')->append('<a href="' . $v . '">' . $url . '</a>');
        }
        
        $artists_model = new Application_Model_Artists();
        $popular = $artists_model->getPopular();
        $this->view->popular = $popular;
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        
        $add_category = new Application_Form_AddCategory();
        
        if($request->getPost('add')){
            if($add_category->isValid($request->getPost()) == 1){
                $title = $request->getPost('title');
                
                $insert = $this->categories_model->addCategory($title);
                
                if($insert){
                    echo "Category success added";
                    $add_category->reset();
                }
            }
        }
        
        if($request->getParam('block')){
            $categoryId = $request->getParam('block');
            
            $this->categories_model->block($categoryId);
        }
        
        if($request->getParam('allow')){
            $categoryId = $request->getParam('allow');
            
            $this->categories_model->allow($categoryId);
        }
        
        
        $categories = $this->categories_model->getAdmin();
        
        $this->view->add_category = $add_category;
        $this->view->categories = $categories;
    }

    public function artistsAction()
    {
        $add_artist = new Application_Form_AddArtist();
        
        $request = $this->getRequest();

        if($request->getPost('submit')){
            if($add_artist->isValid($request->getPost()) == 1){
                $name = $request->getPost('name');
                $category = $request->getPost('category');
                $about = $request->getPost('about');
                
                
                $upload = new Zend_File_Transfer_Adapter_Http();
                $upload->setDestination("images\\artists\\");
                $image = $upload->getFileName();
                $all = explode('.', $image);
                $ext = $all[count($all) - 1];
                
                $newName = date('d_m_Y_H_i_s') . '.' . $ext;
                
                $upload->addFilter('Rename', array('target' => $newName));
                
                if($upload->receive('image')){
                    $insert = $this->artists_model->addArtist($name, $category, $about, $newName);
                
                    if($insert){
                        echo "Artist success added";
                        $add_artist->reset();
                    }
                }
            }
        }
        
        if($request->getParam('allow')){
            $artistId = $request->getParam('allow');
            
            $this->artists_model->allow($artistId);
        }
        
        if($request->getParam('block')){
            $artistId = $request->getParam('block');
            
            $this->artists_model->block($artistId);
        }
        
        $artists = $this->artists_model->getAdmin();
        $this->view->add_artist = $add_artist;
        $this->view->artists = $artists;
    }

    public function songsAction()
    {
        $add_song = new Application_Form_AddSong();
        $request = $this->getRequest();
        
        if($request->getPost('submit')){
            if($add_song->isValid($request->getPost()) == 1){
              $title = $request->getPost('title');
              $artist = $request->getPost('artist');
              
              $upload = new Zend_File_Transfer_Adapter_Http();
              $upload->setDestination("music\\");
              $file = $upload->getFileName('file');
              $all = explode('.', $file);
              $ext = $all[count($all) - 1];
              $name = explode(' ', $title);
              
              $newName = "";
              $i = 1;
              foreach($name as $n){
                 if($i < count($name)){
                     $newName .= $n . "_";
                 }else{
                    $newName .= $n; 
                 }
                 $i++;
              }
              
              $newName .= "." . $ext;
              
              $upload->addFilter('Rename', array('target' => $newName));
              $size = $upload->getFileSize();
              
              if($upload->receive('file')){
                  $insert = $this->songs_model->addSong($title,$artist,$newName, $size);
                  
                  if($insert){
                    echo "Song success added";
                    $add_song->reset();  
                  }
              }
            }
        }
        
        $this->view->add_song = $add_song;
    }

    public function playlistsAction()
    {
        $add_playlist = new Application_Form_AddPlaylist();
        
        $this->view->add_playlist = $add_playlist;
    }

    public function addplaylistAction()
    {
        $request = $this->getRequest();
        
        $songs = $request->getPost('songs');
        $title = $request->getPost('title');
        
        $insert = $this->playlists_model->add($title, $songs);
        
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
    }

    public function showplaylistsAction()
    {
        
        $request = $this->getRequest();
        
        if($request->getParam('allow')){
            $allow = $this->getParam('allow');
            
            $this->playlists_model->allow($allow);
        }
        
        if($request->getParam('block')){
            $block = $this->getParam('block');
            
            $this->playlists_model->block($block);
        }
        
        
        $playlists = $this->playlists_model->getAdmin();
        $this->view->playlists = $playlists;
    }

    public function editplaylistAction()
    {
        $request =$this->getRequest();
        $id = $request->getParam('id');
        
        if($request->getPost('submit')){
            $id = $request->getPost('playlistId');
            $title = $request->getPost('title');
            
            $update = $this->playlists_model->edit($id, $title);
        }
        
        $playlist = $this->playlists_model->getOne($id);
        
        $edit_playlist = new Application_Form_EditPlaylist();
        
        
        $title = $edit_playlist->getElement('title');
        $title->setValue($playlist['playlistTitle']);
        $hidden = $edit_playlist->getElement('playlistId');
        $hidden->setValue($playlist['playlistId']);
                
        if($request->getParam('delete')){
            $delete = $request->getParam('delete');
            
            $this->playlists_model->deleteItem($delete);
        }
        
        $all = $this->playlists_model->getEdit($id);
        
        $this->view->playlist = $all['playlist'];
        $this->view->songs = $all['songs'];
        $this->view->id = $id;
        $this->view->edit_playlist = $edit_playlist;
    }

    public function showsongsAction()
    {
        $artists = $this->artists_model->getAdmin();
        $this->view->artists = $artists;
    }

    public function artistsongsAction()
    {
        $request = $this->getRequest();
        
        $id = $request->getParam('id');
        
        if($request->getParam('block')){
            $block = $request->getParam('block');
            
            $this->songs_model->block($block);
        }
        
        if($request->getParam('allow')){
            $allow = $request->getParam('allow');
            
            $this->songs_model->allow($allow);
        }
        
        $songs = $this->songs_model->getAdmin($id);
        $artist = $this->artists_model->getName($id);
        
        $this->view->id = $id;
        $this->view->songs = $songs;
        $this->view->artist = $artist;
    }

    public function editsongAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id');
        
        $edit_song = new Application_Form_EditSong();
        
        if($request->getPost('submit')){
            $id = $request->getPost('songId');
            if($edit_song->isValid($request->getPost())){
                $title = $request->getPost('title');
                
                $this->songs_model->edit($id, $title);
            }
        }
        
        $song = $this->songs_model->getOne($id);
        
        $title = $edit_song->getElement('title');
        $title->setValue($song['songTitle']);
        $hidden = $edit_song->getElement('songId');
        $hidden->setValue($song['songId']);
        
        $this->view->edit_song = $edit_song;
        $this->view->song = $song;
    }

    public function editartistAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id');
        
                
        $edit_artist = new Application_Form_EditArtist();
        
        if($request->getPost('submit')){
            $id = $request->getPost('artistId');
            if($edit_artist->isValid($request->getPost())){
                $name = $request->getPost('name');
                $about = $request->getPost('about');
                $this->artists_model->edit($id, $name, $about);
            }
        }
        
        $artist = $this->artists_model->getOne($id);
        
        $name = $edit_artist->getElement('name');
        $name->setValue($artist['artistName']);
        
        $about = $edit_artist->getElement('about');
        $about->setValue($artist['artistAbout']);
        
        $hidden = $edit_artist->getElement('artistId');
        $hidden->setValue($artist['artistId']);
        
        $this->view->edit_artist = $edit_artist;
        $this->view->artist = $artist;
    }

    public function editcategoryAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id');
        
        $edit_category = new Application_Form_EditCategory();
        
        if($request->getPost('submit')){
           $id = $request->getPost('categoryId');
           if($edit_category->isValid($request->getPost())){
               $title = $request->getPost('title');
               
               $this->categories_model->edit($id, $title);
           }
        }
        
        $category = $this->categories_model->getOne($id);
        
        $title = $edit_category->getElement('title');
        $title->setValue($category['categoryTitle']);
        
        $hidden = $edit_category->getElement('categoryId');
        $hidden->setValue($category['categoryId']);
                
        $this->view->edit_category = $edit_category;
        $this->view->category = $category;
    }

    public function usersAction()
    {
        $request = $this->getRequest();
        
        if($request->getParam('allow')){
            $allow = $request->getParam('allow');
            $this->users_model->allow($allow);
        }
        
        if($request->getParam('block')){
            $block = $request->getParam('block');
            $this->users_model->block($block);
        }
        
        $users = $this->users_model->getAdmin();
        
        $this->view->users = $users;
    }

    public function edituserAction()
    {
        $request = $this->getRequest();
        
        $id = $request->getParam('id');
        
        $edit_user = new Application_Form_EditUser();
        $edit_password = new Application_Form_EditPassword();
        
        if($request->getPost('submit')){
            $id = $request->getPost('userId');
            if($edit_user->isValid($request->getPost())){
                $email = $request->getPost('email');
                $role = $request->getPost('role');
                
                $this->users_model->edit($id, $email, $role);
            }
        }
        
        if($request->getPost('passwordsubmit')){
            $id = $request->getPost('userId');
            if($edit_password->isValid($request->getPost())){
                $password = $request->getPost('password');
                
                $this->users_model->editPassword($id, $password);
            }
        }
        
        $user = $this->users_model->getOne($id);
        
        $email = $edit_user->getElement('email');
        $email->setValue($user['email']);
        
        $hidden = $edit_user->getElement('userId');
        $hidden->setValue($user['userId']);
        
        $hiddenpassword = $edit_password->getElement('userId');
        $hiddenpassword->setValue($user['userId']);
        
        $this->view->edit_user = $edit_user;
        $this->view->edit_password = $edit_password;
        $this->view->user = $user;
    }

    public function wishesAction()
    {
        $request = $this->getRequest();
        
        if($request->getParam('allow')){
            $allow = $request->getParam('allow');
            
            $this->songs_model->allowWish($allow);
        }
        
        if($request->getParam('block')){
            $block = $request->getParam('block');
            
            $this->songs_model->blockWish($block);
        }
        
        $wishes = $this->songs_model->getWishes();
        
        $this->view->wishes = $wishes;
    }


}










