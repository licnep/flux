USE fluxTasks;

#
# tasks are put directly in this file
# Try to make tasks as easy and small as possible
#

INSERT INTO tasks 
SET date = '2011-08-06',
description = '
<b>Find a real name/domain for the flux project.</b><br/>
Propose your names <a href="http://flux.lolwut.net:89/mediawiki/index.php/Name_and_logo">here</a> (sign your proposals), comment other people\'s proposals too<br/>
<b>Award</b>: 40 points (if your name is chosen or it\'s the closest to the chosen one)
';


INSERT INTO tasks 
SET date = '2011-08-06',
description = '
<b>Fork or clone the github repo.<br/></b>
That\'s right, you get 1 point for just forking the repo. It requires a bit of work anyway.<br/>
Here\'s the <a href="https://github.com/licnep/flux">link</a>.<br/>
<b>Award</b>: 1 point
';


INSERT INTO tasks 
SET date = '2011-08-06',
description = '
<b>Push your first change to the github repo.<br/></b>
Ok this seems stupid, but at least it means you learnt some git. Good job bro! +2 points for learning git!<br/>
What? you don\'t know git? What a flaming faggot. Ask in the irc or skim through <a href="http://progit.org/book/">this</a>.<br/>
<b>Award</b>: 2 points
';

INSERT INTO tasks 
SET date = '2011-08-06',
description = '
<b>Draw a mockup, or something like that.<br/></b>
Draw a mockup, and upload it somewhere (possibly the git repo).<br/>
Currently most needed mockups: 
<ul>
<li>general website look&feel (eg. colors, websites we can copy from etc.)</li>
<li>homepage for logged in user</li>
<li>registration procedure</li>
<li>flux creation procedure</li>
<li>representations of a flux</li>
<li>cool stuff to make the website cooler</li>
</ul>
<b>Award</b>: 0~5 points, depending on the goodness/well-thoughtness/usefulness/spot-on-ness of the mockup. Not all mockups will get points.
';

INSERT INTO tasks 
SET date = '2011-08-06',
description = '
<b>Have an awesome idea.<br/></b>
Ideas are great, and they require work.<br/>
If you come up with a cool, innovative idea for the flux (i\'m talking <u>real cool</u>) you\'ll get some points.<br/>
<b>Award</b>: 0~9001 points
';

INSERT INTO tasks 
SET date = '2011-08-06',
description = '
<b>Recruit moar people.<br/></b>
Tell a friend, or an enemy, or post somewhere.<br/>
<b>Award</b>: 3+ points/recruit (only if the recruit is in good shape. Extra points for cool recruits. You get the points only once the recruit has done something useful)
';

INSERT INTO tasks 
SET date = '2011-08-06',
description = '
<b>CSS/html coding.<br/></b>
Make pages cooler, according to some mockups or your personal taste.<br/>
Current things to modify:<ul>
<li>global website frame</li>
<li>account home</li>
<li>registration,login</li>
<li>flux representation,flux creation</li>
</ul>
<b>Award</b>: about 1 point per 10 lines of code. Extra points for cool stuff, or stuff that required more work.
';

INSERT INTO tasks 
SET date = '2011-08-06',
description = '
<b>Flux API coding.<br/></b>
Code the script that moves money around between the fluxes.<br/>
Currently there\'s not much to code in the backend api, but check for updates.</br>
<b>Award</b>: ~1 point per 10 lines of code.
';

INSERT INTO tasks 
SET date = '2011-08-06',
description = '
<b>Coding the payment procedure<br/></b>
Phase 1: design the process for the payment and withdrawal of donations. It must be abstract, so that we can use different payment mehods (eg. paypal/amazon payments/bitcoins/game points)</br>
<b>Award</b>: ~1 point per 10 lines of code.
';

INSERT INTO tasks 
SET date = '2011-08-06',
description = '
<b>Make this tasks page look better<br/></b>
<b>Award</b>: ~1 point per 10 lines of code.
';

INSERT INTO tasks 
SET date = '2011-08-06',
description = '
<b>javascript field Validation in the registration form.<br/></b>
<b>Award</b>: ~1 point per 10 lines of code.
';

INSERT INTO tasks 
SET date = '2011-08-06',
description = '
<b>Make login automatic after registration<br/></b>
<b>Award</b>: ~1 point per 10 lines of code.
';

INSERT INTO tasks 
SET date = '2011-08-06',
description = '
<b>Turn the creation of a flux into a popup.<br/></b>
<b>Award</b>: ~1 point per 10 lines of code.
';

INSERT INTO tasks 
SET date = '2011-08-06',
description = '
<b>Document.The.Fucking.Code.<br/></b>
Make documents that explain both the big picture, and the single api calls.<br/>
<b>Award</b>: ~1 point per 10 lines.
';

INSERT INTO tasks 
SET date = '2011-08-06',
description = '
<b>Anything useful not listed here.<br/></b>
Not everything that can be done is listed here, BE CREATIVE!<br/>
<b>Award</b>: 0~9001 points
';

