<?php 
    require_once("connection.php");
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>

    <?php require_once("linkai.php"); ?>
</head>
<body>
    <div class="container">
        <?php require_once("includes/menu.php"); ?>
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

<?php if(isset($_GET["search"]) && !empty($_GET["search"])) { ?>
    <a class="btn btn-primary" href="clients.php"> Išvalyti paiešką</a>
<?php } ?>


<div class="row">
    <div class="col-lg-12 col-md-12">
        <h3>Filtravimas ir rikiaviamas</h3>
        <form action="clients.php" method="get">

        <select class="form-control" name="rikiuoti_pagal">
            <?php 
                $sql = "SELECT * FROM `klientai_rikiavimas`";

                $result = $conn->query($sql); //vykdoma uzklausa

                $rikiavimo_stulpelis = array();
                
                $skaitiklis = 1;


                //3 irasai
                //3 kartus
                //pacios pirmos reiksmes
                while($sortColumns = mysqli_fetch_array($result)) {

                    if($skaitiklis == 1) {
                        $numatytoji_reiksme = $sortColumns["ID"]; //paskutine reiksme
                    }
                    
                    
                    if(isset($_GET["rikiuoti_pagal"]) && $_GET["rikiuoti_pagal"] == $sortColumns["ID"]) {
                        echo "<option value='".$sortColumns["ID"]."' selected='true'>".$sortColumns["rikiavimo_pavadinimas"]."</option>";
                    } else {
                        echo "<option value='".$sortColumns["ID"]."'>".$sortColumns["rikiavimo_pavadinimas"]."</option>";    
                    }
                    
                    $rikiavimo_stulpelis[$sortColumns["ID"]] =  $sortColumns["rikiavimo_stulpelis"];
                    
                    $skaitiklis++;
                }

                // var_dump($rikiavimo_stulpelis);
            ?>
        </select>

        <select class="form-control" name="rikiavimas_id">
                    <?php if((isset($_GET["rikiavimas_id"]) && $_GET["rikiavimas_id"] == "DESC") || !isset($_GET["rikiavimas_id"]) ) {  ?>
                        <option value="DESC" selected="true"> Nuo didžiausio iki mažiausio</option>
                        <option value="ASC"> Nuo mažiausio iki didžiausio</option>
                    <?php } else {?>
                        <option value="DESC"> Nuo didžiausio iki mažiausio</option>
                        <option value="ASC" selected="true"> Nuo mažiausio iki didžiausio</option>
                    <?php } ?>    
        </select>

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
                <button class="btn btn-primary" name="filtruoti" type="submit">Vykdyti</button>            
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

    $clients_count = 10;
    $pagination_url = "";        

    if(isset($_GET["page-limit"])) {
        $page_limit = $_GET["page-limit"] * $clients_count - $clients_count;    
    } else {
        $page_limit = 0;    
    }        

    //1. Sujungti dvi formas. T.y is musu turimu dvieju formu padaryti tik viena
    //2. Abejose formose tureti pasleptus rikiavimas_id ir filtravimas_id input laukelius.
    
    if(isset($_GET["rikiuoti_pagal"]) && !empty($_GET["rikiuoti_pagal"])) {
         $rikiuoti_pagal = $rikiavimo_stulpelis[$_GET["rikiuoti_pagal"]];
         $pagination_url .= "&rikiuoti_pagal=". $_GET["rikiuoti_pagal"];
    } else {
         $rikiuoti_pagal = $rikiavimo_stulpelis[$numatytoji_reiksme];
    }

    // switch($rikiuoti_pagal) {
    //     case 1: $rikiuoti_pagal = "klientai.ID";
    //     break;
    //     case 2: $rikiuoti_pagal = "klientai.vardas";
    //     break;
    //     case 3: $rikiuoti_pagal = "klientai.pavarde";
    //     break;
    //     case 4: $rikiuoti_pagal = "klientai_teises.pavadinimas";
    //     break;
    //     default: $rikiuoti_pagal = "klientai.ID";
    // }

    if(isset($_GET["filtravimas_id"]) && !empty($_GET["filtravimas_id"]) && $_GET["filtravimas_id"] != "default") {
        //zino, kad filtravimas yra atliekamas
        $filtravimas = "klientai.teises_id =" .$_GET["filtravimas_id"];
        $pagination_url .= "&filtravimas_id=". $_GET["filtravimas_id"];
    } else {
        // kad nera jokio filtro
        $filtravimas = 1;
    }

    if(isset($_GET["rikiavimas_id"]) && !empty($_GET["rikiavimas_id"])) {
        $rikiavimas = $_GET["rikiavimas_id"];
        $pagination_url .= "&rikiavimas_id=". $_GET["rikiavimas_id"];
    } else {
        $rikiavimas = "DESC";
    }


    $sql = "SELECT klientai.ID, klientai.vardas, klientai.pavarde, klientai_teises.pavadinimas FROM klientai 
    LEFT JOIN klientai_teises ON klientai_teises.reiksme = klientai.teises_id 
    WHERE $filtravimas
    ORDER BY $rikiuoti_pagal $rikiavimas
    LIMIT $page_limit , $clients_count 
    ";

    if(isset($_GET["search"]) && !empty($_GET["search"])) {
        $search = $_GET["search"];

        $sql = "SELECT klientai.ID, klientai.vardas, klientai.pavarde, klientai_teises.pavadinimas FROM klientai 
        LEFT JOIN klientai_teises ON klientai_teises.reiksme = klientai.teises_id 
        
        WHERE klientai.vardas LIKE '%".$search."%' OR klientai.pavarde 
        LIKE '%".$search."%' AND $filtravimas
        ORDER BY $rikiuoti_pagal $rikiavimas
        LIMIT $page_limit , $clients_count 
        ";
        
    }

    $result = $conn->query($sql); // uzklausos vykdymas
    // 0 - Naujas klientas
    // 1 - Ilgalaikis klientas
    // 2 - Neaktyvus klientas
    // 3 - Nemokus klientas
    // 4 - Uzsienio(Ne EU) klientas
    // 5 - Uzsienio(EU) klientas
    while($clients = mysqli_fetch_array($result)) {
        echo "<tr>";
            echo "<td>". $clients["ID"]."</td>";
            echo "<td>". $clients["vardas"]."</td>";
            echo "<td>". $clients["pavarde"]."</td>";
            echo "<td>". $clients["pavadinimas"]."</td>";
            echo "<td>";
                echo "<a href='clients.php?ID=".$clients["ID"]."'>Trinti</a><br>";
                echo "<a href='clientsEdit.php?ID=".$clients["ID"]."'>Redaguoti</a>";
            echo "</td>";
        echo "</tr>";
    }
    //Atvaizduoti visus klientus. I lentele

    //Kiekviena is klientu triname pagal jo ID
    //ID perduoti per GET. Per nuoroda
    ?>
  </tbody>
