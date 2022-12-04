<?php
$id = filter_input(INPUT_GET,
    'roomId',
    FILTER_VALIDATE_INT,
    ["options" => ["min_range"=> 1]]
);


if ($id === null || $id === false) {
    http_response_code(400);
    $status = "bad_request";
    $title = "No id";
} else {

    require_once "inc/db.inc.php";

    $stmt = $pdo->prepare("SELECT * FROM room WHERE room_id=:roomId");
    $stmt->execute(['roomId' => $id]);
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        $status = "not_found";
        $title = "Not found";
    } else {
        $room = $stmt->fetch();
        $status = "OK";
    }

    $titlestmt = $pdo->prepare("SELECT * FROM room WHERE room_id=:roomId");
    $titlestmt->execute(['roomId' => $id]);
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        $status = "not_found";
    } else {
        while($row=$titlestmt->fetch()){
            $jmenoKratsi = substr($row->name,0,1);
            $title = "Karta místnosti č. {$row->no}";
        }
        
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title><?php echo $title;?></title>
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

    $stmt = $pdo->query("SELECT * FROM `room` WHERE room_id=$id");
    $aveSala = $pdo->prepare("SELECT AVG(wage) as avg_salary FROM employee WHERE room=:roomId");
    $aveSala->execute(['roomId' => $id]);
    $avgSalary = $aveSala->fetch();  
    $pruMzda = number_format($avgSalary->avg_salary,2);

    $employeeKeys = $pdo->prepare("SELECT employee.employee_id, employee.name, employee.surname FROM `key` JOIN employee ON employee.employee_id=`key`.employee WHERE `key`.room=:roomId");
    $employeeKeys->execute(['roomId' => $id]);
    $employeeKeys = $employeeKeys->fetchAll();

    $employee = $pdo->prepare("SELECT * FROM employee WHERE room=:roomId");
    $employee->execute(['roomId' => $id]);
    $employee = $employee->fetchAll();


    if ($stmt->rowCount() == 0) {
        echo "Záznam neobsahuje žádná data";
    } else {
        while ($row = $stmt->fetch()) {
            echo "<h1>Místnost č. {$row->no}</h1>";
            echo "<dl class='dl-horizontal'>";
            echo "<dt>Číslo</dt> <dd>{$row->no}</dd>";
            echo "<dt>Název</dt> <dd>{$row->name}</dd>";
            echo "<dt>Telefon</dt> <dd>{$row->phone}</dd>";
            echo "<dt>Lidé</dt>";
            foreach($employee as $odkaz){
            
                $odkazNaLidi = "clovek.php?clovekId=$odkaz->employee_id";
                $jmenoKratsi = substr($odkaz->name,0,1);
                echo "<dd><a href='".$odkazNaLidi."'>{$odkaz->surname} {$jmenoKratsi}.</a><br><dd>";
            }
            echo "<dt>Průměrná mzda</dt> <dd>{$pruMzda}</dd>";
            echo "<dt>Klíče</dt>";
            foreach($employeeKeys as $clovek){
                $odkazNaLidi = "clovek.php?clovekId=$clovek->employee_id";
                $jmenoKratsi = substr($clovek->name,0,1);
                echo "<dd><a href='".$odkazNaLidi."'>{$clovek->surname} {$jmenoKratsi}.</a><br></dd>";
            }

            echo "</dl>";
            echo "<a href='mistnosti.php'><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span>Zpět na seznam místností</a>";
        }
    }
    unset($stmt);
    ?>
</body>
</html>