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
$root_path = './../';
require_once($root_path . 'include/common.inc.php');
require_once( $root_path . 'include/functions_admin.inc.php' );
require_once( $root_path . 'admin/init.inc.php' );

$tpl->set_filenames(
  array(
    'page' => 'admin/page.tpl',
    'empty_extensions' => 'admin/empty_extensions.tpl'
  )
);

// Are there extension without a single revision?
$query = '
SELECT
    id_extension,
    name
  FROM '.EXT_TABLE.'
    LEFT JOIN '.REV_TABLE.' ON idx_extension = id_extension
  WHERE id_revision IS NULL
;';
$tpl->assign('extensions', query2array($query));

// +-----------------------------------------------------------------------+
// |                           html code display                           |
// +-----------------------------------------------------------------------+

$tpl->assign_var_from_handle('main_content', 'empty_extensions');
$tpl->pparse('page');
