<?php
session_start();
$cleanPost = filter_input_array(INPUT_POST);
include './functions.php';

if($_SESSION['token']=== $cleanPost['token']){
        // appel de la fonction delete
        $nbDeleted = DeleteSupplier($cleanPost['idfournisseurs']);
        if ($nbDeleted>0){
            $msg="fournisseur supprimé";
            // si l'article supprimé était celui en cours, on null l'idproduits de l'article en cours
            if ($cleanPost['idfournisseurs']==$_SESSION['idFrnEnCours']){
                $_SESSION['idFrnEnCours']=NULL;
           } 
        }else{
            $msg="Problème lors de la suppression";
        }   

}else{ // cas d'appel non reconnu
    $msg='Tentative d\'attaque !! ';
}

$_SESSION['msg']= $msg;
header('Location: ../index.php'); 
?>