<?php
   session_start();

    $servidor = "localhost";
    $basedatos = "intercambio";
    $usuario = "postgres";
    $password = "admin";

   try{
      $conexion = pg_connect("host=$servidor dbname=$basedatos user=$usuario password=$password");
   }catch(Exception $ex){
      echo $ex->getMessage();
   }

    
?>