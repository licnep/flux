<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<link rel="stylesheet/less" type="text/css" href="../website/css/bootstrap/lib/bootstrap.less">
<script src="../website/css/bootstrap/less.js" type="text/javascript"></script>
<div class="container">
<?php
if (!isset($_GET['user'])) {
    ?>
    <h2>== change permissions</h2>
    <p>
        The following files must be made editable:<br/>
        <pre>
            ./API/LocalSettings.php
            ./pools/lovePool/LocalSettings.php
            ./pools/paypalPool/internal/LocalSettings.php
            ./pools/paypalPool/internal/common/log.txt</pre>
        On linux, you can just run the <b>change_permissions.sh</b> script in this folder.
    </p>
    <h2>== install stuff:</h2>
    <ul>
        <li><a href="../API/install_scripts/install.php">Install the flux API</a></li>
        <li><a href="../pools/paypalPool/install_scripts/install.php">Install the paypal pool</a></li>
    </ul>
        <div class="alert-message error">Attention> if you have a previous flux installation, you will lose all your data.</div>
    <?php
}
else {
    require_once("../API/phpAPI/phpAPI.php");
    //if the user already inputted some data:
    install_API($_GET['user'],$_GET['password']);
    install_lovePool($_GET['user'],$_GET['password']);
    install_paypalPool($_GET['user'],$_GET['password']);
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

function install_paypalPool($user,$password) {
    echo "<h1>-------Paypal Pool INSTALLATION:-------</h1>";
    $url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."/../../pools/paypalPool/install_scripts/install.php?user=$user&password=$password";
    $result = get_webpage($url);
    echo $result;
}
?>
</div>
