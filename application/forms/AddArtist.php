<?php

class Application_Form_AddArtist extends Zend_Form
{

    public function init()
    {
        $view = Zend_Layout::getMvcInstance()->getView();

        $category_model = new Application_Model_Categories();
        $categories = $category_model->getAdmin();
        
        $categories_list = array();
        
        foreach($categories as $category){
           $categories_list[$category['categoryId']] = $category['categoryTitle'];
        }
        
        $this->setAction($view->url(array('controller' => 'Administration', 'action' => 'artists'), null, true));
        $this->setMethod('post');
        $this->setAttrib('id', 'form1');
         $this->setEnctype('multipart/form-data');
        
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
                ->setAttrib('class', 'text')
                ->setRequired(TRUE)
                ->addValidator('Alpha', TRUE, array('allowWhiteSpace' => TRUE))
                ->addValidator('StringLength', TRUE, array('min' => 3, 'max' => 50))
                ->addValidator('Db_NoRecordExists', TRUE, array('artists','artistName'));
        
        $category = new Zend_Form_Element_Select('category');
        $category->setLabel('Category')
                ->setAttrib('class', 'text')
                ->setRequired(TRUE)
                ->addMultiOption('0', 'Choose')
                ->addMultiOptions($categories_list)
                ->addValidator('GreaterThan', TRUE, array('min' => '0'))->addErrorMessage('Choose category');
        
        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Image')
                ->setAttrib('class', 'image')
                ->setRequired(TRUE)
                ->addValidator('Extension', TRUE, array('jpg','jpeg','png','gif'));
        
        $about = new Zend_Form_Element_Textarea('about');
        $about->setLabel('About')
                ->setRequired(TRUE)
                ->addValidator('StringLength', TRUE, array('min' => 5, 'max' => 200));
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Add')
                ->setAttrib('id', 'button');
        
        $this->addElements(array($name, $category, $about, $image, $submit));
    }


}

