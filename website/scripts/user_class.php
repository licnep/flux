<?
ini_set('display_errors', 'On');
error_reporting(E_ALL);

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
	
	function user() {
		if (isset($_SESSION['logged'])&&$_SESSION['logged']) {
			$this->_checkSession();
		} elseif ( isset($_COOKIE['mtwebLogin']) ) {
			$this->_checkRememberedCookie($_COOKIE['mtwebLogin']);
		} else {
                        $this->_login_as_temp_user();
			//user_logout();
		}
	}
        
        function _login_as_temp_user() {
                require_once(dirname(__FILE__).'/../../API/phpAPI/phpAPI.php');
                $json = flux_api_call("create_temp_user.php");
                $result = json_decode($json);
                if (!$result) {user_logout();return false;}
                $this->_setSession($result, true);
        }

	function _CheckLogin ($username, $hash, $remember) {
		require_once(dirname(__FILE__).'/../../API/phpAPI/phpAPI.php');
		$output = flux_api_call("check_login.php?username=".$username."&hash=".$hash);
		//done with the remote connection, now the result of the login operation is in $output
		$result = json_decode($output);
		if ($result=="false") {
			echo "ERROR";
            user_logout();
            return false;
		} else {
            $this->_setSession($result, $remember,1);
            return true;
		}
	}

	function _setSession($result, $remember = true, $init = true) {
                if (!$result) {
                    /*there's been some problem with the API call, we must logout*/
                    user_logout();
                    return;
                }
		$_SESSION['uid'] = $result->{'uid'};
		$_SESSION['username'] = $result->{'username'};
		$_SESSION['hash'] = $result->{'hash'};
                $_SESSION['temp'] = $result->{'temp'};
		$_SESSION['logged'] = true;
		
		if ($remember) {
			$this->updateCookie($result->{'hash'});
		}

		/*
		if ($init) {
            //put this session's info in the database
			$query = "UPDATE account SET session = '".mysql_real_escape_string(session_id())."', ".
                                "ip = '".$ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."' WHERE " .
				"id = '".mysql_real_escape_string($_SESSION['uid'])."'";
			$result = mysql_query($query, $this->dblogin);
			if(!result) {
				//loggare l'errore correttamenre
				echo 'non sono riuscito a fare l\'update della sessione';
           }
		}*/
	}

	function _checkSession() {

		//TODO!!!!!!!!!!!!!!!!!!
		//this has to be done remotely
		$username = $_SESSION['username'];
		$hash = $_SESSION['hash'];
		$this->_CheckLogin($username,$hash,false);

        //i check the session parameters against the ones in the DB
        //if there's something wrong -> logout
		/*$uid = mysql_real_escape_string($_SESSION['uid']);
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
		}*/
	}

	function updateCookie($cookie) {
		$_SESSION['cookie'] = $cookie;
        $serial = serialize(array($_SESSION['username'], $cookie) );
        //		  ( cooki-name , cookie , expires in a year, where the cookie is available(entire domain))
        //              |           |              |          |
        setcookie('mtwebLogin', $serial, time() + 31104000, '/');
	}

	function _checkRememberedCookie($serialCook) {

        //retrieves $username and $cookie from the cookie
		list($username, $cookie) = unserialize(stripslashes($serialCook));
		if (!$username or !$cookie) {
            return;
        }
		$this->_CheckLogin($username,$cookie,true);
	}

}
?>
