<?php 
    function connect()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $db_name = "job_portal_project";
        $conn =new mysqli($servername, $username, $password, $db_name);
        return $conn;
    }



?>