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

// Hacking attempt
if(!defined('INTERNAL'))
{
  die('No right to do that, sorry. :)');
}

if (!isAdmin(@$user['id']))
{
  header('Location: '.$root_path);
  exit;
}