</table>

<?php
        // 30 irasu viename puslapyje
        // AS komanda - stulpelio pervadinimas
        //FLOOR - grindys = 15.6 = 15
        //CEILING - lubos = 15.1 = 16
        //visa klientu skaiciu: 391/30 = puslapiu skaicius
        if(isset($_GET["search"]) && !empty($_GET["search"])) {
            $page_filtering = "klientai.vardas LIKE '%".$search."%' OR klientai.pavarde 
            LIKE '%".$search."%' AND $filtravimas";
        } else {
            $page_filtering = $filtravimas;
        }
        
        $sql = "SELECT CEILING(COUNT(ID)/$clients_count) AS puslapiu_skaicius, COUNT(ID) AS viso_klientai 
        FROM klientai
        WHERE $page_filtering
        ";
        $result = $conn->query($sql);  
        //Kiek irasu grazina sita uzklausa?
        //1 irasas
        if($result->num_rows == 1) { 
            $clients_total_pages = mysqli_fetch_array($result);
            // var_dump($clients_total_pages);
            
            for($i = 1; $i <= intval($clients_total_pages["puslapiu_skaicius"]); $i++) {
                //Ar tikrai mes $i turim perduot?
                //rikiuoti_pagal=15&
                //rikiavimas_id=DESC&
                //filtravimas_id=4

                // 1 2 3 4 ...
                //$_GET["page-limit"] = 1 iki tiek puslapiu kiek turim

                if(!isset($_GET["page-limit"]) && $i==1) {
                    echo "<a class='btn btn-primary active' href='clients.php?page-limit=$i$pagination_url'>";
                } else if((isset($_GET["page-limit"]) && $_GET["page-limit"] == $i) )
                {
                    echo "<a class='btn btn-primary active' href='clients.php?page-limit=$i$pagination_url'>";
                } else {
                    echo "<a class='btn btn-primary' href='clients.php?page-limit=$i$pagination_url'>";
                }
                echo $i; //puslapio numeris
                    echo " ";
                echo "</a>";
            }
            
            echo "<p>";
            echo "Is viso puslapiu: ";
            echo $clients_total_pages["puslapiu_skaicius"];
            echo "</p>";

            echo "<p>";
            if (isset($_GET["page-limit"])) {
                echo $_GET["page-limit"];
            } else {
                echo "1";
            }
            
            echo " iš ";
            echo $clients_total_pages["puslapiu_skaicius"];
            echo "</p>";

            echo "<p>";
            echo "Is viso klientu: ";
             echo $clients_total_pages["viso_klientai"];
            echo "</p>";
        }
        else {
            echo "Nepavyko suskaiciuoti klientu";
        }
    ?>

</div>


<?php mysqli_close($conn); ?>
</body>
</html>