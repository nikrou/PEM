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
    'versions' => 'admin/versions.tpl'
  )
);

$tpl->assign('version_form_title', l10n('Add a version'));
$tpl->assign('version_form_type', l10n('add'));

if (isset($_POST['submit_add'])) {
  $insert = array(
    'version' => $_POST['name'],
    );

  mass_inserts(
    VER_TABLE,
    array_keys($insert),
    array($insert)
    );
}

if (isset($_POST['submit_edit'])) {
  mass_updates(
    VER_TABLE,
    array(
      'primary' => array('id_version'),
      'update' => array('version'),
      ),
    array(
      array(
        'id_version' => $_POST['id'],
        'version' => $_POST['name'],
        )
      )
    );

  $tpl->assign('f_action', 'versions.php');
  unset($_GET['edit']);
}

if (isset($_GET['edit'])) {
  $page['version_id'] = abs(intval($_GET['edit']));
  if ($page['version_id'] != $_GET['edit']) {
    message_die('edit URL parameter is incorrect', 'Error', false);
  }

  $tpl->assign('version_form_title', l10n('Modify a version'));
  $tpl->assign('version_form_type', l10n('edit'));
  $tpl->assign('version_form_expanded', true);

  $query = '
SELECT
    id_version,
    version
  FROM '.VER_TABLE.'
  WHERE id_version = '.$page['version_id'].'
;';
  $data = $db->fetch_assoc($db->query($query));

  $tpl->assign('version_id', $data['id_version']);
  $tpl->assign('name', $data['version']);
}

if (isset($_GET['delete'])) {
  $page['version_id'] = abs(intval($_GET['delete']));
  if ($page['version_id'] != $_GET['delete']) {
    message_die('edit URL parameter is incorrect', 'Error', false);
  }

  delete_version($page['version_id']);
}

// Categories selection
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
$tpl->assign('f_action', 'versions.php');

// +-----------------------------------------------------------------------+
// |                           html code display                           |
// +-----------------------------------------------------------------------+

$tpl->assign_var_from_handle('main_content', 'versions');
$tpl->parse('page');
$tpl->p();
