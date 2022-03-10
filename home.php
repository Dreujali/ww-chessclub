<?php
session_start();
include_once 'dbconnect.php';

?>

<!doctype html>
<html>
<meta charset = "UTF8">
<head>
<title>chess club</title>
</head>

<body>
<?php
include 'style.css';
include 'header.php';
//nulls every variable, so the table would not write anything if no one is logged in
$wins = NULL;
$loses = NULL;
$whitewins = NULL;
$blackwins = NULL;
$bestgame_oponent = NULL;
$bestgame_moves = NULL;
$bestgame_color = NULL;
$bestgame_date = NULL;
$user = $_SESSION["user"];
if ($user != NULL)
{ 
    //function sqlgetvalue(sqlquery, coulumn) is defined in dbconnect.php; is for getting data for sql database; and can only return one value
    //sets every variable as sql value to display them in table
    $wins = sqlgetvalue("SELECT COUNT(winner_id) as wins FROM matches WHERE winner_id = (
        SELECT player_id FROM players WHERE nickname = '$user'
        );", "wins");
    $loses = sqlgetvalue("SELECT COUNT(loser_id) as loses FROM matches WHERE loser_id = (
        SELECT player_id FROM players WHERE nickname = '$user'
        );", "loses");
    $whitewins = sqlgetvalue("SELECT COUNT(winner_id) AS white_win_count FROM matches WHERE 
    winner_id = (SELECT player_id FROM players WHERE nickname = '$user') AND winner_color = 'W';", 'white_win_count');
    $blackwins = $wins - $whitewins;
    $bestgame = sqlgetarray("SELECT b.moves, b.match_date, b.winner_color, players.nickname FROM
    (SELECT a.moves, a.match_date, a.loser_id, a.winner_color FROM 
        (SELECT * FROM matches WHERE winner_id = (SELECT player_id FROM players WHERE nickname = '$user')) 
    AS a ORDER BY a.moves LIMIT 1) as b
    JOIN players ON players.player_id = b.loser_id;");

    if ($wins > 0) //to avoid errors when someone have not played any game yet
    {
        $bestgame_oponent = $bestgame[3];
        $bestgame_moves = $bestgame[0];
        $bestgame_date = $bestgame[1];
        $bestgame_color = $bestgame[2];
    }

}

?>


<table class="tg" style="undefined;table-layout: fixed; width: 248px">
<colgroup>
<col style="width: 124px">
<col style="width: 124px">
</colgroup>
<thead>
  <tr>
    <th class="tg-v0nz" colspan="2"> <?php echo $_SESSION["user"] ?> </th>
  </tr>
</thead>
<tbody>
  <tr>
    <td class="tg-v0nz">wins<br> <?php echo $wins ?> </td>
    <td class="tg-baqh">loses<br><?php echo $loses ?></td>
  </tr>
  <tr>
    <td class="tg-baqh">wins as white<br><?php echo $whitewins ?></td>
    <td class="tg-baqh">wins as black<br><?php echo $blackwins ?></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2"><?php
if ($bestgame_oponent)
{
    echo "Your best game was ", $bestgame_date, " against ", $bestgame_oponent, ", you won with ", $bestgame_moves, " moves and played with ";
    if ($bestgame_color == 'W')
    {
        echo "white figures";
    }
    else
    {
        echo "black figures";
    }
}


?></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="2">
<?php
$sql = "SELECT `player_name`, `player_email`, `player_phone`, `date_joined` FROM `players` WHERE nickname = '$user';";

$result = $conn->query($sql);
$result = $result->fetch_assoc();
echo "Name: ", $result["player_name"], "<br>Email: ", $result["player_email"], "<br>Phone: ", $result["player_phone"], "<br>Joined: ", $result["date_joined"];

?></td>
  </tr>
</tbody>
</table>   
<?php
echo "<br>match log: <br>";

$sql = "
SELECT winner_color,
       moves,
       winner,
       match_date,
       players.nickname AS loser
FROM
  (SELECT loser_id,
          winner_color,
          moves,
          match_date,
          players.nickname AS winner
   FROM
     (SELECT winner_id,
             loser_id,
             winner_color,
             moves,
             match_date
      FROM `matches`
      WHERE winner_id =
          (SELECT player_id
           FROM players
           WHERE nickname = '$user')
        OR loser_id =
          (SELECT player_id
           FROM players
           WHERE nickname = '$user') ) AS a
   INNER JOIN players ON players.player_id = winner_id) AS b
INNER JOIN players ON players.player_id = loser_id
ORDER BY match_date DESC;
";

$result = $conn->query($sql);

if ($result->num_rows > 0)
{
    // output data of each row
    echo "<table class='tg' style='undefined;table-layout: fixed; width: 700px'>";
    echo "<tr><td>" . "result" . "</td><td>" . "oponent" . "</td><td>" . "your figures" . "</td><td>" . "number of moves" . "</td><td>" . "match date" . "</td></tr>";
    while ($row = $result->fetch_assoc())
    {
        echo "<tr><td>";
        if ($row["winner"] == $user)
        {
            echo "WIN";
        }
        else
        {
            echo "LOST";
        }
        echo "</td><td>";
        if ($row["winner"] == $user)
        {
            echo $row["loser"];
        }
        else
        {
            echo $row["winner"];
        }
        echo "</td><td>";
        if ($row["winner"] == $user)
        {
            if ($row["winner_color"] == 'W')
            {
                echo "White";
            }
            else
            {
                echo "Black";
            }
        }
        else
        {
            if ($row["winner_color"] == 'W')
            {
                echo "Black";
            }
            else
            {
                echo "White";
            }
        }

        echo "</td><td>";
        echo $row["moves"];
        echo "</td><td>";
        echo $row["match_date"];
        echo "</td><tr>";
    }
    echo "</table>";
}
else
{
    echo "0 results";
}
?>
</body>
</html>
