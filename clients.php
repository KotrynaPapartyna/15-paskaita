<?php 
    require_once("connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>

    <?php require_once("linkai.php"); ?>

</head>
<body>
    <div class="container">
    
    <?php require_once("includes/menu.php");?>  

<?php 

if(!isset($_COOKIE["prisijungta"])) { 
    header("Location: index.php"); 

} else {
    echo "Sveikas prisijunges";
    echo "<form action='clients.php' method ='get'>";
    echo "<button class='btn btn-primary' type='submit' name='logout'>Logout</button>";
    echo "</form>";
    if(isset($_GET["logout"])) {
        setcookie("prisijungta", "", time() - 3600, "/");
        header("Location: index.php");
    }
}    
?>

<?php 

if(isset($_GET["ID"])) {
    $id = $_GET["ID"];
    $sql = "DELETE FROM `klientai` WHERE ID = $id";
    if(mysqli_query($conn, $sql)) {
        $message = "Klientas sekmingai istrintas";
        $class="success";
    } else {
        $message = "Kazkas ivyko negerai";
        $class="danger";
    }
}
?>

<?php if(isset($message)) { ?> <!--isvedama zinute-->
    <div class="alert alert-<?php echo $class; ?>" role="alert">
        <?php echo $message; ?>
    </div>
<?php } ?>


<!-- paieskos mygtuko paspaudimas-->
<?php if(isset($_GET["search"]) && !empty($_GET["search"])) { ?>
    <a class="btn btn-primary" href="clients.php"> Išvalyti paiešką</a>
<?php } ?>

<!-- rusiavimo nustatymo forma-->
<!--filtravimo forma-->

<div class="row">
    <div class="col-lg-6 col-md-3">
        <h3>Rikiavimas</h3>

    <form action="clients.php" method="get">
        <div class="form-group">
            <select class="form-control" name="rikiavimas_id">
                <option value="DESC"> Nuo didžiausio iki mažiausio</option>
                <option value="ASC"> Nuo mažiausio iki didžiausio</option>
            </select>
            <button class="btn btn-primary" name="rikiuoti" type="submit">Rikiuoti</button>
        </div>
    </form> 
</div>

    <div class="col-lg-6 col-md-9">
        <h3>Filtravimas</h3>
        <form action="clients.php" method="get">
        <select class="form-control" name="filtravimas_id">

        <?php if(isset($_GET["filtravimas_id"]) && !empty($_GET["filtravimas_id"]) && $_GET["filtravimas_id"] != "default") {?>
                <option value="default">Rodyti visus</option>
<?php } else {?>
                <option value="default" selected="true">Rodyti visus</option>
<?php } ?>    

        <?php 
            $sql = "SELECT * FROM klientai_teises";
            $result = $conn->query($sql);

            while($clientRights = mysqli_fetch_array($result)) {
                if(isset($_GET["filtravimas_id"]) && $_GET["filtravimas_id"] == $clientRights["reiksme"] ) {
                    echo "<option value='".$clientRights["reiksme"]."' selected='true'>";
                    } else  {
                    echo "<option value='".$clientRights["reiksme"]."'>";
                    }
                     echo $clientRights["pavadinimas"];
                    echo "</option>";
                    }
        ?>
        </select>
                <button class="btn btn-primary" name="filtruoti" type="submit">Filtruoti</button>            
        </form>


        <?php   if(isset($_GET["filtravimas_id"]) && !empty($_GET["filtravimas_id"]) && $_GET["filtravimas_id"] != "default") { ?>
            <a class="btn btn-primary" href="clients.php">Išvalyti filtrą</a>
        <?php } ?>
    </div>
</div> 


    <table class="table table-striped">
        <thead>
            <tr>
            <th scope="col">ID</th>
            <th scope="col">Vardas</th>
            <th scope="col">Pavardė</th>
            <th scope="col">Teisės</th>
            <th scope="col">Veiksmai</th>
            </tr>
        </thead>
    <tbody>


<?php 

// filtravimo nustatymas
if(isset($_GET["filtravimas_id"]) && !empty($_GET["filtravimas_id"]) && $_GET["filtravimas_id"] != "default") {
    $filtravimas = "klientai.teises_id =" .$_GET["filtravimas_id"];
} else {
    $filtravimas = 1;
}

// rikiavimo nustatymas    
if(isset($_GET["rikiavimas_id"]) && !empty($_GET["rikiavimas_id"])) {
        $rikiavimas = $_GET["rikiavimas_id"];
    } else {
        $rikiavimas = "DESC"; // nuo didziausio 
    }
    
        // uzklausa is serverio
        $sql = "SELECT klientai.ID, klientai.vardas, klientai.pavarde, klientai_teises.pavadinimas FROM klientai 
        LEFT JOIN klientai_teises ON klientai_teises.reiksme = klientai.teises_id 
        WHERE $filtravimas
        ORDER BY klientai.ID $rikiavimas"; 

if(isset($_GET["search"]) && !empty($_GET["search"])) {
        $search = $_GET["search"];
     
    // uzklausa is serverio
    $sql = "SELECT * FROM `klientai` WHERE `vardas` LIKE '%".$search."%' OR `pavarde` LIKE '%".$search."%' ORDER BY `ID` $rikiavimas";$sql = "SELECT klientai.ID, klientai.vardas, klientai.pavarde, klientai_teises.pavadinimas FROM klientai 
    LEFT JOIN klientai_teises ON klientai_teises.reiksme = klientai.teises_id 
    
    WHERE klientai.vardas LIKE '%".$search."%' OR klientai_teises.pavadinimas LIKE '%".$search."%'
    ORDER BY klientai.ID $rikiavimas";
}

    $result = $conn->query($sql); // vykdoma uzklausa is serverio 
    
    while($clients = mysqli_fetch_array($result)) {
        echo "<tr>";
            echo "<td>". $clients["ID"]."</td>";
            echo "<td>". $clients["vardas"]."</td>";
            echo "<td>". $clients["pavarde"]."</td>";
            echo "<td>". $clients["pavadinimas"]."</td>";

            //vykdoma uzklausa is duomenu bazes pagal teises_id
                //$teises_id=$clients["teises_id"];
                //$sql="SELECT * FROM klientai_teises WHERE reiksme=$teises_id"; 
            // gausime 1 irasa 
                //$result_teises = $conn->query($sql); // vykdoma uzklausa 
            // programiskai vykdomas lenteliu sujungimas
            // kad nebutu taip- pakeiciamas kintamasis i kitoki

                //if ($result_teises->num_rows==1) {
                   // $rights=mysqli_fetch_array($result_teises); 
                    //echo "<td>";
                        //echo $rights["pavadinimas"]; 
                    //echo "</td>";
                //} else {
                    //echo "<td>Nepatvirtintas klientas</td>";
                //}


            //ifa/switch/ turi buti atvaizdavimas pagal DB
            //switch($clients["Teises_ID"]) {
                //case 0:
                    //echo "<td>Naujas klientas</td>";     
                //break;
                //case 1:
                    //echo "<td>Ilgalaikis klientas</td>";
                //break;
                //case 2:
                    //echo "<td>Neaktyvus klientas</td>";
                //break;
                //case 3:
                    //echo "<td>Nemokus klientas</td>";
                //break;
                //case 4:
                    //echo "<td>Uzsienio(Ne EU) klientas</td>";
                //break;
                //case 5:
                    //echo "<td>Uzsienio(EU) klientas</td>";
                // break;
                //default: echo "<td>Nepatvirtintas klientas</td>";
            //}    

            
            echo "<td>";
                echo "<a href='clients.php?ID=".$clients["ID"]."'>Trinti</a><br>";
                echo "<a href='editClients.php?ID=".$clients["ID"]."'>Redaguoti</a>";
            echo "</td>";
        echo "</tr>";
    }
    //Atvaizduoti visus klientus. I lentele

    //Kiekviena is klientu triname pagal jo ID
    //ID perduoti per GET. Per nuoroda
    ?>
  </tbody>
</table>
    </div>

    

</body>
</html>
