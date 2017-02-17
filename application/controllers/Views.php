<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Views extends Application
{
    public function index()
    {
        $this->data['pagetitle'] = 'Ordered TODO List';
        $tasks = $this->tasks->all();
        $this->data['content'] = 'Ok';
        $this->data['leftside'] = 'by_priority';
        $this->data['rightside'] = 'by_category';
        
        $this->render('template_secondary');
    }
}