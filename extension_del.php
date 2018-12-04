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

if (!isset($user['id']))
{
  message_die('You must be connected to add, modify or delete an extension.');
}

$page['extension_id'] =
  (isset($_GET['eid']) and is_numeric($_GET['eid']))
  ? $_GET['eid']
  : '';

if (empty($page['extension_id']))
{
  message_die('Incorrect extension identifier');
}

// Checks if the user who wants to delete the extension is really its author
$query = '
SELECT idx_user
  FROM '.EXT_TABLE.'
   WHERE id_extension = '.$page['extension_id'].'
;';
$req = $db->query($query);
$row = $db->fetch_assoc($req);

if (empty($row['idx_user']))
{
  message_die('Unknown extension');
}

if ($row['idx_user'] != $user['id'] and !isAdmin($user['id']))
{
  message_die('Deletion forbidden');
}

// Delete all the revisions for the given extension
$query = '
SELECT id_revision
  FROM '.REV_TABLE.'
  WHERE idx_extension = '.$page['extension_id'].'
;';
$rev_to_delete = query2array($query, null, 'id_revision');
delete_revisions($rev_to_delete);

// Deletes all the categories relations
$query = '
DELETE
  FROM '.EXT_CAT_TABLE.'
  WHERE idx_extension = '.$page['extension_id'].'
;';
$db->query($query);

// Deletes all the tags relations
$query = '
DELETE
  FROM '.EXT_TAG_TABLE.'
  WHERE idx_extension = '.$page['extension_id'].'
;';
$db->query($query);

// Deletes all the rates
$query = '
DELETE
  FROM '.RATE_TABLE.'
  WHERE idx_extension = '.$page['extension_id'].'
;';
$db->query($query);

// Deletes all the reviews
$query = '
DELETE
  FROM '.REVIEW_TABLE.'
  WHERE idx_extension = '.$page['extension_id'].'
;';
$db->query($query);

// And finally delete the extension
$query = '
DELETE
  FROM '.EXT_TABLE.'
  WHERE id_extension = '.$page['extension_id'].'
;';
$db->query($query);

message_success('Extension successfuly deleted.', 'index.php');
