<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Service_playlist
 *
 * @author STANISIC
 */
class Service_playlist{
    /**
     *
     * @var array
     */
    var $playlists_model;
    /**
     *
     * @var array
     */
    var $songs_model;
    /**
     *
     * @var array
     */
    var $users_model;
    public function __construct(){
        $this->playlists_model = new Application_Model_Playlists();
        $this->songs_model = new Application_Model_Songs();
        $this->users_model = new Application_Model_Users();
    }
    /**
     * 
     * @return array
     */
    public function getplaylist(){
        $playlists = $this->playlists_model->getAll();
        
        return $playlists;
    }
    /**
     * 
     * @param string $id
     * @return array
     */
    public function getsong($id){
        $songs = $this->songs_model->getPlaylistSong($id);
        return $songs;
    }
    /**
     * 
     * @return string
     */
    public function addsong($username, $password, $playlistId, $songId){
        $select = $this->users_model->autorization($username, $password);
        
        if($select){
            $insert = $this->playlists_model->addSong($playlistId,$songId);
        
            if($insert){
                return "Song success added in playlist";
            }
        }else{
            return "Wrong username or password";
        }
        
    }
    /**
     * 
     * @return array
     */
    public function getplaylists(){
        $playlists = $this->playlists_model->getAll();
        
        $list_playlists = array();
        foreach($playlists as $p){
            $list_playlists[$p['playlistId']] = $p['playlistTitle'];
        }
        
        return $list_playlists;
    }
    /**
     * 
     * @return array
     */
    public function getsongs(){
        $songs = $this->songs_model->getAllSongs();
        
        $list_songs = array();
        foreach($songs as $s){
            $list_songs[$s['songId']] = $s['songTitle'];
        }
        
        return $list_songs;
    }
}