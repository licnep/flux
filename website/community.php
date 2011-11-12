<?php
$head='';
$body='';

ob_start(); //i call ob_start, so that instead of outputting everything directly, we can buffer the output and pass it to the create_page function
?>
<div class="well">
    <div class="wellTitleBar">
        <div class="wellTitle">IRC:</div>
    </div>
    <p><b>Server</b>: irc.rizon.net</p>
    <p><b>Channel</b>: #/g/flux</p>
    <p>For the losers who don't have an IRC client: <a target="about:blank" href="http://qchat.rizon.net/?channels=/g/flux&uio=Nz10cnVlJjk9ODYmMTY9ZmFsc2Ud1">WebIrc</a></p>
</div>
<div class="well">
    <div class="wellTitleBar">
        <div class="wellTitle">Mailing list:</div>
    </div>
    <p><a href="http://groups.google.com/group/flux-list">Flux project on Google Groups</a></p>
</div>
<?php
$body .= ob_get_clean();
/*
 * END 1) list of fluxes ===============================================================
 */

require_once(dirname(__FILE__).'/scripts/page_creator.php');
$html = create_page($body,$head);
echo $html;
?>