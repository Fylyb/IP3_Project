<?php

function Order($poradi){   
    $order = "";
    switch($poradi){
        case "nazev_up": $order = "room.name ASC";
            break;
        case "nazev_down": $order = "room.name DESC";
            break;
        case "cislo_up": $order = "room.no ASC";
            break;
        case "cislo_down": $order = "room.no DESC";
            break;    
        case "telefon_up": $order = "room.phone ASC";
            break;
        case "telefon_down": $order = "room.phone DESC";
            break;
        default: $order = "room.name ASC";
            break;
    } 
    return $order;
}
if(!isset($_GET['poradi'])) 
{
    $order = "room.name ASC";
}
else  
{
    $order = Order($_GET['poradi']);
}
?>
<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <!-- Bootstrap-->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>Seznam místností</title>
</head>

<body class="container">
    <?php

    require_once "inc/db.inc.php";

    $stmt = $pdo->query("SELECT * FROM room ORDER BY $order");

    echo "<h1>Seznam místností</h1>";

    if ($stmt->rowCount() == 0) {
        echo "Záznam neobsahuje žádná data";
    } else {
        echo "<table class='table'><tbody>";
        echo "<tr>";
        echo "<th>Název<a href='?poradi=nazev_up'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a>
    <a href='?poradi=nazev_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>

    <th>Číslo<a href='?poradi=cislo_up'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a>
    <a href='?poradi=cislo_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>
    
    <th>Telefon<a href='?poradi=telefon_up'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a>
    <a href='?poradi=telefon_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>";
        echo "</tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td><a href='room.php?roomId={$row->room_id}'>{$row->name}</a></td><td>{$row->no}</td><td>{$row->phone}</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    }
    unset($stmt);
    ?>
</body>

</html>