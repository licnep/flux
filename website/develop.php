<?php
$head='';
$body='';

ob_start(); //i call ob_start, so that instead of outputting everything directly, we can buffer the output and pass it to the create_page function
?>
<a href="http://github.com/sandboxer/flux"><img style="position: absolute; top: 40px; left: 0; border: 0;" src="https://a248.e.akamai.net/assets.github.com/img/6429057dfef9e98189338d22e7f6646c6694f032/687474703a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f6c6566745f6f72616e67655f6666373630302e706e67" alt="Fork me on GitHub"></a>
<div class="well">
    <div class="wellTitleBar">
        <div class="wellTitle">Minor edits:</div>
    </div>
    <p>
        If you can't use Git, or just want to make minor changes, you can edit a sandboxed version of the website directly from our <a href="http://flux.lolwut.net:3000">in-browser-IDE</a></p>
    <p>
        To notify the other devs about your changes use <a href="community.php">irc or the mailing list</a>.
    </p>
</div>
<div class="well">
    <div class="wellTitleBar">
        <div class="wellTitle">Documentation:</div>
    </div>
    <p>
        You can find some info in the <a href="../../wiki/">wiki</a>, or in the source code itself. As usual, documentation is a bit lacking, if you have problems ask the other devs.
    </p>
</div>
<?php
$body .= ob_get_clean();
require_once(dirname(__FILE__).'/scripts/page_creator.php');
$html = create_page($body,$head);
echo $html;
?>