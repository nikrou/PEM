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
    'extensions_translations' => 'admin/extensions_translations.tpl'
  )
);

// search
if (isset($_POST['reset']))
{
  unset($_POST);
}

$join = $where = array();
if (isset($_POST['category']) and $_POST['category']!=-1)
{
  $join[] = '
    INNER JOIN '.EXT_CAT_TABLE.' AS cat
      ON cat.idx_extension = ext.id_extension
      AND cat.idx_category = '.$_POST['category'];

  $tpl->assign('filter_category', $_POST['category']);
}
if (isset($_POST['version']) and $_POST['version']!=-1)
{
  $join[] = '
    INNER JOIN '.REV_TABLE.' AS rev
      ON rev.idx_extension = ext.id_extension
    INNER JOIN '.COMP_TABLE.' AS comp
      ON comp.idx_revision = rev.id_revision
      AND comp.idx_version = '.$_POST['version'];

  $tpl->assign('filter_version', $_POST['version']);
}
if (!empty($_POST['name']))
{
  $where[] = 'LOWER(ext.name) LIKE "%'.strtolower($_POST['name']).'%"';

  $tpl->assign('filter_name', stripslashes($_POST['name']));
}


// get extensions
$query = '
SELECT
    ext.id_extension,
    ext.name,
    ext.idx_language AS main_language,
    trans.idx_language AS other_language
  FROM '.EXT_TABLE.' AS ext
    LEFT JOIN '.EXT_TRANS_TABLE.' AS trans
      ON trans.idx_extension = ext.id_extension
    '.implode("\n    ", $join);
if (count($where))
{
  $query.= '
  WHERE
    '.implode("\n    AND ", $where);
}
$query.= '
  ORDER BY name ASC
;';
$result = $db->query($query);

$extension_languages = array();
while ($row = $db->fetch_assoc($result))
{
  if (empty($extension_languages[ $row['id_extension'] ]))
  {
    $extension_languages[ $row['id_extension'] ] = array(
      'id' => $row['id_extension'],
      'name' => $row['name'],
      'main' => $row['main_language'],
      'all' => array($row['main_language']),
      );
  }

  if ( !empty($row['other_language']) and
    !in_array($row['other_language'], $extension_languages[ $row['id_extension'] ]['all'])
  )
  {
    $extension_languages[ $row['id_extension'] ]['all'][] = $row['other_language'];
  }
}

$tpl->assign('extensions', $extension_languages);


// categories
$query = '
SELECT id_category, name
  FROM '.CAT_TABLE.' AS c
  ORDER BY name ASC
;';
$tpl->assign('categories', query2array($query, 'id_category', 'name'));

// versions
$query = '
SELECT id_version, version
  FROM '.VER_TABLE.'
  ORDER BY version DESC
;';
$tpl->assign('versions', query2array($query, 'id_version', 'version'));

// +-----------------------------------------------------------------------+
// |                           html code display                           |
// +-----------------------------------------------------------------------+

$tpl->assign_var_from_handle('main_content', 'extensions_translations');
$tpl->pparse('page');
