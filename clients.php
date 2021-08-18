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
<?php if(isset($message)) { ?>
    <div class="alert alert-<?php echo $class; ?>" role="alert">
        <?php echo $message; ?>
    </div>
<?php } ?>

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

    $sql = "SELECT * FROM `klientai` ORDER BY `ID` DESC"; 
    $result = $conn->query($sql); // uzklausos vykdymas
    
    while($clients = mysqli_fetch_array($result)) {
        echo "<tr>";
            echo "<td>". $clients["ID"]."</td>";
            echo "<td>". $clients["Vardas"]."</td>";
            echo "<td>". $clients["Pavarde"]."</td>";
            //ifa/switch
            switch($clients["Teises_ID"]) {
                case 0:
                    echo "<td>Naujas klientas</td>";     
                break;
                case 1:
                    echo "<td>Ilgalaikis klientas</td>";
                break;
                case 2:
                    echo "<td>Neaktyvus klientas</td>";
                break;
                case 3:
                    echo "<td>Nemokus klientas</td>";
                break;
                case 4:
                    echo "<td>Uzsienio(Ne EU) klientas</td>";
                break;
                case 5:
                    echo "<td>Uzsienio(EU) klientas</td>";
                break;
                default: echo "<td>Nepatvirtintas klientas</td>";
            }    

            
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
