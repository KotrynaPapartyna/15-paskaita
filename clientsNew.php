<?php 
    require_once("connection.php");
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Clients</title>

    <?php require_once("linkai.php"); ?>
    
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

        .hide {
            display:none;
        }
    </style>

</head>
<body>

<?php 
// veikia 
if (isset($_COOKIE["prisijungta"])) {
    header("Location:index.php"); 
}

if(isset($_GET["submit"])) {
    if(isset($_GET["vardas"]) && isset($_GET["pavarde"]) && isset($_GET["teises_id"]) 
    && !empty($_GET["vardas"]) && !empty($_GET["pavarde"]) && !empty($_GET["teises_id"])) {
        $id = $_GET["ID"];
        $vardas = $_GET["vardas"];
        $pavarde = $_GET["pavarde"];
        $teises_id = intval($_GET["teises_id"]);

    // vykdoma uzklausa REIKIA SUTVARKYTI 
    $sql = "INSERT INTO `klientai`(`ID`, `vardas`, `pavarde`, `teises_id`) 
        VALUES ($vardas, $pavarde, $teises_id)"; 

        if(mysqli_query($conn, $sql)) {
            $message =  "Vartotojas redaguotas sėkmingai";
            $class = "success";
        } else {
            $message = "Prasome uzpildyti visus laukelius";
            $class = "danger";
        }
    }
}

?>

<div class="container">
    <h1>Vartotojo sukurimas</h1>
    
        <form action="clientsNew.php" method="get">
                
        <div class="form-group">
            <label for="vardas">Vardas</label>
            <input class="form-control" type="text" name="vardas" placeholder="vardas"/>
        </div>

        <div class="form-group">
            <label for="pavarde">Pavardė</label>
            <input class="form-control" type="text" name="pavarde" placeholder="pavarde"/>
        </div>

        <div class="form-group">
                    <label for="teises_id">Teisės</label>
                    <select class="form-control" name="teises_id">
                        <?php 
                         $sql = "SELECT * FROM klientai_teises";
                         $result = $conn->query($sql);
                        //  $client["teises_id"] - sita kintamaji
                        // kam jis turi buti lygus is duomenu bazes stulpelio?
                         while($clientRights = mysqli_fetch_array($result)) {
                            echo "<option value='".$clientRights["reiksme"]."'>";
                                echo $clientRights["pavadinimas"];
                            echo "</option>";
                        }
                        ?>
                    </select>
                </div>

                <a href="clients.php">Back</a><br>
                <button class="btn btn-primary" type="submit" name="submit">New Client</button>
            </form>

            <?php if(isset($message)) { ?>
                <div class="alert alert-<?php echo $class; ?>" role="alert">
                <?php echo $message; ?>
                </div>
            <?php } ?>
        
              
    </div>
</body>
</html>