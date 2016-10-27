<?php
$salt='';  //A modifier
$Host = '';
$User = '';
$Pass = '';
$Db = ''; 
try
{
    $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
    $bdd = new PDO('mysql:host='.$Host.';dbname='.$Db.';charset=utf8', ''.$User.'', ''.$Pass.'', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
     
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}


$reqConfiguration = $bdd->query('SELECT * FROM configuration WHERE ID = 1');
$configuration = $reqConfiguration->fetch();
$reqConfiguration->closeCursor();