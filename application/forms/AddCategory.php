<?php

class Application_Form_AddCategory extends Zend_Form
{

    public function init()
    {
        $view = Zend_Layout::getMvcInstance()->getView();

        $this->setAction($view->url(array('controller' => 'Administration', 'action' => 'index'), null, true))->setMethod('post');
        $this->setAttrib('id', 'form1');
        
        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Title')
                ->setAttrib('class', 'text')
                ->setRequired(TRUE)
                ->addValidator('NotEmpty', TRUE)
                ->addValidator('StringLength', TRUE, array('min' => 3, 'max' => '25'))
                ->addValidator('Db_NoRecordExists', TRUE, array('categories', 'categoryTitle'));
        
        $add = new Zend_Form_Element_Submit('add');
        $add->setLabel('Add')
                ->setAttrib('id', 'button');
        
        $this->addElements(array($title, $add));
    }


}

