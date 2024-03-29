<?php
#
# Murmur.php is generated by calling
# slice2php /path/to/Murmur.ice
# in this directory
#

if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != "test" || $_SERVER['PHP_AUTH_PW'] != "test") {
    header('WWW-Authenticate: Basic realm="MumbleHost"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Invalid User!';
    exit;
}

if (Ice_intversion() >= 30400) {
  if (!stream_resolve_include_path('Ice.php')) {
		exit('The required file Ice.php could not be found in the PHP include path(s).');
  }
  if (!stream_resolve_include_path('Murmur.php')) {
		exit('The required file Murmur.php could not be found in the PHP include path(s).');
  }
  require 'Ice.php';
  require 'Murmur.php';
} else {
  Ice_loadProfile();
}

  $contentMain = 
    "<table class=\"table\">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Port</th>
        <th>Status</th>
        <th>User</th>
        <th></th>
      </tr>";

try {
  if (Ice_intversion() >= 30400) {
    $ICE = Ice_initialize();
  }

  $base = $ICE->stringToProxy("Meta:tcp -h 127.0.0.1 -p 6502");
  $meta = $base->ice_checkedCast("::Murmur::Meta");

  $servers = $meta->getAllServers();
  $default = $meta->getDefaultConf();

  foreach($servers as $s) {

    $contentMain .= "<tr>";
    $contentMain .=  "<td>".$s->id()."</td>";

    $name = $s->getConf("registername");
    if (! $name) {
      $name =  $default["registername"];
    }


    $contentMain .=  "<td>".$name."</td>";

    //List Port
    $port = $s->getConf("port");
    if (! $port) {
      $port =  $default["port"];
    }

    $contentMain .=  "<td>".$port."</td>";

    if($s->isRunning())
    {
      $contentMain .=  "<td>running</td>";
      $u = $s->getUsers();
      $contentMain .=  "<td>".count($u)."</td>";
      $contentMain .=  "<td>";
      $contentMain .=  "<a class=\"btn btn-warning\" href=\"control.php?action=stop&id=".$s->id()."\"><span class=\"glyphicon glyphicon-stop\"/></a>";
      $contentMain .=  "<a class=\"btn btn-default\" href=\"configure.php?id=".$s->id()."\"><span class=\"glyphicon glyphicon-edit\"/></a>";
      $contentMain .=  "<a class=\"btn btn-warning\" href=\"control.php?action=destroy&id=".$s->id()."\"><span class=\"glyphicon glyphicon-remove\"/></a>";
      $contentMain .=  "</td>";
    }
    else
    {
      $contentMain .=  "<td>stoped</td>";
      $contentMain .=  "<td>-</td>";
      $contentMain .=  "<td>";
      $contentMain .=  "<a class=\"btn btn-primary\" href=\"control.php?action=start&id=".$s->id()."\"><span class=\"glyphicon glyphicon-play\"/></a>";
      $contentMain .=  "<a class=\"btn btn-default\" href=\"configure.php?id=".$s->id()."\"><span class=\"glyphicon glyphicon-edit\"/></a>";
      $contentMain .=  "<a class=\"btn btn-warning\" href=\"control.php?action=destroy&id=".$s->id()."\"><span class=\"glyphicon glyphicon-remove\"/></a>";
      $contentMain .=  "</td>";
    }

    $contentMain .=  "</tr>";
  }
} catch (Ice_LocalException $ex) {
  print($ex);

  $contentMain.="</table>";


}

$templateMain = file_get_contents("./template/main.thtml");
$templateMain = str_replace("{{title}}", "Serverübersicht", $templateMain);
$templateMain = str_replace("{{content}}", $contentMain, $templateMain);
echo $templateMain;

?>
