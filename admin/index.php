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
    'index' => 'admin/index.tpl'
  )
);

// Select the revisions count
$sql =  '
SELECT
    COUNT(id_revision) AS revisions_count
  FROM '.REV_TABLE.'
;';
$req = $db->query($sql);
$data = $db->fetch_assoc($req);

$tpl->assign('revisions_count', $data['revisions_count']);

// Are there extension without a single revision?
$query = '
SELECT COUNT(*)
  FROM '.EXT_TABLE.'
    LEFT JOIN '.REV_TABLE.' ON idx_extension = id_extension
  WHERE id_revision IS NULL
;';
list($count) = $db->fetch_row($db->query($query));
if ($count > 0) {
  $tpl->assign(
    array(
      'empty_extensions_count' => $count,
      'empty_extensions_url' => 'empty_extensions.php',
      )
    );
}

// Are there revisions compatible to no version?
$query = '
SELECT
    name,
    id_revision,
    idx_extension,
    nb_downloads,
    version,
    idx_version
  FROM '.REV_TABLE.'
    JOIN '.EXT_TABLE.' ON idx_extension = id_extension
    LEFT JOIN '.COMP_TABLE.' ON id_revision = idx_revision
  WHERE idx_version IS NULL
;';
$tpl->assign('no_compat_revs', query2array($query));

// Reviews awaiting validation
$query = '
SELECT COUNT(1)
  FROM '.REVIEW_TABLE.'
  WHERE validated = "false"
;';
list($count) = $db->fetch_row($db->query($query));
if ($count > 0) {
  $tpl->assign('nb_awaiting_reviews', $count);
}

// +-----------------------------------------------------------------------+
// |                           html code display                           |
// +-----------------------------------------------------------------------+

$tpl->assign_var_from_handle('main_content', 'index');
$tpl->pparse('page');
