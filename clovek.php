<?php
$id = filter_input(INPUT_GET,
    'clovekId',
    FILTER_VALIDATE_INT,
    ["options" => ["min_range"=> 1]]
);

if ($id === null || $id === false) {
    http_response_code(400);
    $status = "bad_request";
    $title = "No id";
} else {

    require_once "inc/db.inc.php";

    $stmt = $pdo->prepare("SELECT * FROM employee WHERE employee_id=:clovekId");
    $stmt->execute(['clovekId' => $id]);
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        $status = "not_found";
    } else {
        $room = $stmt->fetch();
        $status = "OK";       
    }
    
    $titlestmt = $pdo->prepare("SELECT * FROM employee WHERE employee_id=:clovekId");
    $titlestmt->execute(['clovekId' => $id]);
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        $status = "not_found";
        $title = "Not found";
    } else {
        while($row=$titlestmt->fetch()){
            $jmenoKratsi = substr($row->name,0,1);
            $title = "Karta osoby {$row->surname} {$jmenoKratsi}.";
        }   
    }   
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title><?php echo $title; ?></title>
</head>
<body class="container">
<?php
switch ($status) {
    case "bad_request":
        echo "<h1>Error 400: Bad request</h1>";
        break;
    case "not_found":
        echo "<h1>Error 404: Not found</h1>";
        break;
}
?>
    <?php
    
    require_once "inc/db.inc.php";

    $stmt = $pdo->query("SELECT *, room.name as rname, room.room_id, employee.name as jmeno FROM employee JOIN room ON employee.room=room.room_id WHERE employee_id=$id");
    
    $stmtKeys = $pdo->query("SELECT * FROM room JOIN `key` ON room.room_id=`key`.room WHERE `key`.employee=$id");

    if ($stmt->rowCount() == 0) {
        echo "Záznam neobsahuje žádná data";
    } else {

        while ($row = $stmt->fetch()) {
            $jmenoKratsi = substr($row->jmeno,0,1);
            echo "<h1>Karta osoby: <em>{$row->surname} {$jmenoKratsi}.</em></h1>";
            echo "<dl class='dl-horizontal'>";
            echo "<dt>Jméno</dt> <dd>{$row->jmeno}</dd>";
            echo "<dt><td>Příjmení</dt> <dd>{$row->surname}</dd>";
            echo "<dt>Pozice</dt> <dd>{$row->job}</dd>";
            echo "<dt><td>Mzda</dt> <dd>{$row->wage}</dd>";
            echo "<dt>Místnost</dt>";
            $odkazNaMistnost = "room.php?roomId=$row->room_id";
            echo "<dd><a href='".$odkazNaMistnost."'>{$row->rname}</a><br></dd>";
            echo "<dt>Klíče </dt>";
            foreach($stmtKeys as $klice){
            $odkazNaMistnost = "room.php?roomId=$klice->room_id";
            echo "<dd><a href='".$odkazNaMistnost."'>{$klice->name}</a><br></dd>";
         }
            echo "</dl>";
         }
        echo "<a href='lide.php'>
        <span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span>
        Zpět na seznam zaměstnanců
        </a>";
    }
    unset($stmt);
    ?>
</body>
</html>