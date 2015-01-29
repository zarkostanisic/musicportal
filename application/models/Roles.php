<?php

class Application_Model_Roles
{
    var $db;
    
    public function __construct(){
        $this->db = Zend_Db_Table::getDefaultAdapter();
    }
    
    public function getAll(){
        $select = $this->db->select()
                ->from('roles')
                ->query();
        
        $roles = $select->fetchAll();
        
        return $roles;
    }

}

