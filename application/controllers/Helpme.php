<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Helpme extends Application
{
    public function index()
    {
        $this->data['pagetitle'] = 'Help Wanted!';
        $stuff = file_get_contents('../data/jobs.md');
        $this->data['content'] = $this->parsedown->parse($stuff);
        $this->render();
    }
}