<?php
echo $event->sender->menu->run();
echo '<br/>';
echo 'Installation finished succesfully.<br/>';
echo '<br/>';
echo '<font color="red">Important:</font> Log in using username <em>admin</em> and password <em>admin</em><br/>';
echo 'Be sure to change both username and password upon logging in for the first time!<br/><br/>';
echo '<br/>';
echo '<b>Notice:</b> The installer is now locked.<br/>';
echo 'To enable the installer again, delete the <em>lock</em> file in the installer root directory.<br/>';
echo '<br/>';
echo '<br/>';
echo 'Enjoy Bugitor. :)<br/><br/>';
echo CHtml::link('Exit Installer', '../../user/login');
