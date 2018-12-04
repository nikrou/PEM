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

$query = '
SELECT
    idx_category,
    COUNT(*) AS counter
  FROM '.EXT_CAT_TABLE.'
  GROUP BY idx_category
;';
$nb_ext_of_category = query2array($query, 'idx_category', 'counter');

$query = '
SELECT
    id_category AS id,
    c.name AS default_name,
    ct.name
  FROM '.CAT_TABLE.' AS c
  LEFT JOIN '.CAT_TRANS_TABLE.' AS ct
    ON c.id_category = ct.idx_category
    AND ct.idx_language = '.$_SESSION['language']['id'].'
  ORDER BY name ASC
;';
$output = query2array($query);
foreach ($output as $i => $category) {
  if (empty($output[$i]['name']))
  {
    $output[$i]['name'] = $output[$i]['default_name'];
  }
  unset($output[$i]['default_name']);

  $output[$i]['counter'] = 0;
  if (isset($nb_ext_of_category[ $category['id'] ])) {
    $output[$i]['counter'] = $nb_ext_of_category[ $category['id'] ];
  }
}

$format = 'json';
if (isset($_GET['format'])) {
  $format = strtolower($_GET['format']);
}

switch ($format) {
  case 'json' :
    echo json_encode($output);
    break;
  case 'php' :
    echo serialize($output);
    break;
  default :
    echo json_encode($output);
}
