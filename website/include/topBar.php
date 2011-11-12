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
            <a class="brand" href="account_home.php">Fluxhub</a>
            <ul class="nav">
                <li class="active"><a href="account_home.php">Home</a></li>
                <li><a href="community.php">Community</a></li>
                <li><a href="develop.php">Development</a></li>
            </ul>
            <ul class="nav secondary-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <?=$_SESSION['username']?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="account.php">My account</a></li>
                        <li><a href="scripts/logout.php?goto=<?php echo urlencode('http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]); ?>">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!--[/TOPBAR]-->

