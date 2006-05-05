<?php
// +-----------------------------------------------------------------------+
// | PEM - a PHP based Extension Manager                                   |
// | Copyright (C) 2005-2006 PEM Team - http://home.gna.org/pem            |
// +-----------------------------------------------------------------------+
// | last modifier : $Author: plg $
// | revision      : $Revision: 2 $
// +-----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify  |
// | it under the terms of the GNU General Public License as published by  |
// | the Free Software Foundation                                          |
// |                                                                       |
// | This program is distributed in the hope that it will be useful, but   |
// | WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU      |
// | General Public License for more details.                              |
// |                                                                       |
// | You should have received a copy of the GNU General Public License     |
// | along with this program; if not, write to the Free Software           |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, |
// | USA.                                                                  |
// +-----------------------------------------------------------------------+

define('INTERNAL', true);
$root_path = './';
require_once($root_path . 'include/common.inc.php');

$page['page'] = isset( $_GET['page'] ) ? abs(intval($_GET['page'])) : 1;
$page['page'] = ( $page['page'] <= 0 ) ? 1 : $page['page'];
$page['category_id'] = isset($_GET['category']) ? abs(intval($_GET['category'])) : null;


// No action set, just display the extensions listing of the chosen category
$template->set_file('extensions', 'extensions.tpl');
  
// Get the category name
$query = '
SELECT name
  FROM '.CAT_TABLE.'
  WHERE id_category = '.$page['category_id'].'
;';
$req = $db->query($query);
$data = $db->fetch_assoc($req);
  
if ($db->num_rows($req) == 0)
{
  message_die(
    l10n('This category does not exist'),
    'Error',
    false
    );
}

$cat_name = $data['name'];
  
$query = '
SELECT idx_extension
  FROM '.EXT_CAT_TABLE.'
  WHERE idx_category = '.$page['category_id'].'
  ORDER BY idx_extension DESC
;';

$category_extension_ids = array_from_query($query, 'idx_extension');

// we don't want extension having no revision (this is possible)
$category_extension_ids = array_diff(
  $category_extension_ids,
  get_extension_ids_without_revision()
  );

if (isset($_SESSION['id_version']))
{
  $extension_ids = array();
  $version_ids_of_extension =
    get_version_ids_of_extension($category_extension_ids);

  foreach ($category_extension_ids as $extension_id)
  {
    if (in_array(
          $_SESSION['id_version'],
          $version_ids_of_extension[$extension_id]
          )
      )
    {
      array_push($extension_ids, $extension_id);
    }
  }
}
else
{
  $extension_ids = $category_extension_ids;
}

if (count($extension_ids) == 0)
{
  message_die(
    l10n('No extension available'),
    'Error',
    false
    );
}

$first = ($page['page'] - 1) * $conf['extensions_per_page'];

$page_extension_ids = array_slice(
  $extension_ids,
  $first,
  $conf['extensions_per_page']
  );
$versions_of_extension = get_versions_of_extension($page_extension_ids);
$extension_infos_of = get_extension_infos_of($page_extension_ids);
$author_ids = array_unique(
  array_from_subfield(
    $extension_infos_of,
    'idx_author'
    )
  );
$author_infos_of = get_author_infos_of($author_ids);

// Admin block used for admins and authors of the extension
$template->set_block('extensions', 'extension', 'Textension');
  
// Display the extensions
foreach ($page_extension_ids as $extension_id)
{
  $author_id = $extension_infos_of[$extension_id]['idx_author'];
  
  $template->set_var(
    array(
      'ID' => $extension_id,
      'NAME' => $extension_infos_of[$extension_id]['name'],
      'AUTHOR' => $author_infos_of[$author_id]['username'],
      'DESCRIPTION' => nl2br(
        $extension_infos_of[$extension_id]['description']
        ),
      'COMPATIBLE_VERSIONS' => implode(
        ', ',
        $versions_of_extension[$extension_id]),
      )
    );
  
  $template->parse('Textension', 'extension', true);
}
  
$template->set_var(
  array(
    'L_CATEGORY_NAME' => $cat_name,
    'L_EXTENSIONS_COUNT' => $extensions_count,
    'L_EXTENSIONS_START' => $extensions_start,
    'L_EXTENSIONS_END' => $extensions_end,
    'L_PAGE_ID' => $page['page'],
    'L_PAGE_COUNT' => $pages_count,
    'PAGINATION_BAR' => create_pagination_bar(
      'extensions.php?category='.$page['category_id'],
      get_nb_pages(
        count($extension_ids),
        $conf['extensions_per_page']
        ),
      $page['page'],
      'page'
      )
    )
  );

build_header();
$template->parse('output', 'extensions', true);
build_footer();
?>
