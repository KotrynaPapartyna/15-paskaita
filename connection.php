<?php 


$duomenu_bazes_serveris = "localhost";
$duomenu_bazes_slapyvardis = "root";
$duomenu_bazes_slaptazodis = "";
$duomenu_bazes_pavadinimas = "klientu_valdymo_sistema";


$conn = mysqli_connect($duomenu_bazes_serveris,$duomenu_bazes_slapyvardis,$duomenu_bazes_slaptazodis,$duomenu_bazes_pavadinimas);

if(!$conn) {
    die("Nepavyko prisijungti prie duomenu bazes". mysqli_connect_error());

//} else {
    //echo "prisijungimas ivyko sekmingai"; 
}

?>