<?php
class Dbh{
    protected function connect()
    {
        try{
            $hostname = "localhost";
            $username = "root";
            $password = "";
            $dbname = "zuriphp";
            $conn = new mysqli($hostname,$username,$password,$dbname);
            return $conn;
        }
        catch(Exception $e){
            echo $ex->getmessage();
        }
    }
}
?>


