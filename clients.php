<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>

    <?php 
    require_once("linkai.php"); 
    ?>

</head>

<body>

<?php
// jeigu nera prisijungta- automaiskai nukreipiama i prisijungimo psl
    if (!isset($_COOKIE["prisijungta"])) { 
        header ("Location:index.php"); 
    // jeigu yra prisijungta- rodo: 
    } else {
        echo "Sveikas prisijunges prie savo paskyros"; 
        echo "<form action='clients.php' method='get'>"; 
        echo "<button class='btn btn-primary' type='submit' name='logout'>Logout</button>"; 
        echo "</form>"; 

        // jeigu paspausta logout- sunaikina cookies ir nukreipia i prisijungimo forma
        if (isset($_GET["logout"])) {
                setcookie("logout", "", time() -3600, "/" ); 
                header("Location:index.php"); 
            }
    }
    ?> 

</body>
</html>

