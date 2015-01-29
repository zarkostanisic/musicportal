<?php

class Application_Form_EditCategory extends Zend_Form
{

    public function init()
    {
        $view = Zend_Layout::getMvcInstance()->getView();

        $this->setAction($view->url(array('controller' => 'Administration', 'action' => 'editcategory'), null, true))->setMethod('post');
        $this->setAttrib('id', 'form1');
        
        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Title')
                ->setAttrib('class', 'text')
                ->setRequired(TRUE)
                ->addValidator('NotEmpty', TRUE)
                ->addValidator('StringLength', TRUE, array('min' => 3, 'max' => '25'))
                ->addValidator('Db_NoRecordExists', TRUE, array('categories', 'categoryTitle'));
        
        $hidden = new Zend_Form_Element_Hidden('categoryId');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Edit')
                ->setAttrib('id', 'button');
        
        $this->addElements(array($title, $hidden, $submit));
    }


}

