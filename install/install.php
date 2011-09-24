<?php
if (!isset($_GET['user'])) {
    ?>
    <form method="GET">
    Please insert the database credential to install the database:
        <p>Username: <input type="text" name="user" value="" /></p>
        <p>Password: <input type="text" name="password" value="" /></p>
        <input type="submit" name="submit" value="Create"/>
    </form>
    <?php
}
else {
    require_once("../API/phpAPI/phpAPI.php");
    //if the user already inputted some data:
    install_API($_GET['user'],$_GET['password']);
    install_lovePool($_GET['user'],$_GET['password']);
}

function install_API($user,$password) {
    echo "<h1>----------API INSTALLATION:----------</h1>";
    $url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."/../../API/install_scripts/install.php?user=$user&password=$password";
    $result = get_webpage($url);
    echo $result;
}

function install_lovePool($user,$password) {
    echo "<h1>-------Love Pool INSTALLATION:-------</h1>";
    $url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."/../../pools/lovePool/install_scripts/install.php?user=$user&password=$password";
    $result = get_webpage($url);
    echo $result;
}
?>
