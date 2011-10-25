<!--[DROP] Code for the dropdowns-->
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
            <a class="brand">Fluxhub</a>
            <ul class="nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Community</a></li>
                <li><a href="#">Development</a></li>
            </ul>
            <ul class="nav secondary-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <?=$_SESSION['username']?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="scripts/logout.php">Logout</a></li>
                    </ul> 
                </li>
            </ul>
        </div>
    </div>
</div>
<!--[/TOPBAR]-->
<!-- if he's a temporary user we show the warning bar-->
<?php
if ($_SESSION['temp']==1) {
?>
<div class="warning">
    Welcome <?=$_SESSION['username']?>, <a href="register.php">click here</a> to change your nick, or <a href="login.php">login</a> if you already have an account.
</div>
<?php } ?>

