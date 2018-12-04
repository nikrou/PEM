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
    'search' => 'admin/search.tpl'
  )
);

if (isset($_POST['compatibility_check']))
{
  $compatibles = get_extension_ids_for_version($_POST['version0']);
  !empty($compatibles) or $compatibles=array(0);

  $query = '
SELECT id_extension
  FROM '.EXT_TABLE.'
  WHERE id_extension NOT IN('. implode(',', $compatibles) .')
;';
  $id_extensions = query2array($query, null, 'id_extension');

  if (!empty($id_extensions))
  {
    $query = '
SELECT
    id_extension,
    name
  FROM '.EXT_TABLE.'
  WHERE id_extension IN('. implode(',', $id_extensions) .')
;';
    $extensions = query2array($query);
  }
  else
  {
    $extensions = array();
  }

  $tpl->assign(array(
    'extensions' => $extensions,
    'version0' => $_POST['version0'],
   ));
}

if (isset($_POST['inter_compatibility_check']))
{
  $compatibles = get_extension_ids_for_version($_POST['version1']);
  $compatibles2 = get_extension_ids_for_version($_POST['version2']);
  !empty($compatibles2) or $compatibles2=array(0);

  $query = '
SELECT id_extension
  FROM '.EXT_TABLE.'
  WHERE id_extension NOT IN('. implode(',', $compatibles2) .')
;';
  $incompatibles = query2array($query, null, 'id_extension');

  $id_extensions = array_intersect($compatibles, $incompatibles);

  if (!empty($id_extensions))
  {
    $query = '
SELECT
    id_extension,
    name
  FROM '.EXT_TABLE.'
  WHERE id_extension IN('. implode(',', $id_extensions) .')
;';
    $extensions = query2array($query);
  }
  else
  {
    $extensions = array();
  }

  $tpl->assign(array(
    'inter_extensions' => $extensions,
    'version1' => $_POST['version1'],
    'version2' => $_POST['version2'],
   ));
}


// get versions
$query = '
SELECT
    id_version,
    version
  FROM '.VER_TABLE.'
;';
$versions = array_reverse(
  versort(
    query2array($query)
    )
  );

$tpl_versions = array();

// Displays the versions
foreach ($versions as $version)
{
  array_push(
    $tpl_versions,
    array(
      'id' => $version['id_version'],
      'name' => $version['version'],
      )
    );
}

$tpl->assign('versions', $tpl_versions);
$tpl->assign('f_action', 'search.php');

// +-----------------------------------------------------------------------+
// |                           html code display                           |
// +-----------------------------------------------------------------------+

$tpl->assign_var_from_handle('main_content', 'search');
$tpl->parse('page');
$tpl->p();
