<?php
require 'vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rock Paper Scissors Tournament</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid black;
        }

        th, td {
            text-align: left;
            padding: 8px;
            border: 1px solid black;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        td:first-child {
            font-weight: bold;
        }

        td:nth-child(3), td:nth-child(5) {
            text-transform: uppercase;
        }
    </style>
</head>

<body>
<h1>Rock Paper Scissors Tournament</h1>
<p>Date: 19th April 2023</p>



<?php
$connectionParams = [
    'dbname' => 'webt_doctrine',
    'user' => 'root',
    'password' => 'root',
    'host' => 'localhost:8889',
    'driver' => 'pdo_mysql',
];

$conn = DriverManager::getConnection($connectionParams);




$fullRound = $conn->prepare('SELECT pk_round_ID, CONCAT(p1.vorname, \' \', p1.Nachname) AS Player1, sp1.Bezeichnung AS P1Bezeichnung, CONCAT(p2.vorname, \' \', p2.Nachname) AS Player2, sp2.Bezeichnung AS P2Bezeichnung, CONCAT(w.Vorname, \' \', w.Nachname) AS `Winner Name`, startTime
FROM Round
         JOIN Player p1 on fk_pk_Player1 = p1.pk_player_ID
         JOIN Player p2 on fk_pk_Player2 = p2.pk_player_ID
         JOIN Symbol sp1 on fk_pk_SymbolP1 = sp1.pk_symbol_ID
         JOIN Symbol sp2 on fk_pk_SymbolP2 = sp2.pk_symbol_ID
         JOIN Player w on fk_pk_Winner = w.pk_player_ID
ORDER BY pk_round_ID;');
$fullRoundResult = $fullRound->executeQuery()->fetchAllAssociative();
// var_dump($fullRoundResult->fetchAllAssociative());







?>


<table>
    <tr>
        <th>Round NR.</th>

        <th>Player 1</th>
        <th>Symbol Player 1</th>

        <th>Player 2</th>
        <th>Symbol Player 2</th>

        <th>Winner</th>

        <th>Date and Time</th>
    </tr>
    <?php
        for ($i = 0; $i < count($fullRoundResult); $i++) {
            echo "<tr>";
                echo "<td>" . $fullRoundResult[$i]["pk_round_ID"] . "</td>";

                echo "<td>" . $fullRoundResult[$i]["Player1"] . "</td>";
                echo "<td>" . $fullRoundResult[$i]["P1Bezeichnung"] . "</td>";
        
                echo "<td>" . $fullRoundResult[$i]["Player2"] . "</td>";
                echo "<td>" . $fullRoundResult[$i]["P2Bezeichnung"] . "</td>";
        
                echo "<td>" . $fullRoundResult[$i]["Winner Name"] . "</td>";
        
                echo "<td>" . date('j-M-Y h:i A', strtotime($fullRoundResult[$i]["startTime"])) . "</td>";
            echo "</tr>";
        }
        ?>

</table>
</body>
</html>
