<form >
        <input type="text" name="user" placeholder="username">
        <button type="submit" name="login" value="login"> login </button>
        <button type="submit" name="logoff" value="logoff"> logoff </button>
</form>

<?php
$uservalid = false;
$user = NULL;
if (isset($_GET["logoff"]))
{
    $_SESSION["user"] = NULL; //setting user variable as null works as log off
}
if (isset($_GET["login"]))
{
    $user = $_GET["user"]; //check if user was not deleted (eplained in interface.php line 140)
    if (sqlgetvalue("SELECT COUNT(1) AS do_exist FROM players WHERE nickname = '$user';", 'do_exist') == 1 and sqlgetvalue("SELECT `player_name` FROM `players` WHERE nickname = '$user';", 'player_name') == 'deleted')
    {
        echo "You can not log in as deleted user";
    }
    //check if user exists
    elseif (sqlgetvalue("SELECT COUNT(1) AS do_exist FROM players WHERE nickname = '$user';", 'do_exist') == 1)
    {
        $_SESSION["uservalid"] = true;
        $_SESSION["user"] = $user;
    }
    else
    {
        echo "User does not exist. ";
    }
}
else
{
}
if ($_SESSION["user"] == NULL) 
{
    echo "No one is logged in.";
}
?>
<hr>
