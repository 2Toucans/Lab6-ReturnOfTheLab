<?php

class Tasks extends MY_Model {
    public function __construct() {
        parent::_construct('tasks', 'id');
    }
}