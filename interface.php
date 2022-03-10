<?php
session_start();
include_once 'dbconnect.php'
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



?>

<!-- edit user details, user must be logged in to edit his details, if you type anything in "username"
the username will be also changed -->
<form>
        <span>Edit user details:  <?php echo $_SESSION["user"] ?> <br> (empty fields stays unchanged)</span><br>
        <input type="text" name="user_nickname" placeholder="username"><br>
        <input type="text" name="user_name" placeholder="Name"><br>
        <input type="email" name="user_email" placeholder="email"><br>
        <input type="text" name="user_phone" placeholder="phone number" pattern="[0-9]{9}" title="9 digit phone number"><br>
        <button type="submit" name="sumbit_changes" value="submited"> submit changes </button>
</form>
<?php
if (isset($_GET["sumbit_changes"]))
{
    if ($_SESSION["user"] == NULL) //check if someone is logged in
    {
        echo "You must be logged in to edit your deatails";
    }
    else
    {

        $oldnickname = $_SESSION["user"]; // to edit database after nickname is changed

        if ($_GET["user_name"])
        {
            global $oldnickname;
            $newname = $_GET["user_name"];
            echo "User name updated to ", $_GET["user_name"], "<br>";
            #update name
            mysqli_query($conn, "UPDATE `players` SET `player_name`= '$newname' WHERE nickname = '$oldnickname';");
        }
        if ($_GET["user_email"])
        {
            global $oldnickname;
            $newemail = $_GET["user_email"];
            echo "User email updated to ", $_GET["user_email"], "<br>";
            #update email
            mysqli_query($conn, "UPDATE `players` SET `player_email`= '$newemail' WHERE nickname = '$oldnickname';");
        }
        if ($_GET["user_phone"])
        {
            global $oldnickname;
            $newphone = $_GET["user_phone"];
            echo "User phone updated to ", $_GET["user_phone"], "<br>";
            #update phone
            mysqli_query($conn, "UPDATE `players` SET `player_phone`= '$newphone' WHERE nickname = '$oldnickname';");
        }
        if ($_GET["user_nickname"]) // must be last
        {

            $newnickname = $_GET["user_nickname"];
            if ((sqlgetvalue("SELECT COUNT(1) AS do_exist FROM players WHERE nickname = '$newnickname';", 'do_exist') == 1) and $oldnickname != $newnickname)
            {
                echo "Someone with this username already exists, try another one", "<br>";

            }
            else
            {
                mysqli_query($conn, "UPDATE `players` SET `nickname`= '$newnickname' WHERE nickname = '$oldnickname';");
                $_SESSION["user"] = $newnickname;

                echo "Nickname updated to: ", $newnickname, "<br>";
             
            }
        }
    }

}
?>
<!-- register a new user, the date of registration is used in datbase as date user joined club  -->
<form style="margin-top: 20px;">
        <span>Register a new user</span><br>
        <input type="text" name="user_nickname" placeholder="username" required> *<br>
        <input type="text" name="user_name" placeholder="Name" required> *<br>
        <input type="email" name="user_email" placeholder="email" required> *<br>
        <input type="text" name="user_phone" placeholder="phone number" pattern="[0-9]{9}" title="9 digit phone number" required> *<br>
        <button type="submit" name="submit_new" value="submited"> register user </button>
</form>
<?php
if (isset($_GET["submit_new"]))
{
    $user_nickname = $_GET["user_nickname"]; 
    // check if user exists
    if (sqlgetvalue("SELECT COUNT(1) AS do_exist FROM players WHERE nickname = '$user_nickname';", 'do_exist') == 1) 
    {
        echo "user with this username already exists!", "<br>";
    }
    else
    {//insert new user to database
        echo "User registered";
        $user_nickname = $_GET["user_nickname"];
        $user_name = $_GET["user_name"];
        $user_email = $_GET["user_email"];
        $user_phone = $_GET["user_phone"];
        mysqli_query($conn, "INSERT INTO `players`(`nickname`, `player_name`, `player_email`, `player_phone`, date_joined) VALUES ('$user_nickname','$user_name','$user_email','$user_phone', CURRENT_TIMESTAMP )");

    }
}
?>

<form style="margin-top: 20px;">
        <span>Delete user</span><br>
        <input type="text" name="user_nickname" placeholder="username" required> *<br>
        <button type="submit" name="submit_deletion" value="submited"> delete user </button>
</form>
<?php
if (isset($_GET["submit_deletion"]))
{
    $username = $_GET["user_nickname"];
    if (sqlgetvalue("SELECT COUNT(1) AS do_exist FROM players WHERE nickname = '$username';", 'do_exist') == 1)
    {
        echo "User deleted";
        mysqli_query($conn, "UPDATE `players` SET `player_name`='deleted',`player_email`='deleted',`player_phone`='deleted' WHERE nickname = '$username';");

    }
    else
    {
        echo "User with this username does not exist";
    }
}
/*
    there was an option if delete all matches that this player had 
    or delete just his personal data email phone atc. 
    and keep the players 'shadow' profile.
    I decided the second variant is more fair for the other club members
*/
?>

<form style="margin-top: 20px;">
        <span>Upload new match results</span><br>
        <input type="text" name="winner_nickname" placeholder="winner nickaname" required> *<br>
        <input type="text" name="loser_nickname" placeholder="loser nickname" required> *<br>
        <input type="number" name="moves" placeholder="number of moves" required> *<br>
        <label for="winner_color">Winner color </label>
        <select name="winner_color" required> 
            <option value="B">Black</option>
            <option value="W">White</option>
        </select> *<br>
        
        <input type="date" name="match_date" placeholder="phone number" required> *<br>
        <button type="submit" name="upload_match" value="submited"> upload </button>
</form>
<?php
if (isset($_GET["upload_match"]))
{
    $loser_nickname = $_GET["loser_nickname"];
    $winner_nickname = $_GET["winner_nickname"];
    // check if both users exist
    if (sqlgetvalue("SELECT COUNT(1) AS do_exist FROM players WHERE nickname = '$winner_nickname' OR nickname = '$loser_nickname';", 'do_exist') == 2)
    {   // if they do exist insert new game into database
        $winner_id = sqlgetvalue("SELECT player_id FROM `players` WHERE nickname = '$winner_nickname';", 'player_id');
        $loser_id = sqlgetvalue("SELECT player_id FROM `players` WHERE nickname = '$loser_nickname';", 'player_id');
        $winner_color = $_GET["winner_color"];
        $match_date = $_GET["match_date"]; // i decided not to use current_timestamp so they can add matches later
        $moves = $_GET["moves"];
        mysqli_query($conn, "INSERT INTO `matches`(`winner_id`, `loser_id`, `winner_color`, `moves`, `match_date`) VALUES ('$winner_id','$loser_id','$winner_color','$moves','$match_date')");
    }
    else
    {
        echo "This player does not exists! ";
    }

}
?>
</body>
</html>
