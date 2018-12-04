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

$available_views = array('standard', 'compact');

if (isset($_GET['view'])) {
  if (in_array($_GET['view'], $available_views)) {
    $_SESSION['view'] = $_GET['view'];
  }
}

$view = $available_views[0];
if (isset($_SESSION['view'])) {
  $view = $_SESSION['view'];
}

$tpl->assign('view', $view);

require_once($root_path.'include/index_view_'.$view.'.inc.php');

$tpl->parse('page');
$tpl->p();
