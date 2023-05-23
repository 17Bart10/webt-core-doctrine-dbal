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




// $fullRound = $conn->prepare('SELECT pk_round_ID, CONCAT(p1.vorname, \' \', p1.Nachname) AS Player1, sp1.Bezeichnung AS P1Bezeichnung, CONCAT(p2.vorname, \' \', p2.Nachname) AS Player2, sp2.Bezeichnung AS P2Bezeichnung, CONCAT(w.Vorname, \' \', w.Nachname) AS `Winner Name`, startTime
// FROM Round
//          JOIN Player p1 on fk_pk_Player1 = p1.pk_player_ID
//          JOIN Player p2 on fk_pk_Player2 = p2.pk_player_ID
//          JOIN Symbol sp1 on fk_pk_SymbolP1 = sp1.pk_symbol_ID
//          JOIN Symbol sp2 on fk_pk_SymbolP2 = sp2.pk_symbol_ID
//          JOIN Player w on fk_pk_Winner = w.pk_player_ID
// ORDER BY pk_round_ID;');
// $fullRoundResult = $fullRound->executeQuery()->fetchAllAssociative();
// var_dump($fullRoundResult->fetchAllAssociative());

$queryBuilderRound = $conn->createQueryBuilder();
$queryBuilderRound
    ->select('pk_round_ID', 'CONCAT(p1.vorname, \' \', p1.Nachname) AS Player1', 'sp1.Bezeichnung AS P1Bezeichnung', 'CONCAT(p2.vorname, \' \', p2.Nachname) AS Player2', 'sp2.Bezeichnung AS P2Bezeichnung', 'CONCAT(w.Vorname, \' \', w.Nachname) AS `Winner Name`', 'startTime')
    ->from('Round', 'r')
    ->join('r', 'Player', 'p1', 'p1.pk_player_ID = r.fk_pk_Player1')
    ->join('r', 'Player', 'p2', 'p2.pk_player_ID = r.fk_pk_Player2')
    ->join('r', 'Symbol', 'sp1', 'sp1.pk_symbol_ID = r.fk_pk_SymbolP1')
    ->join('r', 'Symbol', 'sp2', 'sp2.pk_symbol_ID = r.fk_pk_SymbolP2')
    ->join('r', 'Player', 'w', 'w.pk_player_ID = r.fk_pk_Winner')
    ->orderBy('r.pk_round_ID');

$fullRoundResult=$queryBuilderRound->fetchAllAssociative();

$queryBuilderPlayer = $conn->createQueryBuilder();
$queryBuilderPlayer
    ->select('pk_player_ID', 'CONCAT(Vorname, \' \', Nachname) AS Name')
    ->from('Player');

$allPlayers = $queryBuilderPlayer->fetchAllAssociative();



echo '


<table>
    <tr>
        <th>Round NR.</th>

        <th>Player 1</th>
        <th>Symbol Player 1</th>

        <th>Player 2</th>
        <th>Symbol Player 2</th>

        <th>Winner</th>

        <th>Date and Time</th>
    </tr>';
        for ($i = 0; $i < count($fullRoundResult); $i++) {
            echo '<tr>
            <td><form method="post" action="' . $_SERVER['PHP_SELF'] . '">
                <input type="hidden" name="pk_round_ID" value="' . $fullRoundResult[$i]["pk_round_ID"] . '">
                <input type="submit" name="delBtn" value="Delete">
                </form></td>';

                echo "<td>" . $fullRoundResult[$i]["Player1"] . "</td>";
                echo "<td>" . $fullRoundResult[$i]["P1Bezeichnung"] . "</td>";
        
                echo "<td>" . $fullRoundResult[$i]["Player2"] . "</td>";
                echo "<td>" . $fullRoundResult[$i]["P2Bezeichnung"] . "</td>";
        
                echo "<td>" . $fullRoundResult[$i]["Winner Name"] . "</td>";
        
                echo "<td>" . date('j-M-Y h:i A', strtotime($fullRoundResult[$i]["startTime"])) . "</td>";
            echo '</tr>';
        }    
    
echo '</table>';


// Insertion



echo '<br><br>
<h1>Insert new records in Database</h1>
    <h4>You must only write the first 2 letters of the symbols in English</h4>
    <form method="post" action="' . $_SERVER['PHP_SELF'] . '">
        <label for="startTime">Date:</label>
        <input type="datetime-local" id="startTime" name="startTime"><br><br>

        <label for="Player1">Name Player 1:</label>
        <input type="text" id="Player1" name="Player1"><br><br>

        <label for="Symbol1">Symbol played by Player 1:</label>
        <input type="text" id="Symbol1" name="Symbol1"><br><br>

        <label for="Player2">Name Player 2:</label>
        <input type="text" id="Player2" name="Player2"><br><br>

        <label for="Symbol2">Symbol played by Player 2:</label>
        <input type="text" id="Symbol2" name="Symbol2"><br><br>
        
        <label for="Winner">Winner of Round:</label>
        <input type="text" id="Winner" name="Winner"><br><br>

        <input type="submit" name="buttonAdd" value="Add">
    </form>';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delBtn'])) {
        $roundID = $_POST['pk_round_ID'];
        deleteRound($queryBuilderRound, $roundID);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else if (isset($_POST['buttonAdd']) && ($_POST['startTime'] != null && $_POST['Player1'] != null && $_POST['Player2'] != null
            && $_POST['Symbol1'] != null && $_POST['Symbol2'] != null) && $_POST['Winner'] != null) {
        $round = array();
        $round[] = explode("T", $_POST['startTime'])[0] . ' ' . explode("T", $_POST['startTime'])[1] . ':00';
        $round[] = $_POST['Player1'];
        $round[] = $_POST['Player2'];
        $round[] = $_POST['Symbol1'];
        $round[] = $_POST['Symbol2'];
        $round[] = $_POST['Winner'];
        addGame($queryBuilderRound, $round, $allPlayers);
        $game = array();
        unset($_POST);
        $_POST = array();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}


function addGame($queryBuilder, $round, $allPlayers){
    if($round[3] == 'ro') {
        $round[3] = '2';
    } else if($round[3] == 'pa') {
        $round[3] = '3';
    } else if($game[3] == 'sc') {
        $round[3] = '1';
    }
    if($round[4] == 'ro') {
        $round[4] = '2';
    } else if($round[4] == 'pa') {
        $round[4] = '3';
    } else if($round[4] == 'sc') {
        $round[4] = '1';
    }

    $p1 = playerNameToID($round[1], $allPlayers);
    $p2 = playerNameToID($round[2], $allPlayers);
    $w = playerNameToID($round[5], $allPlayers);
    


    $queryBuilder
        ->insert('Round')
        ->values([
            'fk_pk_Player1' => $queryBuilder->expr()->literal($p1),
            'fk_pk_Player2' => $queryBuilder->expr()->literal($p2),
            'fk_pk_SymbolP1' => $round[3],
            'fk_pk_SymbolP2' => $round[4],
            'startTime' => $queryBuilder->expr()->literal($round[0]),
            'fk_pk_Winner' => $queryBuilder->expr()->literal($w),
        ])
        ->execute();
}

function symbolToID($symbol) {

}

function playerNameToID ($playerName, $allPlayers) {
    foreach($allPlayers as $player){
        if($playerName == $player["Name"]){
            return $player["pk_player_ID"];
        }
    }


}



function deleteRound($query, $round_ID)
{
    echo $round_ID;
    $query
        ->delete('Round')
        ->where('pk_round_ID = :pk_round_ID')
        ->setParameter('pk_round_ID', $round_ID)
        ->execute();
}

?>
</body>
</html>
