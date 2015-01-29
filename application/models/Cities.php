<?php

class Application_Model_Cities
{
    var $db;
    
    public function __construct() {
        $this->db = Zend_Db_Table::getDefaultAdapter();
    }
    
    public function getList(){
        $select = $this->db->select()
                ->from('cities')
                ->query();
        $cities = $select->fetchAll();
        
        return $cities;
    }

}

