<?php
/**
 * This functions adds all the common bells and whistles to our webpages.
 * To create a webpage, put all the stuff you want in the webpage body in $body, the stuff you want in the head in $head.
 * 
 * For example:
 * $body = '<b>a webpage!!!!</b>';
 * $head = '<script type="text/javascript" src="asd.js"></script>'
 * echo create_page($body,$head);
 * 
 * 
 * @return a string containing the complete webpage
 * 
 * Standard included stuff:
 * -top bar, login check
 * -jquery
 * -bootstrap, bootstrap-modal
 * -fluxAPI
 * 
 * 
 */

function create_page($body,$head="") {
    ob_start(); //we call this so that the output is kept in a buffer, because we want to return it as a string, not output it
    ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
    <?php include(dirname(__FILE__).'../../include/phpTOP.php');?>
    <html>
    <head>
        <!--[BOOTSTRAP] the bootstrap CSS style, then we launch less.js to compile it-->
        <link rel="stylesheet/less" type="text/css" href="css/bootstrap/lib/bootstrap.less">
        <script src="css/bootstrap/less.js" type="text/javascript"></script>
        <!--[/BOOTSTRAP]-->
        <script type="text/javascript" src="include/jquery/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="../API/javascriptAPI/fluxAPI.js"></script>
        <!--[MODAL POPUP STUFF]-->
        <script src="css/bootstrap/js/bootstrap-modal.js" type="text/javascript"></script>
        <script src="include/FW.js" type="text/javascript"></script>
        <!--[/MODAL POPUP STUFF]-->
        <?=$head;?>
    </head>
    <body>
    <?php include(dirname(__FILE__).'../../include/topBar.php');?>
        <div class="container">
            <div class="content">
                <?php
                    //if he's a temporary user we show the warning bar
                    if ($_SESSION['temp']==1) {
                ?>
                    <div class="alert-message warning">
                        WARNING, logged in as Guest, either <a onclick="$FW.popupUrl('include/popups/register.php')" href="#">register</a> or <a onclick="$FW.popupUrl('include/popups/login.php')" href="#">login</a> if you want to save your changes.
                    </div>
                <?php }
                    echo $body;
                ?>
            </div>
        </div><!--container-->
    </body>
    <?
    return ob_get_clean();
}

?>
