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

if (!defined('INTERNAL'))
{
  die('No right to do that, sorry. :)');
}

$tpl->assign(
  'generation_time',
  (intval(microtime(true) * 1000) - $page['start']).' ms'
  );

ob_start();
include($root_path.$conf['footer_filepath']);
$footer = ob_get_contents();
ob_end_clean();

$subversion_revision = get_Subversion_revision();
if (isset($subversion_revision)) {
  $tpl->assign(
    'subversion_revision',
    $subversion_revision
    );
}

$tpl->assign('footer', $footer);
