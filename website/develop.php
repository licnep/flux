<?php
$head='';
$body='';

ob_start(); //i call ob_start, so that instead of outputting everything directly, we can buffer the output and pass it to the create_page function
?>
<div class="well">
    <div class="wellTitleBar">
        <div class="wellTitle">Stuff you can do:</div>
    </div>
    <p>
        <ul>
            <li>Fork us on <a href="https://github.com/sandboxer/flux/">Github</a>.</li>
            <li>Join the <a href="http://groups.google.com/group/flux-list">mailing list</a>.</li>
            <li>Unleash your hacker powers on the <a href="sandboxNotYetCreated">sandbox</a>, and let us know if you find any security flaws.</li>
        </ul>
    </p>
</div>
<?php
$body .= ob_get_clean();
require_once(dirname(__FILE__).'/scripts/page_creator.php');
$html = create_page($body,$head,'develop');
echo $html;
?>