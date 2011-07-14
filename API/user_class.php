<?
function session_defaults() {
	$_SESSION['logged'] = false;
	$_SESSION['uid'] = 0;
	$_SESSION['username'] = '';
	$_SESSION['cookie'] = 0;
}

function user_logout() {
        //forget all session things..
        session_defaults();
        //remove the cookie:
        setcookie('mtwebLogin', "", time() - 100, '/');
}

class user {
	var $dblogin = null; // DB pointer

	function user(&$dblogin) {
		$this->dblogin = $dblogin;

		if ($_SESSION['logged']) {
			$this->_checkSession();
		} elseif ( isset($_COOKIE['mtwebLogin']) ) {
			$this->_checkRememberedCookie($_COOKIE['mtwebLogin']);
		}
	}

	function _CheckLogin ($given_email, $given_plain_password, $remember) {

            //  The plain password is used to generate the hash that corresponds to it and is stored on the database
            $inserted_hash = md5(md5($given_plain_password).md5($given_email));

            $query = "SELECT * FROM users WHERE " .
                    "email = '".mysql_real_escape_string($given_email)."' AND " .
                    "password = '$inserted_hash'";
            $result = mysql_query($query, $this->dblogin);

            if (!$result) {
                //TODO something
                die ('error, query:' .$query.' -- '. mysql_error());
            }
            else {
                if (mysql_numrows($result) == 1) {
                        $this->_setSession($result, $remember,1);
                        return true;
                } else {
                        user_logout();
                        return false;
                }
            }
	}

	function _setSession(&$values_from_logindb, $remember = true, $init = true) {

		$row_user = mysql_fetch_array($values_from_logindb);

		$_SESSION['uid'] = $row_user['user_id'];
		$_SESSION['username'] = $row_user['username'];
		$_SESSION['cookie'] = $row_user['cookie'];
		$_SESSION['logged'] = true;

		if ($remember) {
			$this->updateCookie($row_user['cookie']);
		}

		if ($init) {
                        //metto nel database le informazioni di questa sessione
			$query = "UPDATE account SET session = '".mysql_real_escape_string(session_id())."', ".
                                "ip = '".$ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."' WHERE " .
				"id = '".mysql_real_escape_string($_SESSION['uid'])."'";
			$result = mysql_query($query, $this->dblogin);
			if(!result) {
				//loggare l'errore correttamenre
				echo 'non sono riuscito a fare l\'update della sessione';
                        }

		}
	}

	function _checkSession() {
        //i check the session parameters against the ones in the DB
        //if there's something wrong -> logout
		$uid = mysql_real_escape_string($_SESSION['uid']);
		$cookie = mysql_real_escape_string($_SESSION['cookie']);
		$session = mysql_real_escape_string(session_id());
		$ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);

		$query = "SELECT * FROM account WHERE " .
			"(id = '$uid') AND (cookie = '$cookie') AND " .
			"(session = '$session') AND (ip = '$ip')";

		$result = mysql_query($query, $this->dblogin);

		if ($result) {
			//echo 'sessione ok';
			//[INUTILE CREDO]$this->_setSession($result, true, false);
		} else {
			user_logout();
			echo 'check sessione fallito, query:'. $query;
		}
	}

	function updateCookie($cookie) {
		$_SESSION['cookie'] = $cookie;
                $serial = serialize(array($_SESSION['uid'], $cookie) );
                //		  ( cooki-name , cookie , expires in a year, where the cookie is available(entire domain))
                //              |           |              |          |
                setcookie('mtwebLogin', $serial, time() + 31104000, '/');
	}

	function _checkRememberedCookie($serialCook) {

            //retrieves $username and $cookie from the cookie
		list($uid, $cookie) = unserialize(stripslashes($serialCook));
		if (!$uid or !$cookie) {
                    return;
                }

		$uid = mysql_real_escape_string($uid);
		$cookie = mysql_real_escape_string($cookie);

		$query = "SELECT * FROM account WHERE " .
			"(id = '$uid') AND (cookie = '$cookie')";

		$result = mysql_query($query);

		if ($result) {
			$this->_setSession($result, true, true);
		}
                else {
                    echo 'la query :'.$query.'e fallita, perche:'.mysql_error();
                }
	}

}
?>
