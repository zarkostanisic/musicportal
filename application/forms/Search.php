<?php

class Application_Form_Search extends Zend_Form
{

    public function init()
    {
        $view = Zend_Layout::getMvcInstance()->getView();

        /* Form Elements & Other Definitions Here ... */
        $this->setAction($view->url(array('controller' => 'Search', 'action' => 'index'), null, true));
        $this->setMethod('get');
        $this->setAttrib('id', 'subscribe');
        
        $where = new Zend_Form_Element_Text('where');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'search');
        $submit->setLabel('');
        
        $this->addElements(array($where, $submit));
        
        $this->setElementDecorators(array(
            'ViewHelper',
            /*'Errors',*/
            array('HtmlTag', array('tag' => 'div'))
            
        ));
        
        $submit->setDecorators(array(
                    'ViewHelper',
                    array(
                        array('row'=>'HtmlTag'),array('tag'=>'span')
                    )
                ));
        
        $this->setDecorators(array(
            'FormElements', array('HtmlTag', array('tag' => 'fieldset')), 'Form'
        ));
    }


}

