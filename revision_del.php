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

if (isset($_GET['rid']) and is_numeric($_GET['rid']))
{
  $page['revision_id'] = $_GET['rid'];
}
else
{
  message_die('Incorrect revision identifier');
}

// Checks if the user who wants to delete the revision is really its author
$query = '
SELECT idx_user,
       idx_extension
  FROM '. REV_TABLE.'
    INNER JOIN '.EXT_TABLE.' ON idx_extension = id_extension
  WHERE id_revision = '.$page['revision_id'].'
;';
$req = $db->query($query);
$row = $db->fetch_assoc($req);

if (empty($row['idx_user']))
{
  message_die('Unknown extension');
}

$authors = get_extension_authors($row['idx_extension']);

if (!in_array($user['id'], $authors) and !isAdmin($user['id']))
{
  message_die('Deletion forbidden');
}

delete_revisions(array($page['revision_id']));

message_success(
  'Revision successfuly deleted.',
  'extension_view.php?eid='.$row['idx_extension']
  );
