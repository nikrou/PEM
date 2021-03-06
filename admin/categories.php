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
    'categories' => 'admin/categories.tpl'
  )
);

$tpl->assign('category_form_title', l10n('Add a category'));
$tpl->assign('category_form_type', l10n('add'));
$tpl->assign('default_name', $interface_languages[$conf['default_language']]['id']);

if (isset($_POST['submit_add']))
{
  $insert = array(
    'name' => $_POST['name'][$_POST['default_name']],
    'idx_language' => $_POST['default_name'],
    );

  mass_inserts(
    CAT_TABLE,
    array_keys($insert),
    array($insert)
    );
  $page['category_id'] = $db->insert_id();
}

if (isset($_POST['submit_edit']))
{
  $page['category_id'] = $_POST['id'];

  mass_updates(
    CAT_TABLE,
    array(
      'primary' => array('id_category'),
      'update' => array('name', 'idx_language'),
      ),
    array(
      array(
        'id_category' => $page['category_id'],
        'name' => $_POST['name'][$_POST['default_name']],
        'idx_language' => $_POST['default_name'],
        )
      )
    );

  $query = '
DELETE
  FROM '.CAT_TRANS_TABLE.'
  WHERE idx_category = '.$_POST['id'].'
;';
    $db->query($query);

  $tpl->assign('f_action', 'categories.php');
  unset($_GET['edit']);
}

if (isset($_POST['submit_edit']) or isset($_POST['submit_add']))
{
  // Insert translations
  $inserts = array();
  foreach ($_POST['name'] as $lang_id => $name)
  {
    if ($lang_id == $_POST['default_name'] or empty($name))
    {
      continue;
    }
    array_push(
      $inserts,
      array(
        'idx_category' => $page['category_id'],
        'idx_language' => $lang_id,
        'name'         => $name,
        )
      );
  }
  if (!empty($inserts))
  {
    mass_inserts(CAT_TRANS_TABLE, array_keys($inserts[0]), $inserts);
  }
}

if (isset($_GET['edit'])) {
  $page['category_id'] = abs(intval($_GET['edit']));
  if ($page['category_id'] != $_GET['edit']) {
    message_die('edit URL parameter is incorrect', 'Error', false);
  }

  $tpl->assign('category_form_title', l10n('Modify a category'));
  $tpl->assign('category_form_type', l10n('edit'));
  $tpl->assign('category_form_expanded', true);

  $query = '
SELECT
    id_category,
    name,
    idx_language
  FROM '.CAT_TABLE.'
  WHERE id_category = '.$page['category_id'].'
;';
  $row = $db->fetch_assoc($db->query($query));
  $tpl->assign('category_id', $row['id_category']);
  $tpl->assign('default_name', $row['idx_language']);
  $name = array($row['idx_language'] => $row['name']);

  $query = '
SELECT idx_category,
       idx_language,
       name
  FROM '.CAT_TRANS_TABLE.'
  WHERE idx_category = '.$page['category_id'].'
;';
  $result = $db->query($query);
  while($row = $db->fetch_assoc($result))
  {
    $name[$row['idx_language']] = $row['name'];
  }
  $tpl->assign('name', $name);
}

if (isset($_GET['delete'])) {
  $page['category_id'] = abs(intval($_GET['delete']));
  if ($page['category_id'] != $_GET['delete']) {
    message_die('edit URL parameter is incorrect', 'Error', false);
  }

  delete_category($page['category_id']);
}

// Categories selection
$query = '
SELECT
    id_category,
    name
  FROM '.CAT_TABLE.'
  ORDER BY name ASC
;';
$req = $db->query($query);

$tpl_categories = array();
while ($cat = $db->fetch_assoc($req))
{
  array_push(
    $tpl_categories,
    array(
      'id' => $cat['id_category'],
      'name' => $cat['name'],
      )
    );
}

$tpl->assign('categories', $tpl_categories);
$tpl->assign('f_action', 'categories.php');

// +-----------------------------------------------------------------------+
// |                           html code display                           |
// +-----------------------------------------------------------------------+

$tpl->assign_var_from_handle('main_content', 'categories');
$tpl->parse('page');
$tpl->p();
