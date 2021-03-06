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
 * check user password
 */
function check_user_password($username, $password)
{
  global $conf, $db;

  // retrieving the encrypted password of the login submitted
  $query = '
SELECT '.$conf['user_fields']['id'].' AS id,
       '.$conf['user_fields']['password'].' AS password
  FROM '.USERS_TABLE.'
  WHERE '.$conf['user_fields']['username'].' = \''. $db->escape($username) .'\'
;';

  $row = $db->fetch_assoc($db->query($query));

  // possible problem if there is an escaped character in the password,
  // because it will have been automatically escaped in
  // include/common.inc.php
  $password = stripslashes($password);

  if ($row['password'] == $conf['pass_convert']($password))
  {
    return $row['id'];;
  }
  else
  {
    return false;
  }
}

/**
 * register a new user in the database
 */
function register_user($username, $password, $email)
{
  global $conf, $db;

  $errors = array();

  if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username))
  {
    array_push(
      $errors,
      l10n('Incorrect username')
      );
  }
  else if (filter_var($email, FILTER_VALIDATE_EMAIL) === false)
  {
    array_push(
      $errors,
      l10n('Mail address must be like xxx@yyy.eee (example : jack@altern.org)')
      );
  }
  else if (get_userid($username))
  {
    array_push(
      $errors,
      l10n('Username already in use')
      );
  }

  // if no error until here, registration of the user
  if (count($errors) == 0)
  {
    // possible problem if there is an escaped character in the password,
    // because it will have been automatically escaped in
    // include/common.inc.php
    $password = stripslashes($password);

    $insert =
      array(
        $conf['user_fields']['username'] => $username,
        $conf['user_fields']['password'] => $conf['pass_convert']($password),
        $conf['user_fields']['email'] => $email
        );

    mass_inserts(
      USERS_TABLE,
      array_keys($insert),
      array($insert)
      );

    create_user_infos($db->insert_id());
  }

  return $errors;
}

/**
 * returns user identifier thanks to his name, false if not found
 *
 * @param string username
 * @param int user identifier
 */
function get_userid($username)
{
  global $conf, $db;

  $query = '
SELECT '.$conf['user_fields']['id'].'
  FROM '.USERS_TABLE.'
  WHERE '.$conf['user_fields']['username'].' = \''.$username.'\'
;';
  $result = $db->query($query);

  if ($db->num_rows($result) == 0)
  {
    return false;
  }
  else
  {
    list($user_id) = $db->fetch_row($result);
    return $user_id;
  }
}

/**
 * add user informations based on default values
 *
 * @param int user_id
 */
function create_user_infos($user_id)
{
  global $conf, $db;

  list($dbnow) = $db->fetch_row(
    $db->query('SELECT NOW();')
    );

  $insert = array(
    'idx_user' => $user_id,
    'language' => $conf['default_language'],
    'registration_date' => $dbnow,
    );

  mass_inserts(
    USER_INFOS_TABLE,
    array_keys($insert),
    array($insert)
    );
}

/**
 * returns basic infos of a specific user or a set of users
 */
function get_user_basic_infos_of($author_ids)
{
  global $db, $conf;

  if (count($author_ids) == 0) {
    return array();
  }

  $user_basic_infos_of = array();

  $query = '
SELECT '.$conf['user_fields']['id'].' AS id,
       '.$conf['user_fields']['username'].' AS username,
       '.$conf['user_fields']['password'].' AS password,
       '.$conf['user_fields']['email'].' AS email
  FROM '.USERS_TABLE.'
  WHERE '.$conf['user_fields']['id'].' IN ('.implode(',', $author_ids).')
;';
  $result = $db->query($query);
  while ($row = $db->fetch_assoc($result))
  {
    $user_basic_infos_of[ $row['id'] ] = $row;
  }

  return $user_basic_infos_of;
}
