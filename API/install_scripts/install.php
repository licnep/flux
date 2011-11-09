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
<?php
#FOR DEBUG: (enable error reporting)
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

//when the user first opens the page we show a form where he must insert the database credential to install:
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
    $db_dbname="testfluxAPI";
    //put all the queries we want to execute in an array
    $queries = array(
        "DROP DATABASE IF EXISTS $db_dbname",
        
        "CREATE DATABASE $db_dbname",
        
        "USE $db_dbname",
		/*the following line is a workaround to avoid error when dropping
		  a user that doesn't exist. See: http://bugs.mysql.com/bug.php?id=19166 */
		"GRANT USAGE ON *.* TO 'fluxAPIuser'@'localhost';",
        
        "DROP USER 'fluxAPIuser'@'localhost'",
        
        "CREATE USER 'fluxAPIuser'@'localhost' IDENTIFIED BY 'password'",
        
        "GRANT ALL ON $db_dbname.* TO 'fluxAPIuser'@'localhost'",
        
        "CREATE TABLE users(
        user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        username VARCHAR(32),
        email VARCHAR(32),
        hash VARCHAR(32),
        confirmed BOOL DEFAULT 0,
        temp BOOL DEFAULT 0,
        plaintextPWD VARCHAR(32),
        created TIMESTAMP DEFAULT NOW(),
        PRIMARY KEY (user_id),
        UNIQUE (username)
        ) ENGINE = InnoDB",
        
        "CREATE TABLE fluxes(
        flux_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(32),
        owner INT UNSIGNED NOT NULL,
        description TEXT(100),
        money DECIMAL(7,2)  NOT NULL DEFAULT 0,
        last_update TIMESTAMP DEFAULT NOW(),
        userflux INT DEFAULT 0,
        PRIMARY KEY (flux_id)
        ) ENGINE = InnoDB",
        
        "CREATE TABLE routing(
        flux_from_id INT UNSIGNED NOT NULL,
        flux_to_id INT UNSIGNED NOT NULL,
        share INT UNSIGNED NOT NULL DEFAULT 0,
        INDEX (flux_from_id),
        INDEX (flux_to_id),
        PRIMARY KEY (flux_from_id,flux_to_id)
        ) ENGINE = InnoDB;",

        /*i put a timestamp so we could eliminate unused transaction keys after a while*/
        /*STATUS is 0 when the key is generated, 1 when the transaction is completed, 2 when it has failed (but the key can't be used again)*/
        /*TYPE is the type of transaction. 0 for donation, 1 for withdrawal*/
        "CREATE TABLE transactions(
        transaction_id VARCHAR(36),
        user_id INT UNSIGNED NOT NULL,
        pool_id INT UNSIGNED NOT NULL,
        flux_to_id INT UNSIGNED NOT NULL,
        amount DECIMAL(7,2),
        amount_readable VARCHAR(32),
        type INT NOT NULL,
        status INT DEFAULT 0,
        timestamp TIMESTAMP DEFAULT NOW(),
        PRIMARY KEY (transaction_id)
        ) ENGINE = InnoDB;",

        "CREATE TABLE pools(
        pool_id INT UNSIGNED NOT NULL,
        public_key BLOB NOT NULL,
        ack_url BLOB NOT NULL,
        total DECIMAL(7,2) NOT NULL DEFAULT 0,
        PRIMARY KEY (pool_id)
        ) ENGINE = InnoDB;",

        "INSERT INTO pools SET pool_id=1,ack_url='www.google.com',public_key=
'-----BEGIN PUBLIC KEY-----
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBANDiE2+Xi/WnO+s120NiiJhNyIButVu6
zxqlVzz0wy2j4kQVUC4ZRZD80IY+4wIiX2YxKBZKGnd2TtPkcJ/ljkUCAwEAAQ==
-----END PUBLIC KEY-----'"
        
    );
    /*we append the queries to create the procedures that move the money around*/
    include("add_procedure_creation_queries.php");
    foreach ($queries as $query) {
        $result = mysql_query($query,$db);
        echo '<div class="'.($result?"success":"fail").'"><small>'.$query.'</small> : <b>'.($result?"SUCCESS":"FAIL: ".mysql_error()).'</b></div>';
    }    
}

function update_LocalSettings($username,$password) {
    $data = "<?php\n";
    $data .= '$C_username = "fluxAPIuser";'."\n";
    $data .= '$C_password = "password";'."\n";
    $data .= '$C_API_base_url = "http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."/../../\";\n";
    $data .= "?>";
    $result = file_put_contents("../LocalSettings.php",$data);
    if ($result) {
        echo "<div class=\"success\">localsettings.php succesfully saved</div>";
    } else {
        echo "<div class=\"fail\">ERROR while saving localsettings.php!!</div>";
    }
    echo "NOW remember to open /API/javascriptAPI/fluxAPI.js AND change the API base url (default is localhost)";
}

?>
