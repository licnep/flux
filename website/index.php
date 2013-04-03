<?php
$head='';
$body='';

ob_start(); //i call ob_start, so that instead of outputting everything directly, we can buffer the output and pass it to the create_page function
?>
<h1 style="text-align:center">FluxHub is the best way to accept donations.</h1>
<div class="container" style="text-align:center">
    <button class="btn success">Join Now</button>
</div>
<br/>
<br/>
<div class="container">
    <div class="well span7">
        <h3>100% Free</h3>
        <p>
            No transaction fees*, no hidden fees.<br/>
            Free, forever.
        </p>
    </div>
    <div class="well span7">
        <h3>Open source</h3>
        <p>
            Build anything on top of our open API.<br/><br/>
        </p>
    </div>
</div>
<div class="container">
    <div class="well span7">
        <h3>Split Donations</h3>
        <p>
            Set their share, and split donations with your collaborators.<br/>
            Automatically and for free.
        </p>
    </div>
        <div class="well span7">
        <h3>Redirect</h3>
        <p>
            Redirect the donations you receive to other projects you like.<br/><br/>
        </p>
    </div>
</div>
<?php
$body .= ob_get_clean();

//START TOOLTIPS/POPOVERS (we attach the required js files to the header)
ob_start(); ?>
<script type="text/javascript" src="css/bootstrap/js/bootstrap-twipsy.js"></script>
<script type="text/javascript" src="css/bootstrap/js/bootstrap-popover.js"></script>
<?php
$head .= ob_get_clean();
//END TOOLTIPS/POPOVERS

require_once(dirname(__FILE__).'/scripts/page_creator.php');
$html = create_page($body,$head);
echo $html;
?>