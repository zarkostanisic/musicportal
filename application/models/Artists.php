<?php

class Application_Model_Artists
{
    var $db;
    
    public function __construct(){
        $this->db = Zend_Db_Table::getDefaultAdapter();
    }
    public function getAll($category_id){
        $db = Zend_Db_Table::getDefaultAdapter();
        
        $all = $db->select()
                ->from('artists')
                ->where('categoryId=' . $category_id . ' AND status=1')
                ->query();
        
        $artists = $all->fetchAll();
        
        return $artists;
        
        
    }
    
    public function getAdmin(){
        $db = Zend_Db_Table::getDefaultAdapter();
        
        $all = $db->select()
                ->from('artists')
                ->query();
        
        $artists = $all->fetchAll();
        
        return $artists;
        
        
    }
    
    public function getName($artist_id){
        $select = $this->db->select()
                ->from('artists','artistName')
                ->where('artistId=' . $artist_id)
                ->query();
        $artist_title = $select->fetch();
        
        return $artist_title['artistName'];
    }
    
    public function addArtist($name, $category, $about, $newName){
        $data = array(
            'artistName' => $name,
            'categoryId' => $category,
            'artistAbout' => $about,
            'artistImage' => $newName,
            'status' => 1
        );
        
        $insert = $this->db->insert('artists', $data);
        
        if($insert){
            return TRUE;
        }
    }
    
    public function allow($artistId){
        $data = array(
            'status' => 1
        );
        
        $this->db->update('artists', $data, 'artistId=' . $artistId);
    }
    
    public function block($artistId){
        $data = array(
            'status' => 0
        );
        
        $this->db->update('artists', $data, 'artistId=' . $artistId);
    }
    
    public function getPopular(){
        $select = $this->db->select()
                ->from('artists')
                ->where('status=1')
                ->order('downloaded DESC')
                ->limit(1)
                ->query();
        
        $popular = $select->fetchAll();
        
        return $popular;
    }
    
    public function getOne($id){
        $select = $this->db->select()
                ->from('artists')
                ->where('artistId=' . $id)
                ->query();
                
        $artist = $select->fetch();
        
        return $artist;
    }
    
    public function edit($id, $name, $about){
        $data = array(
            'artistName' => $name,
            'artistAbout' => $about
        );
        
        $this->db->update('artists', $data, 'artistId=' . $id);
    }

}

