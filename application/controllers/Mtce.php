<?php

class Mtce extends Application
{
    private $items_per_page = 10;
    public function index()
    {
        $this->page(1);
    }
        
    private function show_page($tasks)
    {
        $role = $this->session->userdata('userrole');
        $this->data['pagetitle'] = 'TODO List Maintenance ('. $role . ')';
        // substitute the status name
        foreach ($tasks as $task)
            if (!empty($task->status))
                $task->status = $this->statuses->get($task->status)->name;

        // build the task presentation output
        $result = '';   // start with an empty array        
        foreach ($tasks as $task)
        {
            if ($role == ROLE_OWNER)
                $result .= $this->parser->parse('oneitemx', (array) $task, true);
            else
                $result .= $this->parser->parse('oneitem',(array)$task,true);
        }
        // and then pass them on
        $this->data['display_tasks'] = $result;
        $this->data['pagebody'] = 'itemlist';
        $this->render();
    }
    
    function page($num = 1)
    {
        $records = $this->tasks->all(); // get all the tasks
        $tasks = array(); // start with an empty extract

        // use a foreach loop, because the record indices may not be sequential
        $index = 0; // where are we in the tasks list
        $count = 0; // how many items have we added to the extract
        $start = ($num - 1) * $this->items_per_page;
        foreach($records as $task) {
            if ($index++ >= $start) {
                $tasks[] = $task;
                $count++;
            }
            if ($count >= $this->items_per_page) break;
        }
        $this->data['pagination'] = $this->pagenav($num);
        
        $role = $this->session->userdata('userrole');
        if ($role == ROLE_OWNER) 
            $this->data['pagination'] .= $this->parser->parse('itemadd',[], true);
        
        $this->show_page($tasks);
    }
    private function pagenav($num) {
        $lastpage = ceil($this->tasks->size() / $this->items_per_page);
        $parms = array(
            'first' => 1,
            'previous' => (max($num-1,1)),
            'next' => min($num+1,$lastpage),
            'last' => $lastpage
        );
        return $this->parser->parse('itemnav',$parms,true);
    }
    // Initiate adding a new task
    public function add()
    {
        $task = $this->tasks->create();
        $this->session->set_userdata('task', $task);
        $this->showit();
    }
    
        // initiate editing of a task
    public function edit($id = null)
    {
        if ($id == null)
            redirect('/mtce');
        $task = $this->tasks->get($id);
        $this->session->set_userdata('task', $task);
        $this->showit();
    }
}