<?php
/*
* This file is part of PEM package
*
* Copyright(c) Nicolas Roudaire  https://www.nikrou.net/
* Licensed under the GPL version 2.0 license.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

define('INTERNAL', true);
$root_path = './';
require_once($root_path.'include/common.inc.php');

$tpl->set_filenames(
  array(
    'page' => 'page.tpl',
    'identification' => 'identification.tpl'
  )
);

if (isset($_POST['submit']))
{
  if ($user_id = check_user_password($_POST['username'], $_POST['password']))
  {
    log_user($user_id, $_POST['username'], $_POST['password']);

    message_success('Identification successful', 'my.php');
  }
  else
  {
    message_die('Incorrect username/password');
  }
}

if (isset($conf['external_register_url']))
{
  $tpl->assign('external_register', true);
}

// +-----------------------------------------------------------------------+
// |                           html code display                           |
// +-----------------------------------------------------------------------+

$tpl->assign_var_from_handle('main_content', 'identification');
include($root_path.'include/header.inc.php');
include($root_path.'include/footer.inc.php');
$tpl->parse('page');
$tpl->p();
