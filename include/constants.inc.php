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

define( 'EXT_TABLE',        $conf['db_params']['tables_prefix'].'extensions' );
define( 'CAT_TABLE' ,       $conf['db_params']['tables_prefix'].'categories' );
define( 'VER_TABLE',        $conf['db_params']['tables_prefix'].'versions' );
define( 'REV_TABLE',        $conf['db_params']['tables_prefix'].'revisions' );
define( 'COMP_TABLE',       $conf['db_params']['tables_prefix'].'revisions_compatibilities' );
define( 'EXT_CAT_TABLE',    $conf['db_params']['tables_prefix'].'extensions_categories' );
define( 'USER_INFOS_TABLE', $conf['db_params']['tables_prefix'].'user_infos' );
define( 'LINKS_TABLE',      $conf['db_params']['tables_prefix'].'links' );
define( 'AUTHORS_TABLE',    $conf['db_params']['tables_prefix'].'authors' );
define( 'LANG_TABLE',       $conf['db_params']['tables_prefix'].'languages' );
define( 'REV_LANG_TABLE',   $conf['db_params']['tables_prefix'].'revisions_languages' );
define( 'EXT_TRANS_TABLE',  $conf['db_params']['tables_prefix'].'extensions_translations' );
define( 'REV_TRANS_TABLE',  $conf['db_params']['tables_prefix'].'revisions_translations' );
define( 'CAT_TRANS_TABLE',  $conf['db_params']['tables_prefix'].'categories_translations' );
define( 'TAG_TABLE' ,       $conf['db_params']['tables_prefix'].'tags' );
define( 'TAG_TRANS_TABLE' , $conf['db_params']['tables_prefix'].'tags_translations' );
define( 'EXT_TAG_TABLE',    $conf['db_params']['tables_prefix'].'extensions_tags' );
define( 'RATE_TABLE',       $conf['db_params']['tables_prefix'].'rates' );
define( 'REVIEW_TABLE',     $conf['db_params']['tables_prefix'].'reviews' );

define( 'USERS_TABLE',      $conf['users_table'] );

define(
  'DOWNLOAD_LOG_TABLE',
  $conf['db_params']['tables_prefix'].'download_log'
  );


define('EXTENSIONS_PER_PAGE', 3);
define('PUN_ROOT', __DIR__.'/../');
define('PUN_TURN_OFF_MAINT', 1);
define('PUN_QUIET_VISIT', 1);
