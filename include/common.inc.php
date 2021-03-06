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

$page = array(
  'errors' => array(),
  'warnings' => array(),
  'infos' => array(),
  );

// determine the initial instant to indicate the generation time of this page
$page['start'] = intval(microtime(true) * 1000);

header('Content-Type: text/html; charset=utf-8');

// Hacking attempt
if(!defined('INTERNAL'))
{
  die('No right to do that, sorry. :)');
}

session_name('pem_session_id');
session_start();

require_once($root_path . 'include/config_default.inc.php');
@include_once($root_path . 'include/config_local.inc.php');
require_once($root_path . 'include/constants.inc.php');
require_once($root_path . 'include/functions.inc.php');
require_once($root_path . 'include/dblayer/common_db.php');
require_once($root_path . 'include/template.class.php');

$db->query('SET NAMES "utf8"');

// secure user incoming data
//
// First we undo what has been done magically
fix_magic_quotes();

// echo '<pre>cookie: '; print_r($_COOKIE); echo '</pre>';
debug($_COOKIE);
// echo '<pre>session: '; print_r($_SESSION); echo '</pre>';
// echo '<pre>user: '; print_r($user); echo '</pre>';

// user informations
$user = array();

if (isset($_COOKIE[ $conf['user_cookie_name'] ])) {
  $cookie = array();

  list($cookie['user_id'], $cookie['password_hash']) = unserialize(
    stripslashes($_COOKIE[ $conf['user_cookie_name'] ])
    );

  // echo '<pre>'; print_r($cookie); echo '</pre>';

  $user_infos_of = get_user_infos_of(array($cookie['user_id']));
  $user = $user_infos_of[$cookie['user_id']];

  debug($user);

  debug(
    array(
      md5($conf['cookie_seed'].$user['password']),
      $cookie['password_hash']
      )
    );

  if (md5($conf['cookie_seed'].$user['password']) !== $cookie['password_hash']) {
    $user = array();
  }
}

// echo '<pre>user: '; print_r($user); echo '</pre>';

$tpl = new template($root_path . 'template/');
$tpl->assign('software', $conf['software']);

// Language selection
$interface_languages = get_interface_languages();
$_SESSION['language'] = get_current_language();

$self_uri = preg_replace('#(\?|&)lang=.._..#', '', $_SERVER['REQUEST_URI']);
$self_uri .= strpos($self_uri, '?') ? '&amp;' : '?';
$tpl->assign('self_uri', $self_uri);
$tpl->assign('user_language', $_SESSION['language']['code']);
$tpl->assign('languages', $interface_languages);

$lang = array();
load_language('common.lang.php');
load_language('local.lang.php', true);

// do we have a disclaimer?
if (is_file($root_path.'language/'.$_SESSION['language']['code'].'/disclaimer.html')
  or is_file($root_path.'language/'.$conf['default_language'].'/disclaimer.html'))
{
  $tpl->assign('has_disclaimer', true);
}

// do we have a HELP?
if (is_file($root_path.'language/'.$_SESSION['language']['code'].'/help_guest.html')
  or is_file($root_path.'language/'.$conf['default_language'].'/help_guest.html'))
{
  $tpl->assign('has_help', true);
}
if (is_file($root_path.'language/'.$_SESSION['language']['code'].'/help_user.html')
  or is_file($root_path.'language/'.$conf['default_language'].'/help_user.html'))
{
  $tpl->assign('has_help_user', true);
}

// is the URL prefiltered?
if (isset($_GET['cid'])) {
  if ($_GET['cid'] == 'null') {
    // special url parameter to cancel category filter
    unset($_SESSION['filter']['category_ids'], $_SESSION['filter']['category_mode']);
  }
  else if (is_numeric($_GET['cid'])) {
    $_SESSION['filter']['category_ids'] = array($_GET['cid']);
    $_SESSION['filter']['category_mode'] = 'and';
  }
}
if ( isset($_GET['tid']) and is_numeric($_GET['tid']) ) {
  $_SESSION['filter']['tag_ids'] = array($_GET['tid']);
  $_SESSION['filter']['tag_mode'] = 'and';
}
if ( isset($_GET['uid']) and is_numeric($_GET['uid']) ) {
  $_SESSION['filter']['id_user'] = $_GET['uid'];
}

// PWG Compatibility version set
if (isset($_POST['filter_submit'])) {
  // filter on the extended application version
  if (isset($_POST['id_version']) and is_numeric($_POST['id_version'])) {
    // If the field is empty, this means that the user wants to cancel the
    // compatibility version setting
    if (!empty($_POST['id_version'])) {
      $_SESSION['filter']['id_version'] = intval($_POST['id_version']);
    }
    else {
      unset($_SESSION['filter']['id_version']);
    }
  }

  // filter on a textual free search
  if (isset($_POST['search']) and !empty($_POST['search'])) {
    $_SESSION['filter']['search'] = $_POST['search'];
  }
  else {
    unset($_SESSION['filter']['search']);
  }

  // filter on a user
  if (isset($_POST['id_user']) and is_numeric($_POST['id_user'])) {
    if ($_POST['id_user'] != 0) {
      $_SESSION['filter']['id_user'] = $_POST['id_user'];
    }
    else {
      unset($_SESSION['filter']['id_user']);
    }
  }
}

// reset filter
if (isset($_POST['filter_reset']) or isset($_GET['filter_reset'])) {
  if (isset($_SESSION['filter'])) {
    unset($_SESSION['filter']);
  }
}

if (isset($_POST['filter_reset']) or isset($_POST['filter_submit'])) {
  unset($_GET['page']);
}

// if a filter is active, we must prepare a filtered list of extensions
if (isset($_SESSION['filter']) and count($_SESSION['filter']) > 0) {
  $page['filtered_extension_ids'] = get_filtered_extension_ids($_SESSION['filter']);

  $page['filtered_extension_ids_string'] = implode(
    ',',
    $page['filtered_extension_ids']
    );
}

// quick connect
if (!isset($user['id']) and isset($_POST['quickconnect_submit']))
{
  if ($user_id = check_user_password($_POST['username'], $_POST['password']))
  {
    log_user($user_id, $_POST['username'], $_POST['password']);

    message_success('Identification successful', 'my.php');
  }
  else
  {
    message_die('Incorrect username/password');
  }
}

// logout
if (isset($_GET['action']) and $_GET['action']=='logout')
{
  $_SESSION = array();
  $user = array();

  session_unset();
  session_destroy();
  setcookie(
    session_name(),
    '',
    0,
    ini_get('session.cookie_path'),
    ini_get('session.cookie_domain')
    );

  unset($_COOKIE[ $conf['user_cookie_name'] ]);
  setcookie($conf['user_cookie_name'], false, 0, $conf['cookie_path']);

  // redirect to index
  message_success('Logout successful', 'index.php');
}
