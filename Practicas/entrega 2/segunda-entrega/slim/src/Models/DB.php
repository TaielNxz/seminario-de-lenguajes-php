<?php
    namespace App\Models;

    class DB{
        public function conectar(){
            try {
                $dbn = "mysql:host=localhost;dbname=entrega2";
                $user = "root";
                $password = "";

                // $dbn = "mysql:host=db;dbname=php";
                // $user = "php";
                // $password = "php";
        
                $dbh = new \PDO($dbn, $user, $password);
                return $dbh;
            }
            catch (PDOException $e){
                echo "ERROR\n";
                echo $e->getMessage();
            }
        }
    }
?>