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

/**
 * delete a category and all dependencies
 */
function delete_category($category_id) {
  global $db;

  $query = '
DELETE
  FROM '.EXT_CAT_TABLE.'
  WHERE idx_category = '.$category_id.'
;';
  $db->query($query);

  $query = '
DELETE
  FROM '.CAT_TABLE.'
  WHERE id_category = '.$category_id.'
;';
  $db->query($query);

  $query = '
DELETE
  FROM '.CAT_TRANS_TABLE.'
  WHERE idx_category = '.$category_id.'
;';
  $db->query($query);
}

/**
 * delete a tag and all dependencies
 */
function delete_tag($tag_id) {
  global $db;

  $query = '
DELETE
  FROM '.EXT_TAG_TABLE.'
  WHERE idx_tag = '.$tag_id.'
;';
  $db->query($query);

  $query = '
DELETE
  FROM '.TAG_TABLE.'
  WHERE id_tag = '.$tag_id.'
;';
  $db->query($query);

  $query = '
DELETE
  FROM '.TAG_TRANS_TABLE.'
  WHERE idx_tag = '.$tag_id.'
;';
  $db->query($query);
}

/**
 * delete a version and all dependencies
 */
function delete_version($version_id) {
  global $db;

  $query = '
DELETE
  FROM '.COMP_TABLE.'
  WHERE idx_version = '.$version_id.'
;';
  $db->query($query);

  $query = '
DELETE
  FROM '.VER_TABLE.'
  WHERE id_version = '.$version_id.'
;';
  $db->query($query);
}
