<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<style type= "text/css">
<!--
div {
    padding:3px;
    margin: 3px;
}
.success {
    background-color:#5F5;
    border: 1px solid #0F0;
}
.fail {
    background-color:#F55;
    border: 1px solid #F00;
}
-->
</style>
<link rel="stylesheet/less" type="text/css" href="../../../website/css/bootstrap/lib/bootstrap.less">
<script src="../../../website/css/bootstrap/less.js" type="text/javascript"></script>
<div class="container">
<?php
#FOR DEBUG: (enable error reporting)
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

//when the user first opens the page we show a form where he must insert the database credential to install:
if (!isset($_GET['user'])) {
    ?>
    <form method="GET">
        <h2>1) Connect with the API</h2>
        <p>Insert the API base url (ending in API/)</p>
        <input type="text" class="xxlarge" name="APIurl" value="http://<?=$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']?>/../../../../API/">
        <h2>2) Install the database:</h2>
    <p>Please insert the database credential to install the database:</p>
        <p>Username: <input type="text" name="user" value="" /></p>
        <p>Password: <input type="text" name="password" value="" /></p>
        <input type="submit" name="submit" value="Create"/>
    </form>
    <?php
}
else {
    //if the user already inputted some data:
    try_to_create_tables($_GET['user'],$_GET['password']);
    update_LocalSettings($_GET['user'],$_GET['password']);
}

function try_to_create_tables($username,$password) {
    $db = mysql_pconnect('localhost',$username,$password);

    if(!is_resource($db)) {
		//TODO ERROR!!! DO SOMETHING USEFUL
		die("db opening error");
	} else {
        install_tables($db);
	}
}

function install_tables($db) {
    $db_dbname="paypalPool";
    //put all the queries we want to execute in an array
    $queries = array(
        "DROP DATABASE IF EXISTS $db_dbname",
        
        "CREATE DATABASE $db_dbname",
        
        "USE $db_dbname",
		/*the following line is a workaround to avoid error when dropping
		  a user that doesn't exist. See: http://bugs.mysql.com/bug.php?id=19166 */
		"GRANT USAGE ON *.* TO 'poolUser'@'localhost';",
        
        "DROP USER 'poolUser'@'localhost'",
        
        "CREATE USER 'poolUser'@'localhost' IDENTIFIED BY 'password'",
        
        "GRANT ALL ON $db_dbname.* TO 'poolUser'@'localhost'",
        
        "CREATE TABLE transactions(
        transaction_id VARCHAR(36),
        amount DECIMAL(7,2),
        type INTEGER NOT NULL,
		ack BOOL DEFAULT 0,
        successful BOOL DEFAULT 1,
        PRIMARY KEY (transaction_id)
        ) ENGINE = InnoDB"
    );
    foreach ($queries as $query) {
        $result = mysql_query($query,$db);
        echo '<div class="'.($result?"success":"fail").'"><small>'.$query.'</small> : <b>'.($result?"SUCCESS":"FAIL: ".mysql_error()).'</b></div>';
    }    
}

function update_LocalSettings($username,$password) {
    $data = "<?php\n";
    $data .= '$C_username = "poolUser";'."\n";
    $data .= '$C_password = "password";'."\n";
    $data .= '$C_API_base_url = "'.$_GET['APIurl']."\";\n";
    $data .= "?>";
    $result = file_put_contents("../internal/LocalSettings.php",$data);
    if ($result) {
        echo '<div class="success">localsettings.php succesfully saved</div>';
    } else {
        echo '<div class="fail">ERROR while saving localsettings.php!!</div>';
    }
}

?>
</div>