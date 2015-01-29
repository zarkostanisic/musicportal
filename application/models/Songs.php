<?php

class Application_Model_Songs
{
    var $db;
    
    public function __construct(){
       $this->db = Zend_Db_Table::getDefaultAdapter(); 
    }

    public function getAll($artist_id){
       $select = $this->db->select()
               ->from('songs')
               ->where('artistId=' . $artist_id . ' AND status=1')
               ->query();
       $songs = $select->fetchAll();
       
       return $songs;
    }
    
    public function getAdmin($artist_id){
       $select = $this->db->select()
               ->from('songs')
               ->where('artistId=' . $artist_id)
               ->query();
       $songs = $select->fetchAll();
       
       return $songs;
    }
    
    public function getAllSongs(){
       $select = $this->db->select()
               ->from('songs')
               ->query();
       $songs = $select->fetchAll();
       
       return $songs;
    }
    
    public function getNumber(){
        $select = $this->db->select()
                ->from('songs', array('cnt' => 'COUNT(*)','artistId'))
                ->where('status=1')
                ->group('artistId')
                ->query();
        $song_nums = $select->fetchAll();
        
        return $song_nums;
    }
    
    public function search($songTitle){
        $select = $this->db->select()
                ->from('songs')
                ->where('songTitle LIKE "%' . $songTitle . '%"')
                ->query();
        $search = $select->fetchAll();
        
        return $search;    
    }
    
    public function addSong($title,$artist,$newName, $size){
       $data = array(
           'artistId' => $artist,
           'songTitle' => $title,
           'url' => $newName,
           'date' => time(),
           'size' => $size,
           'status' => 1
       );
       
       $insert = $this->db->insert('songs', $data);
       
       if($insert){
           return TRUE;
       }
    }
    
    public function block($songId){
        $data = array(
            'status' => 0
        );
        
        $this->db->update('songs', $data, 'songId=' . $songId);
    }
    
    public function allow($songId){
        $data = array(
            'status' => 1
        );
        
        $this->db->update('songs', $data, 'songId=' . $songId);
    }
    
    public function getOne($songId){
        $select = $this->db->select()
                ->from('songs')
                ->where('songId=' . $songId)
                ->query();
        
        $song = $select->fetch();
        
        return $song;
    }
    
    public function edit($id, $title){
        $data = array(
            'songTitle' => $title
        );
        
        $this->db->update('songs', $data, 'songId=' . $id);
    }
    
    public function getPlaylistSong($id){
        $select = $this->db->select()
                ->from(array('s' => 'songs'))
                ->join(array('p' => 'playlistitems'), 's.songId=p.songId')
                ->where('p.playlistId=' . $id)
                ->query();
        $songs = $select->fetchAll();
        
        return $songs;
    }
    
    public function getWishes(){
        $select = $this->db->select()
                ->from(array('w' => 'wishes'))
                ->join(array('u' => 'users'), 'w.userId=u.userId')
                ->order('wishStatus')
                ->query();
        
        $wishes = $select->fetchAll();
        
        return $wishes;
    }
    
    public function allowWish($id){
        $data = array(
            'wishStatus' => 1
        );
        
        $this->db->update('wishes', $data, 'wishId=' . $id);
    }
    
    public function blockWish($id){
        $data = array(
            'wishStatus' => 0
        );
        
        $this->db->update('wishes', $data, 'wishId=' . $id);
    }
}

