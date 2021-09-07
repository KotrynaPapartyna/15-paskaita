<!DOCTYPE html>
<html lang="lt">
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

            $vardas = "vardas".$i;
            $pavarde = "pavarde".$i;
            $teises_id = rand(0, 5);
            $aprasymas="aprasymas"; 
            $imones_id= rand(0,10); 


        $sql = "INSERT INTO `klientai`(`vardas`, `pavarde`, `teises_id`, 
        `aprasymas`, `imones_id`) 
            VALUES ('$vardas','$pavarde','$teises_id','$aprasymas', '$imones_id')";

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