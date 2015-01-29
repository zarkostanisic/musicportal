<?php

class Application_Model_Users
{
    var $db;
    
    public function __construct() {
        $this->db = Zend_Db_Table::getDefaultAdapter();
    }
    
    public function register($username, $password, $email, $city){
        $data = array(
            'username' => $username,
            'password' => md5($password),
            'email' => $email,
            'cityId' => $city,
            'roleId' => 2
        );
        
        $insert = $this->db->insert('users', $data);
        
        if($insert){
            return TRUE;
        }
    }
    
    public function getAdmin(){
        $select = $this->db->select()
                ->from(array('u' => 'users'))
                ->join(array('r' => 'roles'), 'u.roleId=r.roleId')
                ->query();
        $users = $select->fetchAll();
        
        return $users;
    }
    
    public function allow($id){
        $data = array(
            'status' => 1
        );
        
        $this->db->update('users', $data, 'userId=' . $id);
    }
    
    public function block($id){
        $data = array(
            'status' => 0
        );
        
        $this->db->update('users', $data, 'userId=' . $id);
    }
    
    public function getOne($id){
        $select = $this->db->select()
                ->from('users')
                ->where('userId=' . $id)
                ->query();
        
        $user = $select->fetch();
        
        return $user;
    }
    
    public function edit($id, $email, $role){
        $data = array(
            'email' => $email,
            'roleId' => $role
        );
        
        $this->db->update('users', $data, 'userId=' . $id);
    }
    
    public function editPassword($id, $password){
        $data = array(
            'password' => md5($password)
        );
        
        $this->db->update('users', $data, 'userId=' . $id);
    }
    
    public function login($username, $password){
        $select = $this->db->select()
                ->from(array('u' => 'users'))
                ->join(array('r' => 'roles'), 'u.roleId=r.roleId')
                ->where('username="' . $username . '" AND password="' . $password . '"')
                ->query();
        $user = $select->fetch();
        
        $role = $user['roleTitle'];
        
        $session = new Zend_Auth_Storage_Session();
        $session->write($user);
        
        return $role;
    }
    
    public function autorization($username, $password){
        $select = $this->db->select()
                ->from('users')
                ->where('username="' . $username . '" AND password="' . $password . '"')
                ->query();
        
        if($select->rowCount() == 1){
            return TRUE;
        }else{
            return FALSE;
        }
    }

}

