<?php
$bd = 'mysql:dbname = exo1 ; host=localhost';
$user = 'root';
$password ='';

try{
    $bdb = new PDO($bd, $user , $password);
    $bdb->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e){
    echo "Echec de connexion:".$e->getMessage();
}
?>

