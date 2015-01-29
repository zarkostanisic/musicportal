<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initPlaceholders(){
        $this->bootstrap('view');
        
        $view = $this->getResource('view');
        $view->baseUrl = 'http://127.0.0.1/musicportal/';

        $view->headTitle('Music portal')->setSeparator(' :: ');
        $view->headLink()->appendStylesheet($view->baseUrl . 'css/style.css');
        $view->headLink()->appendStylesheet($view->baseUrl . 'css/layout.css');
        $view->headLink()->appendStylesheet($view->baseUrl . 'css/layout.css');
        $view->headScript()->appendFile($view->baseUrl . 'js/jquery-1.10.2.min.js');
        $view->headScript()->appendFile($view->baseUrl . 'js/cufon-yui.js');
        $view->headScript()->appendFile($view->baseUrl . 'js/cufon-replace.js');
        $view->headScript()->appendFile($view->baseUrl . 'js/Josefin_Sans_600.font.js');
        $view->headScript()->appendFile($view->baseUrl . 'js/Lobster_400.font.js');
        $view->headScript()->appendFile($view->baseUrl . 'js/sprites.js');
    }
    
    protected function _initMenu(){
        $this->bootstrap('view');
        $view = $this->getResource('view');
        
        $view->placeholder('menu')->setPrefix('<ul>')->setPostfix('</ul>');
    }
    
    protected function _initFooter(){
        $this->bootstrap('view');
        
        $view = $this->getResource('view');
        
        $view->placeholder('footer')->setPrefix('<span>')->setPostfix('</span>');
    }

}

