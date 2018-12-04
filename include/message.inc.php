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

if (!defined('INTERNAL'))
{
  die('No right to do that, sorry. :)');
}
if (!isset($tpl->files['page']))
{
  $tpl->set_filename('page', 'page.tpl');
}
$tpl->set_filename('message', 'message.tpl');

if ($page['message']['is_success'])
{
  if (!isset($page['message']['title']))
  {
    $page['message']['title'] = l10n('Success');
  }

  if (!isset($page['message']['time_redirect']))
  {
    $page['message']['time_redirect'] = $conf['time_redirect'];
  }

  if (isset($page['message']['redirect']))
  {
    $tpl->assign(
      array(
        'time_redirect' => $page['message']['time_redirect'],
        'u_redirect' => $page['message']['redirect'],
        'meta' =>
          sprintf(
            '<meta http-equiv="refresh" content="%s;url=%s">',
            $page['message']['time_redirect'],
            $page['message']['redirect']
            ),
        )
      );
  }
  $page['message']['go_back'] = false;
}
else
{
  if (!isset($page['message']['title']))
  {
    $page['message']['title'] = l10n('Error');
  }

  if (!isset($page['message']['go_back']))
  {
    $page['message']['go_back'] = true;
  }
}

$tpl->assign(
  array(
    'message_title' => $page['message']['title'],
    'message_text' => $page['message']['message'],
    'go_back' => $page['message']['go_back'],
    )
  );

// +-----------------------------------------------------------------------+
// |                           html code display                           |
// +-----------------------------------------------------------------------+

$tpl->assign_var_from_handle('main_content', 'message');
include($root_path.'include/header.inc.php');
include($root_path.'include/footer.inc.php');
$tpl->parse('page');
$tpl->p();
exit();
