<?php 
    require_once("connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients Edit</title>

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

//mes pagal ID turetume isvesti visus duomenis i input apie klienta
//ir naujus duomenis per UPDATE sukelti i duomenu baze

if(isset($_GET["ID"])) {
    $id = $_GET["ID"];
    $sql = "SELECT * FROM klientai WHERE ID = $id";

    //kiek rezultatu gaunam? 1 

    $result = $conn->query($sql);//vykdome uzklausa 

    if($result->num_rows == 1) {
        //veiksmai
        $client = mysqli_fetch_array($result);
        $hideForm = false;
    
    } else {
        $hideForm = true;
    }
}

if(isset($_GET["submit"])) {
    //Turime pasiimti visus kintamuosius
    //Kokia uzklausa atlikti? UPDATE
    if(isset($_GET["Vardas"]) && isset($_GET["pavarde"]) && isset($_GET["Teises_ID"]) 
    && !empty($_GET["Vardas"]) && !empty($_GET["Pavarde"]) && !empty($_GET["Teises_ID"])) {
        $id = $_GET["ID"];
        $vardas = $_GET["Vardas"];
        $pavarde = $_GET["Pavarde"];
        $teises_id = intval($_GET["Teises_ID"]);

        $sql = "UPDATE `klientai` SET `Vardas`='$vardas',
        `Pavarde`='$pavarde',`Teises_ID`=$teises_id WHERE ID = $id";

        if(mysqli_query($conn, $sql)) {
            $message =  "Vartotojas redaguotas sėkmingai";
            $class = "success";
        } else {
            $message =  "Kazkas ivyko negerai";
            $class = "danger";
        }
    } else {
        $id = $client["ID"];
        $vardas = $client["Vardas"];
        $pavarde = $client["Pavarde"];
        $teises_id = intval($client["Teises_ID"]);

        $sql = "UPDATE `klientai` SET `Vardas`='$vardas',
        `Pavarde`='$pavarde',`Teises_ID`=$teises_id WHERE ID = $id";

        if(mysqli_query($conn, $sql)) {
            $message =  "Vartotojas redaguotas sėkmingai";
            $class = "success";
        } else {
            $message =  "Kazkas ivyko negerai";
            $class = "danger";
        }
    }
}

?>

<div class="container">
        <h1>Vartotojo redagavimas</h1>
        <?php if($hideForm == false) { ?>
            <form action="clientsEdit.php" method="get">
                
                <input class="hide" type="text" name="ID" value ="<?php echo $client["ID"]; ?>" />

                <div class="form-group">
                    <label for="Vardas">Vardas</label>
                    <input class="form-control" type="text" name="Vardas" value="<?php echo $client["Vardas"]; ?>" />
                </div>
                <div class="form-group">
                    <label for="Pavarde">Pavardė</label>
                    <input class="form-control" type="text" name="Pavarde" value="<?php echo $client["Pavarde"]; ?>"/>
                </div>

                <div class="form-group">
                    <label for="Teises_ID">Teisės</label>
                    <input class="form-control" type="text" name="Teises_ID" value="<?php echo $client["Teises_ID"]; ?>"/>
                </div>

                <a href="clients.php">Back</a><br>
                <button class="btn btn-primary" type="submit" name="submit">Edit</button>
            </form>
            <?php if(isset($message)) { ?>
                <div class="alert alert-<?php echo $class; ?>" role="alert">
                <?php echo $message; ?>
                </div>
            <?php } ?>
        <?php } else { ?>
            <h2> Tokio kliento nėra </h2>
            <a href="clients.php">Back</a>
        <?php }?>    
    </div>
</body>
</html>