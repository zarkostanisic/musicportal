<?php

class Application_Model_Categories
{
    var $db;
    
    public function __construct(){
        $this->db = Zend_Db_Table::getDefaultAdapter();
    }
    
    public function getAll(){
        
        $select = $this->db->select()
                ->from('categories')
                ->where('status=1')
                ->order('categoryTitle')
                ->query();
        $categories = $select->fetchAll();
        
        return $categories;
    }
    
    public function getAdmin(){
        
        $select = $this->db->select()
                ->from('categories')
                ->order('categoryTitle')
                ->query();
        $categories = $select->fetchAll();
        
        return $categories;
    }
    
    public function getTitle($category_id){
        $select = $this->db->select('categoryTitle')->from('categories')->where('categoryId=' . $category_id)->query();
        $category_title = $select->fetch();
        
        return $category_title['categoryTitle'];
    }
    
    public function addCategory($title){
        $data = array(
            'categoryTitle' => $title,
            'status' => 1
        );
        
        $insert = $this->db->insert('categories', $data);
        
        if($insert){
            return TRUE;
        }
    }
    
    public function block($categoryId){
        $data = array(
            'status' => 0
        );
        $this->db->update('categories', $data, 'categoryId=' . $categoryId);
    }
    
    public function allow($categoryId){
        $data = array(
            'status' => 1
        );
        $this->db->update('categories', $data, 'categoryId=' . $categoryId);
    }
    
    public function getOne($id){
        $select = $this->db->select()
                ->from('categories')
                ->where('categoryId=' . $id)
                ->query();
        
        $category = $select->fetch();
        
        return $category;
    }
    
    public function edit($id, $title){
        $data = array(
            'categoryTitle' => $title
        );
        
        $this->db->update('categories', $data, 'categoryId=' . $id);
    }

}

