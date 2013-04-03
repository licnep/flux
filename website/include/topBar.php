<?php
function generate_topBar($pageID) {
    ob_start();
    ?>
    <!--[DROP] Code for the dropdown menus-->
    <script type="text/javascript" src="css/bootstrap/js/bootstrap-dropdown.js"></script>
    <script type="text/javascript">
        //when the document is ready we initialize the dropdowns (eg. the clickable username on the top right)
        $(document).ready(function () {
            $('.dropdown').dropdown();
        });
    </script>
    <!--[/DROP]-->
    <!--[TOPBAR]-->
    <div class="topbar">
        <div class="topbar-inner">
            <div class="container">
                <a class="brand" href="index.php">Fluxhub</a>
                <ul class="nav">
                    <li class="<?=($pageID=='account_home')?'active':'' ?>"><a href="account_home.php">My Fluxes</a></li>
                    <li class="<?=($pageID=='develop')?'active':'' ?>"><a href="develop.php">HackThisSite</a></li>
                </ul>
                <ul class="nav secondary-nav">
                    <?php  if ($_SESSION['logged']) { ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">
                                <?=$_SESSION['username']?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="account.php">My account</a></li>
                                <li><a href="scripts/logout.php?goto=<?php echo urlencode('http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]); ?>">Logout</a></li>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <li><a href="#"  onclick="$FW.popupUrl('include/popups/login.php')">Login</a></li>
                        <li><a href="#" onclick="$FW.popupUrl('include/popups/register.php')">Register</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <!--[/TOPBAR]-->
    <?php
    return ob_get_clean();
}
