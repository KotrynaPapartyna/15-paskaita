<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klientu generavimas</title>
</head>
<body>
    <form action="generateClients.php" method="get">
        <button type="submit" name="submit">Sukurti klientus</button>
    </form>
    <?php 

// Sugeneruos 200 klientu i duomenu baze

    require_once("connection.php");

    if(isset($_GET["submit"])) {
        for ($i=0; $i<200; $i++) {

            $vardas = "Vardas".$i;
            $pavarde = "Pavarde".$i;
            $teises_id = rand(0, 5);

            $sql = "INSERT INTO `klientai`(`Vardas`, `Pavarde`, `Teises_ID`) 
            VALUES ('$vardas','$pavarde','$teises_id')";

            if(mysqli_query($conn, $sql)) {
                echo "Vartotojas sukurtas sekmingai";
                echo "<br>";
            } else {
                echo "Kazkas ivyko negerai";
                echo "<br>";
            }
        }
    }

//INSERT komanda i klientai lentele 200 kartu


?>
</body>
</html>