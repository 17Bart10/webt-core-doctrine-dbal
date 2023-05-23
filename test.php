<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    require 'vendor/autoload.php';

    use Doctrine\DBAL\DriverManager;
$connectionParams = [
    'dbname' => 'rps',
    'user' => 'root',
    'password' => '',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
];
$conn = DriverManager::getConnection($connectionParams);
$queryBuilderGame = $conn->createQueryBuilder();
$queryBuilderSymbol = $conn->createQueryBuilder();

$queryBuilderGame
    ->select('pk_gameID', 'date', 'player1', 'player2', 'fk_symbol1', 'fk_symbol2')
    ->from('game');

$queryBuilderSymbol
    ->select('symbolPlayed')
    ->from('symbol');

$symbols = $queryBuilderSymbol->fetchAllAssociative();
$game = $queryBuilderGame->fetchAllAssociative();
//Das da in die foreach wo du die datenbank ausliest
echo '<td><form method="post" action="' . $_SERVER['PHP_SELF'] . '">
    <input type="hidden" name="gameId" value="' . $gameTable['pk_gameID'] . '">
    <input type="submit" name="button1" value="Delete">
</form></td>';
echo '<br><br>
<h1>Insert new records in Database</h1>
    <h4>You only need to write the first 2 letters of the symbols</h4>
    <form method="post" action="' . $_SERVER['PHP_SELF'] . '">
        <label for="date">Date:</label>
        <input type="datetime-local" id="date" name="date"><br><br>

        <label for="player1">Name Player 1:</label>
        <input type="text" id="player1" name="player1"><br><br>

        <label for="symbol1">Symbol played by Player 1:</label>
        <input type="text" id="symbol1" name="symbol1"><br><br>

        <label for="player2">Name Player 2:</label>
        <input type="text" id="player2" name="player2"><br><br>

        <label for="symbol2">Symbol played by Player 2:</label>
        <input type="text" id="symbol2" name="symbol2"><br><br>

        <input type="submit" name="buttonAdd" value="Add">
    </form>';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['button1'])) {
        $gameId = $_POST['gameId'];
        deleteGame($queryBuilderGame, $gameId);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else if (isset($_POST['buttonAdd']) && ($_POST['date'] != null && $_POST['player1'] != null && $_POST['player2'] != null
            && $_POST['symbol1'] != null && $_POST['symbol2'] != null)) {
        $game = array();
        $game[] = explode("T", $_POST['date'])[0] . ' ' . explode("T", $_POST['date'])[1] . ':00';
        $game[] = $_POST['player1'];
        $game[] = $_POST['player2'];
        $game[] = $_POST['symbol1'];
        $game[] = $_POST['symbol2'];
        addGame($queryBuilderGame, $game);
        $game = array();
        unset($_POST);
        $_POST = array();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}
function deleteGame($queryBuilder, $gameId)
{
    $queryBuilder
        ->delete('game')
        ->where('pk_gameID = :gameId')
        ->setParameter('gameId', $gameId)
        ->execute();
}

function addGame($queryBuilder, $game){
    if($game[3] == 'ro') {
        $game[3] = '1';
    } else if($game[3] == 'pa') {
        $game[3] = '2';
    } else if($game[3] == 'sc') {
        $game[3] = '3';
    }
    if($game[4] == 'ro') {
        $game[4] = '1';
    } else if($game[4] == 'pa') {
        $game[4] = '2';
    } else if($game[4] == 'sc') {
        $game[4] = '3';
    }
    $queryBuilder
        ->insert('game')
        ->values([
            'date' => $queryBuilder->expr()->literal($game[0]), // Wrap the value in expr()->literal() to format it correctly
            'player1' => $queryBuilder->expr()->literal($game[1]), // Wrap the value in expr()->literal() to format it correctly
            'player2' => $queryBuilder->expr()->literal($game[2]), // Wrap the value in expr()->literal() to format it correctly
            'fk_symbol1' => $game[3],
            'fk_symbol2' => $game[4],
        ])
        ->execute();
}
        ?>
</body>
</html>