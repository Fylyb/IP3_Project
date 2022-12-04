<?php
function Order($poradi)
{
    $order = "";
    switch ($poradi) {
        case "prijmeni_up": $order = "employee.surname ASC";
            break;
        case "prijmeni_down": $order = "employee.surname DESC";
            break;
        case "mistnosti_up": $order = "room.name ASC";
            break;
        case "mistnosti_down": $order = "room.name DESC";
            break;
        case "telefon_up": $order = "room.phone ASC";
            break;
        case "telefon_down": $order = "room.phone DESC";
            break;
        case "pozice_up": $order = "employee.job ASC";
            break;
        case "pozice_down": $order = "employee.job DESC";
            break;
        default: $order = "employee.surname ASC";
            break;
    }
    return $order;
}
if (!isset($_GET['poradi'])) {
    $order = "employee.surname ASC";
} else {
    $order = Order($_GET['poradi']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Bootstrap-->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>Seznam zaměstnanců</title>
</head>

<body class="container">
    <?php
    
    require_once "inc/db.inc.php";

    $stmt = $pdo->query("SELECT employee.name, employee.surname, employee.job, room.name as rname, room.phone, room.room_id, employee.employee_id FROM employee JOIN room ON employee.room=room.room_id ORDER BY $order");

    echo "<h1>Seznam zaměstnanců</h1>";

    if ($stmt->rowCount() == 0) {
        echo "Záznam neobsahuje žádná data";
    } else {
        echo "<table class='table'>";
        echo "<tbody>";
        echo "<tr>";
        echo "<th>Jména<a href='?poradi=prijemi_up'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a>
            <a href='?poradi=prijmeni_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>

            <th>Místnost<a href='?poradi=mistnosti_up'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a>
            <a href='?poradi=mistnosti_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>
            
            <th>Telefon<a href='?poradi=telefon_up'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a>
            <a href='?poradi=telefon_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>
            
            <th>Pozice<a href='?poradi=pozice_up'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a>
            <a href='?poradi=pozice_down'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a></th>";
        echo "</tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td><a href='clovek.php?clovekId={$row->employee_id}'>{$row->surname} {$row->name}</a></td><td>{$row->rname}</td><td>{$row->phone}</td><td>{$row->job}</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    }
    unset($stmt);
    ?>
</body>

</html>