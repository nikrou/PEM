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

$tpl->set_filename('page', 'page.tpl');

if (isset($user['id']))
{
  message_die('You mustn\'t be connected to read help guest');
}

// +-----------------------------------------------------------------------+
// |                           html code display                           |
// +-----------------------------------------------------------------------+

$help = '';

$selected_language_file = $root_path.'language/'.$_SESSION['language']['code'].'/help_guest.html';
$default_language_file = $root_path.'language/'.$conf['default_language'].'/help_guest.html';

if (is_file($selected_language_file))
{
  $help = file_get_contents($selected_language_file);
}
elseif (is_file($default_language_file))
{
  $help = file_get_contents($default_language_file);
}

$tpl->assign('main_content', $help);
include($root_path.'include/header.inc.php');
include($root_path.'include/footer.inc.php');
$tpl->parse('page');
$tpl->p();
