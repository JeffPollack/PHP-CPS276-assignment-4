<?php
include ('db_connect.php');

$id = intval($_GET['id']);

$sql = "SELECT id, event, site, round, player1Elo, player2Elo, opening, moves,"
        . " player1, player2, result, eco, DATE_FORMAT(matchDate, '%m/%d/%Y')"
        . " FROM matches WHERE id=".$id;

$result = $pdo->query($sql);

$row = $result->fetch(PDO::FETCH_ASSOC)
?> 
<a href="index.php">Back</a>
<br>
<table  border ='1'>
    <tr>
        <th>Date</th>
        <th>Player 1 and Elo</th>
        <th>Player 2 and Elo</th>
        <th>Result</th>
        <th>Opening Play and ECO</th>    
    </tr>

    <?php
    $matchDate = $row['DATE_FORMAT(matchDate, \'%m/%d/%Y\')'];

    $matchResult = array(
        '1' => 'Player 1 Win',
        '2' => 'Player 2 Win',
        'D' => 'Draw'
    );

    echo "<tr>";
    echo "<td>" . $matchDate . "</td>" .
    "<td>" . $row['player1'] . ": " . $row['player1Elo'] . "</td>" .
    "<td>" . $row['player2'] . ": " . $row['player2Elo'] . "</td>" .
    "<td>" . $matchResult[$row['result']] . "</td>" .
    "<td>" . $row['opening'] . ": " . $row['eco'] . "</td>";
    echo "</tr>";
    ?></table>

<table  border ='1'>
    <tr>
        <th>Moves</th>
    </tr><?php
    echo"<tr>";
    echo"<td>" . $row['moves'] . "</td>";
    echo "</tr>";
    ?>
</table>