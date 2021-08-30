<?php require_once("../connection.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos</title>
</head>
<body>

<form action="datos.php" method="get">
    <input type="text" name="username" placeholder="Enter your username">
    <button type="submit" name="submit">Submit</button> 
</form>

<?php
//getdate ir date funkciju testavimas
    $today = getdate(); //masyvas - asociatyvus
    var_dump($today);

    echo $today["year"]."-".$today["mon"]."-".$today["mday"];
    echo "<br>";

    // 0 - timestamp

    //date(formatas, ?timestamp) - timestampa pavercia i reikiamo formato data
    $formatas = "Y-m-d";
    //Y - metai 2021
    // m - menesis
    // d - diena
    $formated_date = date($formatas, $today[0]);

    echo $formated_date;
/////////////////////////////////////////////////////////////////////////////////////////


//Mygtuko paspaudimu yra pagaunama sios dienos data
//data yra irasoma i duomenu baze bet irasoma prie to iraso kurio sutampa slapyvardis



if(isset($_GET["submit"])) {
    // date_default_timezone_set('Europe/Vilnius');
    // $formated_date_1 = date("Y-m-d H:i:s");
    // echo "<br>";
    // echo $formated_date_1;

    if(isset($_GET["username"]) && !empty($_GET["username"]) ) {

        $username = $_GET["username"];

        // $sql = "UPDATE `uzsiregistrave_vartotojai` SET `prisijungimo_data`='$formated_date_1' 
        // WHERE `slapyvardis`='$username'";
        $sql = "UPDATE `uzsiregistrave vartotojai` SET `prisijungimo_data`='1996-05-15'
        WHERE `slapyvardis`='$username'";


        if(mysqli_query($conn, $sql)) {
            $message =  "Prisijungimo data pakeista";
        } else {
            $message =  "Uzklausa yra netinkama";
        }

        echo $message;
    }
}



//1996-05-15

//Datu aritmetika


// Sumoketi iki 2021-09-30
// Kiek dienu iki sumokejimo?
// 2021-09-30 - 2021-08-30
$end_date = "2021-09-30"; //string
$now_date = date("Y-m-d"); //string

//PHP aritmetikos su tekstine eilute atlikti negalim

//strtotime - teksta, kuris yra datos formato pavercia i sekundes
$end_date = strtotime($end_date);
$now_date = strtotime($now_date);

var_dump($end_date);
var_dump($now_date);

$seconds = $end_date + $now_date;

$minutes = intval($seconds / 60);
$hours = intval($seconds/3600);
$days = intval($seconds/(3600*24));


$time_array = array($days, $hours, $minutes);

var_dump($time_array);

//Prie sios dienos datos prideti 5 dienas;
$now_date = date("Y-m-d");
$add_days = 5*24*3600;

$now_date = strtotime($now_date);
$date_sum = $now_date + $add_days; //sekundemis

$formated_date_sum = date("Y-m-d", $date_sum );

var_dump($formated_date_sum);

//2021-08-30
//2021-09-04




?>    
</body>
</html>