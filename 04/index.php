<?php
/*
 * Homework 4 
 * Jeff Pollack
 * Time logged on assignemt: 3.5 hours
 */
include ('db_connect.php');
//print_r($_POST);


if (isset($_POST['playerName'])) {
    $playerName = $_POST['playerName'];
} else {
    $playerName = '';
}

if (isset($_POST['date1'])) {
    $date1 = $_POST['date1'];
} else {
    $date1 = '';
}

if (isset($_POST['date2'])) {
    $date2 = $_POST['date2'];
} else {
    $date2 = '';
}

if (isset($_POST['winner'])) {
    $winner = $_POST['winner'];
} else {
    $winner = 'any';
}

$sqlSelect = "SELECT id, player1, player2, result, eco, DATE_FORMAT(matchDate, '%m/%d/%Y') FROM matches";
$sqlOrder = " ORDER BY matchDate desc  LIMIT 250";
$where = " WHERE ";
$and = " AND ";

// Find a player name search
if ($playerName) {
    $sqlName = "(player1 LIKE '%$playerName%' OR player2 LIKE '%$playerName%')";
} else {
    $sqlName = "";
}

// Checking winner filter
switch ($winner) {
    case 'any':$sqlWinner = "";
        break;
    case '1':$sqlWinner = "(result=1)";
        break;
    case '2':$sqlWinner = "(result=2)";
        break;
    case 'D':$sqlWinner = "(result='D')";
        break;
}

// Collecting user date filter   
/*
 * NOTE:
 * Found explode method on stackoverflow.com from user: eggyal
 * LINK: http://stackoverflow.com/questions/12120433/php-mysql-insert-date-format
 */

if ($date1 && $date2) {
    $parts1 = explode('/', $_POST['date1']);
    $parts2 = explode('/', $_POST['date2']);
    $date1Form = "$parts1[2]/$parts1[0]/$parts1[1]";
    $date2Form = "$parts2[2]/$parts2[0]/$parts2[1]";

    $sqlDateFilt = "(matchDate BETWEEN '$date1Form' AND '$date2Form')";
} else {
    $sqlDateFilt = "";
}


// Until I figure out something better....    
if ($sqlName && $sqlWinner && $sqlDateFilt) {
    $sql = $sqlSelect . $where . $sqlDateFilt . $and . $sqlWinner . $and . $sqlName . $sqlOrder;
} elseif ($sqlWinner && $sqlDateFilt) {
    $sql = $sqlSelect . $where . $sqlDateFilt . $and . $sqlWinner . $sqlOrder;
} elseif ($sqlName && $sqlDateFilt) {
    $sql = $sqlSelect . $where . $sqlDateFilt . $and . $sqlName . $sqlOrder;
} elseif ($sqlName && $sqlWinner) {
    $sql = $sqlSelect . $where . $sqlWinner . $and . $sqlName . $sqlOrder;
} elseif ($sqlWinner) {
    $sql = $sqlSelect . $where . $sqlWinner . $sqlOrder;
} elseif ($sqlName) {
    $sql = $sqlSelect . $where . $sqlName . $sqlOrder;
} elseif ($sqlDateFilt) {
    $sql = $sqlSelect . $where . $sqlDateFilt . $sqlOrder;
} else {
    $sql = $sqlSelect . $sqlOrder;
}

$result = $pdo->query($sql);
//print_r($sql);
?>

<form method='post' action='index.php'>
    <fieldset>
        <legend>Filter the Chess Matches</legend>
        <label>Enter player name:</label>
        <input type='text' name='playerName' value='<?= $playerName ?>'/>
        </br>
        <label>Input date range mm/dd/yyyy:</label>
        <input type='text' name='date1' value='<?= $date1 ?>'/>
        <input type='text' name='date2' value='<?= $date2 ?>'/>
        </br>
        <label>Select Winner:</label>
        <select name='winner'>
        <?php
            if ($winner === 'any') {$s = "selected";} else {$s = "";}
                echo "<option value='any' $s>Any</option>";
            if ($winner === '1') {$s = "selected";} else {$s = "";}
                echo "<option value='1' $s>Player 1 Win</option>";
            if ($winner === '2') {$s = "selected";} else {$s = "";}
                echo "<option value='2' $s>Player 2 Win</option>";
            if ($winner === 'D') {$s = "selected";} else {$s = "";}
                echo "<option value='D' $s>Draw</option>";
        ?>
        </select>
        </br>
        <input type='submit' name='Filter'/>
    </fieldset>    
</form>
    


<table  border ='1'>
    <tr>
        <th>Date</th>
        <th>Player 1</th>
        <th>Player 2</th>
        <th>Result</th>
        <th>ECO Code</th>    
        <th>More Info</th>
    </tr>

    <?php
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $matchDate = $row['DATE_FORMAT(matchDate, \'%m/%d/%Y\')'];
        $player1 = $row['player1'];
        $player2 = $row['player2'];
        $game_result = $row['result'];
        $eco = $row['eco'];
        $id = $row['id'];


        echo "<tr>";
        echo "<td>" . $matchDate . "</td>" .
        "<td>" . $player1 . "</td>" .
        "<td>" . $player2 . "</td>" .
        "<td>" . $game_result . "</td>" .
        "<td>" . $eco . "</td>" .
        "<td> <a href=\"details.php?id=" . $id . "\">Match Details</a> </td>";
        echo "</tr>";
    }
    ?>
</table>