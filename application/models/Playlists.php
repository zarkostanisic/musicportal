<?php

class Application_Model_Playlists
{
    var $db;
    public function __construct(){
       $this->db = Zend_Db_Table::getDefaultAdapter(); 
    }
    
    public function getAll(){
        $select = $this->db->select()
                ->from('playlists')
                ->where('status=1')
                ->query();
        
        $playlists = $select->fetchAll();
        
        return $playlists;
    }

    public function getLastPlayList(){
        $selectlast = $this->db->select()
                ->from('playlists',array('playlistId', 'playlistTitle'))
                ->where('status=1')
                ->order('playlistId DESC')
                ->limit(1)
                ->query();
        
        $last = $selectlast->fetch();
        
        $id = $last['playlistId'];
        
        
        $select = $this->db->select()
                ->from(array('p' => 'playlistitems'))
                ->join(array('s' => 'songs'), 'p.songId=s.songId')
                ->where('p.playlistId=' . $id . ' AND s.status=1')
                ->query();
        
        $songs = $select->fetchAll();
        
        return array('last' => $last, 'songs' => $songs);
    }
    
    public function getEdit($playlistId){
        $selectplaylist = $this->db->select()
                ->from('playlists',array('playlistId', 'playlistTitle'))
                ->where('playlistId=' . $playlistId)
                ->query();

        $playlist = $selectplaylist->fetch();


        $select = $this->db->select()
                ->from(array('p' => 'playlistitems'))
                ->join(array('s' => 'songs'), 'p.songId=s.songId')
                ->where('p.playlistId=' . $playlistId)
                ->query();

        $songs = $select->fetchAll();

        return array('playlist' => $playlist, 'songs' => $songs);
    }
    
    public function getAdmin(){
        $select = $this->db->select()
                ->from('playlists')
                ->query();
        
        $playlists = $select->fetchAll();
        
        return $playlists;
    }

    public function add($title, $songs){
        $data = array(
            'playlistTitle' => $title,
            'date' => time(),
            'status' => 1
        );
        $this->db->insert('playlists', $data);

        $id = $this->db->lastInsertId();


        foreach($songs as $song){
            $songsdata = array(
                'songId' => $song,
                'playlistId' => $id
            );

            $this->db->insert('playlistitems', $songsdata);
        }
    }
    
    public function allow($playlistId){
        $data = array(
            'status' => 1
        );
        
        $this->db->update('playlists', $data, 'playlistId=' . $playlistId);
    }
    
    public function block($playlistId){
        $data = array(
            'status' => 0
        );
        
        $this->db->update('playlists', $data, 'playlistId=' . $playlistId);
    }
    
    public function deleteItem($id){
        $this->db->delete('playlistitems', 'itemId=' . $id);
    }
    
    public function getOne($playlistId){
        $select = $this->db->select()
                ->from('playlists')
                ->where('playlistId=' . $playlistId)
                ->query();
        
        $artist = $select->fetch();
        
        return $artist;
    }
    
    public function edit($id, $title){
        $data = array(
            'playlistTitle' => $title
        );
        
        $update = $this->db->update('playlists', $data, 'playlistId=' . $id);
        
        if($update){
            return TRUE;
        }
    }
    
    public function addSong($playlistId, $songId){
        $data = array(
            'playlistId' => $playlistId,
            'songId' => $songId
        );
        
        $insert = $this->db->insert('playlistitems', $data);
        
        if($insert){
            return TRUE;
        }
    }
}

