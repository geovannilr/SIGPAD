<?php
class MY_URI extends CI_URI {

    function __construct()
    {
        parent::__construct();
    }
    
    function set_segment($n,$value)
    {
      $this->segments[$n] = $value;
      return $this;            
    }        
}