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
    'reviews' => 'admin/reviews.tpl'
  )
);

if (isset($_GET['delete_review']))
{
  delete_user_review($_GET['delete_review']);
}
else if (isset($_GET['validate_review']))
{
  validate_user_review($_GET['validate_review']);
}

$query = '
SELECT *
  FROM '.REVIEW_TABLE.'
  WHERE validated = "false"
  ORDER BY date DESC
;';
$tpl_reviews = query2array($query);

if (count($tpl_reviews))
{
    $extensions_ids = array_map(function($v) {return $v["idx_extension"];}, $tpl_reviews);
    $extensions_infos_of = get_extension_infos_of($extensions_ids);

  foreach ($tpl_reviews as &$review)
  {
    $review['extension_name'] = $extensions_infos_of[ $review['idx_extension'] ]['name'];
    $review['content'] = nl2br($review['content']);
    $review['date'] = date('d F Y H:i:s', strtotime($review['date']));
    $review['u_delete'] = 'reviews.php?delete_review='.$review['id_review'];
    $review['u_validate'] = 'reviews.php?validate_review='.$review['id_review'];
  }

  $tpl->assign('reviews', $tpl_reviews);
}

$tpl->assign('nb_reviews', count($tpl_reviews));
$tpl->assign('f_action', 'reviews.php');

// +-----------------------------------------------------------------------+
// |                           html code display                           |
// +-----------------------------------------------------------------------+

$tpl->assign_var_from_handle('main_content', 'reviews');
$tpl->parse('page');
$tpl->p();
