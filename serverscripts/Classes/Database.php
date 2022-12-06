<?php

    class DataBase{
        function __construct(){
            $this->db = mysqli_connect('localhost', 'root', '@Tsung3#', 'xMedici');
            $this->mysqli = new mysqli('localhost', 'root', '@Tsung3#', 'xMedici');
        }
    }
    

?>