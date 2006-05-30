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
require_once($root_path.'include/common.inc.php');

$template->set_file('index', 'index.tpl');

$revision_ids = array();
$revision_infos_of = array();
$extension_ids = array();
$extension_infos_of = array();
$author_ids = array();
$author_infos_of = array();

// retrieve N last added revisions, filtered on the user version
$query = '
SELECT DISTINCT r.id_revision
  FROM '.REV_TABLE.' r';
if (isset($_SESSION['id_version']))
{
  $query.= '
    INNER JOIN '.COMP_TABLE.' c ON c.idx_revision = r.id_revision
  WHERE c.idx_version = '.$_SESSION['id_version'];
}
$query.= '
  ORDER BY r.id_revision DESC
  LIMIT 0, '.$conf['nb_last_revs'].'
;';

$revision_ids = array_from_query($query, 'id_revision');

if (count($revision_ids) == 0)
{
  message_die(
    l10n('No extension match your filter'),
    sprintf(
      l10n('%d last revisions added'),
      $conf['nb_last_revs']
      ),
    false
    );
}

$versions_of = get_versions_of_revision($revision_ids);

// retrieve revisions information
$revision_infos_of = get_revision_infos_of($revision_ids);
$extension_ids = array_unique(
  array_from_subfield(
    $revision_infos_of,
    'idx_extension'
    )
  );

$extension_infos_of = get_extension_infos_of($extension_ids);
$author_ids = array_unique(
  array_from_subfield(
    $extension_infos_of,
    'idx_user'
    )
  );

$author_infos_of = get_user_infos_of($author_ids);

// $template->set_block( 'index', 'switch_admin', 't_switch_admin' );
$template->set_block('index', 'revision', 'Trevision');

foreach ($revision_ids as $revision_id)
{
  $extension_id = $revision_infos_of[$revision_id]['idx_extension'];
  $author_id = $extension_infos_of[$extension_id]['idx_user'];
  
  $template->set_var(
    array(
      'ID' => $revision_id,
      'EXTENSION_NAME' => $extension_infos_of[$extension_id]['name'],
      'AUTHOR' => $author_infos_of[$author_id]['username'],
      'REVISION_NAME' => $revision_infos_of[$revision_id]['version'],
      'COMPATIBLE_VERSIONS' => implode(', ', $versions_of[$revision_id]),
      'REVISION_DESCRIPTION' => nl2br(
        htmlspecialchars(
          strip_tags($revision_infos_of[$revision_id]['description'])
          )
        ),
      'DATE' => date('Y-m-d', $revision_infos_of[$revision_id]['date']),
      )
    );
  $template->parse('Trevision', 'revision', true);
}
  
build_header();
$template->parse('output', 'index', true);
build_footer();
?>
