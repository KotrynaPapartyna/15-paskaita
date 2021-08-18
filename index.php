<?php 
    require_once("connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

<?php require_once("linkai.php");?>
    
    <style>
        h1 {
            text-align: center;
        }

        .container {
            position:absolute;
            top:50%;
            left:50%;
            transform: translateY(-50%) translateX(-50%);
        }
    </style>
</head>
<body>

    <?php
    //Klientu valdymas + vartotoju valdymas
    // 08-16 dienos irasas 
    //1. Prisijungimas naudojant duomenu baze
    // Lenteles su kazkokiais tai testiniais duomenimis
    if(isset($_GET["submit"])) {
        if(isset($_GET["username"]) && isset($_GET["password"]) && !empty($_GET["username"]) && !empty($_GET["password"])) {
            $username = $_GET["username"];
            $password = $_GET["password"];

            $sql = "SELECT * FROM `uzsiregistrave vartotojai` 
            WHERE slapyvardis='$username' AND slaptazodis='$password'"; //pasirinks visus duomenis kurie yra vartotoju lenteleje

            //Uzklausa grazins 1 rezultata
            //Jeigu neteisinga, sita uzklausa mums grazins 0/false

            $result = $conn->query($sql); // yra vykdoma uzklasa is DB
            // siuo atveju rezult yra objektas su eilutemis (kintamaisiais)

            // jeigu eiluteje isvedami duomenis- 1 ar kitas sk- vadinasi viskas ok, klientas egzistuoja
            // jeigu num_rows grazintu 0- vadinasi duomenys suvesti neteisingai arba tokio kliento nera
            if($result->num_rows == 1) {
                $user_info=mysqli_fetch_array($result);
                $cookie_array=array(
                        $user_info["ID"],
                        $user_info["slapyvardis"],
                        $user_info["vardas"],
                        $user_info["teises_id"],
                    ); 
                // paverciam masyva i teksta, gal galetume nustatyti cookies
                $cookie_array =implode("|", $cookie_array);
                setcookie("prisijungta", $cookie_array, time() +3600, "/" ); 
                    //is gauto rezultato mes turetume pasiimti ID.uztenka ID nes jis nekinta
                    //duomenis mes turetume isaugoti i COOKies.
                    header("Location: clients.php");

                } else {
                    $message = "Neteisingi prisijungimo duomenys";
                }
                // $result = mysqli_query($conn, $sql);

                // var_dump($result);

                //mes turime i serveri nusiusti kazkokia tai uzklausa
                //ivykdyti/neivykdyti prisijungimo
                //ir uzdaryti prisijungima prie duomenu bazes

            } else {
                $message = "Laukeliai yra tušti arba duomenys yra neteisingi";
            }
        }    

    ?>

    <?php if (!isset($_COOKIE["prisijungta"])) { ?>
    <div class="container">

        <h1>Klientų valdymo sistema</h1>

        <form action="index.php" method="get">
            <div class="form-group">
                <label for="username">Username</label>
                <input class="form-control" type="text" name="username" />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control" type="password" name="password" />
            </div>

            <a href="register.php">Register here</a><br>
            <button class="btn btn-primary" type="submit" name="submit">Log In</button>
        </form>

            <?php if(isset($message)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php } ?>
    </div>


    <?php } else { // jeigu yra prisijungta- t.y. issaugotas cookies, nukreipiama i clients psl
        header ("Location:clients.php");
    } ?>


    
</body>
</html>