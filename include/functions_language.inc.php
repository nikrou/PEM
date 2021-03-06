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

/* this file contains functions for language management */

/**
 * returns the corresponding value from $lang if existing. Else, the key is
 * returned
 *
 * @param string key
 * @return string
 */
function l10n($key)
{
  global $lang, $conf;

  if ($conf['debug_l10n'] and !isset($lang[$key]) and !empty($key))
  {
    trigger_error('[l10n] language key "'.$key.'" is not defined', E_USER_WARNING);
  }

  return isset($lang[$key]) ? $lang[$key] : $key;
}

/**
 * includes a language file
 */
function load_language($filename, $no_fallback = false, $dirname = './')
{
  global $conf, $lang;

  $dirname .= 'language/';

  $selected_language_file = $dirname . $_SESSION['language']['code'] . '/' . $filename;
  $default_language_file = $dirname . $conf['default_language'] . '/' . $filename;

  if (file_exists($selected_language_file))
  {
    @include($selected_language_file);
  }
  elseif (!$no_fallback and file_exists( $default_language_file))
  {
    @include($default_language_file);
  }
}

/**
 * tries to determine the visitor language from SESSION, SERVER and config
 */
function get_current_language()
{
  global $db, $conf;

  $language = null;

  $interface_languages = get_interface_languages();

  if (isset($_GET['lang']))
  {
    $language = @$interface_languages[$_GET['lang']];
  }
  else if (isset($_SESSION['language']))
  {
    $language = $_SESSION['language'];
  }

  if (empty($language) or !is_array($language))
  {
    $language = $interface_languages[$conf['default_language']];

    if ($conf['get_browser_language'])
    {
      $browser_language = @substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
      foreach ($interface_languages as $interface_language)
      {
        if (substr($interface_language['code'], 0, 2) == $browser_language)
        {
          $language = $interface_languages[$interface_language['code']];
          break;
        }
      }
    }
  }

  return $language;
}

/**
 * same as above but only returns the id
 */
function get_current_language_id()
{
  $language = null;

  if (isset($_SESSION['language']))
  {
    $language = $_SESSION['language'];
  }
  else
  {
    $language = get_current_language();
  }

  return $language['id'];
}

/**
 * returns available languages
 */
function get_interface_languages()
{
  global $db, $conf, $cache;

  if (isset($cache['interface_languages']))
  {
    return $cache['interface_languages'];
  }

  $query = '
SELECT id_language AS id,
       code,
       name
  FROM '.LANG_TABLE.'
  WHERE interface = "true"
  ORDER BY name
;';
  $result = $db->query($query);
  $interface_languages = array();
  while ($row = $db->fetch_assoc($result))
  {
    $interface_languages[$row['code']] = $row;
  }

  $cache['interface_languages'] = $interface_languages;

  return $cache['interface_languages'];
}
