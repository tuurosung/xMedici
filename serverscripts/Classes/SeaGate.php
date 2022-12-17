<?php
class SeaGate{

    public $str='';

    function __construct(){
        
    }

    function Clean($str){
        $str = trim($str);
        $str = htmlspecialchars($str);

        return $str;
    }
}

?>