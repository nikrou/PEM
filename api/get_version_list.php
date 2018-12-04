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
$root_path = '../';
require_once($root_path.'include/common.inc.php');

// if a category_id is given, it will say the number of extensions available
// for each version, but all versions are returned.

$category_id = null;
if (isset($_GET['category_id'])) {
  $category_id = $_GET['category_id'];
  if ($category_id != abs(intval($category_id))) {
    die('unexpected category identifier');
  }
}

$query = '
SELECT
    idx_version,
    COUNT(DISTINCT(r.idx_extension)) AS counter
  FROM '.COMP_TABLE.' AS c
    JOIN '.REV_TABLE.' AS r ON r.id_revision = c.idx_revision';
if (isset($category_id)) {
  $query.= '
    JOIN '.EXT_TABLE.' AS e ON e.id_extension = r.idx_extension
    JOIN '.EXT_CAT_TABLE.'  AS ec ON ec.idx_extension = e.id_extension
  WHERE idx_category = '.$category_id.'
';
}
$query.= '
  GROUP BY idx_version
;';
$nb_ext_of_version = query2array($query, 'idx_version', 'counter');

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

$output_versions = array();

foreach ($versions as $version) {
  $id_version = $version['id_version'];

  array_push(
    $output_versions,
    array(
      'id' => $id_version,
      'name' => $version['version'],
      'nb_extensions' => isset($nb_ext_of_version[$id_version]) ? $nb_ext_of_version[$id_version] : 0,
      )
    );
}

$format = 'json';
if (isset($_GET['format'])) {
  $format = strtolower($_GET['format']);
}

switch ($format) {
  case 'json' :
    echo json_encode($output_versions);
    break;
  case 'php' :
    echo serialize($output_versions);
    break;
  default :
    echo json_encode($output_versions);
}
