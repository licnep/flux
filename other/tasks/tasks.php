<html>
<head>
<link href="tasks.css" rel="stylesheet" type="text/css"/>	
</head>
<body>
<h1>Tasks:</h1>
<?php

#FOR DEBUG: (enable error reporting)
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

$db_name = "fluxTasks";
$username="fluxtask"; 
$password="fluxtask";

$db = mysql_pconnect('localhost',$username,$password);

if(!isset($db)) {
	//TODO ERROR!!! DO SOMETHING USEFUL
	die("db opening error");
}
//selecting the database:
$db_sel_result = mysql_select_db($db_name);
if ($db_sel_result==FALSE) {
	//TODO do something here
	die("db selecting error");
}

$query = "SELECT * FROM tasks ORDER BY date DESC";
$result = mysql_query($query,$db);
if(!$result) {//TODO something
	die("query failed, query: ".$query."\n error:".mysql_error());
}

while($row=mysql_fetch_array($result)) {
?>
<div class="taskDIV">
	<?=$row['description']?>
	<div class="taskDate">Created on: <?=$row['date']?></div>
</div>
<?php
}
?>
</body>
</html>
