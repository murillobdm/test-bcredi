<?php

namespace App\Models;

class Node
{
    public function __construct($name='root', $path='', $type='', $content=array())
    {
        $this->name = $name;
        $this->path = $path;
        $this->type = $type;
        $this->content = $content;
    }
}