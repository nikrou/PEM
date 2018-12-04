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

define( 'INTERNAL', true );
$root_path = './';
require_once( $root_path . 'include/common.inc.php' );

if (isset($_POST['revision_id']))
{
  $page['revision_id'] = intval($_POST['revision_id']);
}
else if (isset($_GET['rid']))
{
  $page['revision_id'] = intval($_GET['rid']);
}
else
{
  message_die('undefined revision identifier');
}

include($root_path.'revision_add.php');
