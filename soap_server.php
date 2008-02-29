<?php
// +-----------------------------------------------------------------------+
// | PEM - a PHP based Extension Manager                                   |
// | Copyright (C) 2005-2006 PEM Team - http://home.gna.org/pem            |
// +-----------------------------------------------------------------------+
// | last modifier : $Author: plg $
// | revision      : $Revision: 2 $
// +-----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify  |
// | it under the terms of the GNU General Public License as published by  |
// | the Free Software Foundation                                          |
// |                                                                       |
// | This program is distributed in the hope that it will be useful, but   |
// | WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU      |
// | General Public License for more details.                              |
// |                                                                       |
// | You should have received a copy of the GNU General Public License     |
// | along with this program; if not, write to the Free Software           |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, |
// | USA.                                                                  |
// +-----------------------------------------------------------------------+

define('INTERNAL', true);
$root_path = './';
require_once($root_path.'include/common.inc.php');
require_once($root_path.'include/nusoap/nusoap.php');
$ns = $conf['website_url'].'/wsdl';

// First we must include our NuSOAP library and define the namespace of the
// service. It is usually recommended that you designate a distinctive URI
// for each one of your Web services.
$server = new soap_server();
$server->configureWSDL('getRevisionList',$ns);
$server->wsdl->schemaTargetNamespace = $ns;

// Next step, we instantiate the SOAP server and define the settings for our
// WSDL file such as the service name and the namespace.
$server->wsdl->addComplexType(
  'Revision',
  'complexType',
  'struct',
  'all',
  '',
  array(
    'revision_id' => array(
      'name' => 'revision_id',
      'type' => 'xsd:int'
      ),
    'extension_name' => array(
      'name' => 'registration_date',
      'type' => 'xsd:string'
      ),
    'extension_description' => array(
      'name' => 'registration_date',
      'type' => 'xsd:string'
      ),
    'revision_name' => array(
      'name' => 'revision_name',
      'type' => 'xsd:string'
      ),
    'revision_date' => array(
      'name' => 'revision_date',
      'type' => 'xsd:string'
      ),
    'revision_url' => array(
      'name' => 'revision_url',
      'type' => 'xsd:string'
      ),
    'revision_description' => array(
      'name' => 'revision_description',
      'type' => 'xsd:string'
      ),
    )
);

$server->wsdl->addComplexType(
  'RevisionList',
  'complexType', 
  'array', 
  '', 
  'SOAP-ENC:Array', 
  array(),
  array(
    array(
      'ref' => 'SOAP-ENC:arrayType',
      'wsdl:arrayType' => 'tns:Revision[]'
      )
  ),
  'tns:Revision'
);

$server->register(
  'getRevisionList',
  array(
   'version' => 'xsd:string',
   'category_id' => 'xsd:int',
    ),
  array(
    'result' => 'tns:RevisionList'
    ),
  $ns
);

function getRevisionList($version, $category_id) {
  global $db, $conf;
  
  $version = mysql_real_escape_string($version);

  if ($category_id != abs(intval($category_id))) {
    die('unexpected category identifier');
  }

  $query = '
SELECT
    r.id_revision         AS revision_id,
    r.version             AS revision_name,
    e.id_extension        AS extension_id,
    e.name                AS extension_name,
    e.description         AS extension_description,
    r.date                AS revision_date,
    r.url                 AS revision_url,
    r.description         AS revision_description
  FROM '.REV_TABLE.' AS r
    INNER JOIN '.EXT_TABLE.'      AS e  ON e.id_extension = r.idx_extension
    INNER JOIN '.COMP_TABLE.'     AS c  ON c.idx_revision = r.id_revision
    INNER JOIN '.VER_TABLE.'      AS v  ON v.id_version = c.idx_version
    INNER JOIN '.EXT_CAT_TABLE.'  AS ec ON ec.idx_extension = e.id_extension
  WHERE ec.idx_category = '.$category_id.'
    AND v.version = \''.$version.'\'
;';

  $revisions = array();
  $result = $db->query($query);
  while ($row = mysql_fetch_assoc($result)) {
    $row['revision_date'] = date('Y-m-d H:i:s', $row['revision_date']);

    $row['revision_url'] = sprintf(
      '%s/%s',
      $conf['website_url'],
      get_revision_src(
        $row['extension_id'],
        $row['revision_id'],
        $row['revision_url']
        )
      );
    
    array_push($revisions, $row);
  }

  // echo '<pre>'; print_r($revisions); echo '<pre/>';

  return $revisions;
}

//Then we invoke the service using the following line of code:
$HTTP_RAW_POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA'])
  ? $GLOBALS['HTTP_RAW_POST_DATA']
  : ''
;
$server->service($HTTP_RAW_POST_DATA);
exit();
?>
