<?php
session_start();
include_once 'dbconnect.php';
include 'style.css';
?>

<!doctype html>
<html>
<meta charset = "UTF8">
<head>
<title>chess club</title>
</head>

<body>
<?php
include 'header.php';
// this long sql query generates leaderboard
$sql = "

SELECT players.nickname,
       wins
FROM
  (SELECT user_id,
          w.wins
   FROM
     (SELECT *
      FROM
        (SELECT winner_id AS user_id,
                l_count + w_count AS white_matches
         FROM
           (SELECT *
            FROM
              (SELECT `loser_id`,
                      COUNT(*) AS l_count
               FROM
                 (SELECT *
                  FROM matches
                  WHERE winner_color = 'B' ) AS a
               GROUP BY loser_id) AS c
            JOIN
              (SELECT `winner_id`,
                      COUNT(*) AS w_count
               FROM
                 (SELECT *
                  FROM matches
                  WHERE winner_color = 'W' ) AS b
               GROUP BY winner_id) AS d ON c.loser_id = d.winner_id) AS e) AS white_matches
      WHERE white_matches > 9) AS z
   INNER JOIN
     (SELECT *
      FROM
        (SELECT winner_id,
                l_count + w_count AS black_matches
         FROM
           (SELECT *
            FROM
              (SELECT `loser_id`,
                      COUNT(*) AS l_count
               FROM
                 (SELECT *
                  FROM matches
                  WHERE winner_color = 'W' ) AS a
               GROUP BY loser_id) AS c
            JOIN
              (SELECT `winner_id`,
                      COUNT(*) AS w_count
               FROM
                 (SELECT *
                  FROM matches
                  WHERE winner_color = 'B' ) AS b
               GROUP BY winner_id) AS d ON c.loser_id = d.winner_id) AS e) AS black_matches
      WHERE black_matches > 9) AS x ON z.user_id = x.winner_id
   INNER JOIN
     (SELECT `winner_id`,
             COUNT(winner_id) AS wins
      FROM `matches`
      GROUP BY winner_id) AS w ON user_id = w.winner_id) AS valid_players
INNER JOIN players ON valid_players.user_id = players.player_id
ORDER BY `valid_players`.`wins` DESC
LIMIT 10;

";
$result = $conn->query($sql);

if ($result->num_rows > 0)
{
    // print leaderboard
    $index = 0;

    echo "<table class='tg' style='undefined;table-layout: fixed; width: 246px'>";
    echo "<tr><td>" . "place" . "</td><td>" . "nickname" . "</td><td>" . "wins" . "</td></tr>";
    while ($row = $result->fetch_assoc())
    {
        $index++;
        echo "<tr><td>" . $index . "</td><td>" . $row["nickname"] . "</td><td>" . $row["wins"] . "</td></tr>";
    }
    echo "</table>";
}
else
{
    echo "0 results";
}
//get shortest game
echo "<br> Shortest game ever: <br>";
$sql = "
SELECT winner,
       nickname AS loser,
       moves,
       match_date
FROM
  (SELECT shortest_match.loser_id,
          shortest_match.moves,
          shortest_match.match_date,
          players.nickname AS winner
   FROM
     (SELECT `winner_id`,
             `loser_id`,
             `moves`,
             `match_date`
      FROM `matches`
      ORDER BY moves
      LIMIT 1) AS shortest_match
   INNER JOIN players ON winner_id = players.player_id) AS a
INNER JOIN players ON players.player_id = a.loser_id;
";
//print shortest game
$result = $conn->query($sql);
$result = $result->fetch_assoc();
echo "winner: ", $result["winner"], "<br>loser: ", $result["loser"], "<br>number of moves: ", $result["moves"], "<br>match date: ", $result["match_date"], "<br><br>";
//get longest game
echo "Longest game ever: <br>";
$sql = "
SELECT winner,
       nickname AS loser,
       moves,
       match_date
FROM
  (SELECT shortest_match.loser_id,
          shortest_match.moves,
          shortest_match.match_date,
          players.nickname AS winner
   FROM
     (SELECT `winner_id`,
             `loser_id`,
             `moves`,
             `match_date`
      FROM `matches`
      ORDER BY moves DESC
      LIMIT 1) AS shortest_match
   INNER JOIN players ON winner_id = players.player_id) AS a
INNER JOIN players ON players.player_id = a.loser_id;
";
//print longest game
$result = $conn->query($sql);
$result = $result->fetch_assoc();
echo "winner: ", $result["winner"], "<br>loser: ", $result["loser"], "<br>number of moves: ", $result["moves"], "<br>match date: ", $result["match_date"];
?>

</body>
</html>
