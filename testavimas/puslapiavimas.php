<?php require_once("../connection.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puslapiavimas</title>
    <?php require_once("../linkai.php"); ?>
</head>
<body>
    <div class="container">
    <table class="table table-striped">
    <thead>
        <tr>
        <th scope="col">ID</th>
        <th scope="col">Vardas</th>
        <th scope="col">Pavardė</th>
        <th scope="col">Teisės</th>
        </tr>
    </thead>
    <tbody>
        <?php
        
        //28 psl
        // 28

        if(isset($_GET["page-limit"])) {
            $page_limit = $_GET["page-limit"] * 15 - 15;    
        } else {
            $page_limit = 0;    
        }

        // Rodoma po 15 irasu kiekviename puslapyje
        $sql = "SELECT * FROM 
        klientai
        ORDER BY klientai.ID ASC
        LIMIT $page_limit , 15
        ";

        // mysqli_fetch_array- duomenu bazes irasus pavercia i masyva
        // irasa pavercia i eilute
        // kol yra irasu- atlikt sia funkcija (paversk i masyva)
        // kuri atvaizduojama lenteleje
        // funkcija dazniausiai naudojama atvaizdavimui
        $result = $conn->query($sql); 
        while($clients = mysqli_fetch_array($result)) {
            echo "<tr>";
                echo "<td>". $clients["ID"]."</td>";
                echo "<td>". $clients["vardas"]."</td>";
                echo "<td>". $clients["pavarde"]."</td>";
                echo "<td>". $clients["teises_id"]."</td>";
            echo "</tr>";
        }
        
        ?>
    </tbody>
</table>

    <?php

        //FLOOR - grindys = 15.6 = 15- paima zemutine reiksme- sveika skaiciu
        //CEILING - lubos = 15.1 = 16- paima virsutine reiksme- virsutini skaiciu
        $sql = "SELECT CEILING(COUNT(ID)/15), COUNT(ID) FROM klientai";
        $result = $conn->query($sql);  
        
        if($result->num_rows == 1) { 
            $clients_total_pages = mysqli_fetch_array($result);
            
            
            for($i = 1; $i <= intval($clients_total_pages[0]); $i++) {
                
                echo "<a href='puslapiavimas.php?page-limit=$i'>";
                    echo $i; //puslapio numeris
                    echo " ";
                echo "</a>";
            }
            
            echo "<p>";
            echo "Is viso puslapiu duomenu bazeje yra: ";
            echo $clients_total_pages[0];
            echo "</p>";

            echo "<p>";
            echo "Is viso klientu duomenu bazeje yra: ";
             echo $clients_total_pages[1];
            echo "</p>";
        }
        else {
            echo "Nepavyko suskaiciuoti klientu";
        }
    ?>

</div>
</body>
</html>