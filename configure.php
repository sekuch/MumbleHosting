
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

$id = $_GET['id'];

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

try {
  if (Ice_intversion() >= 30400) {
    $ICE = Ice_initialize();
  }

  $base = $ICE->stringToProxy("Meta:tcp -h 127.0.0.1 -p 6502");
  $meta = $base->ice_checkedCast("::Murmur::Meta");

  $server = $meta->getServer((int) $id);
  $default = $meta->getDefaultConf();

 $name = $server->getConf("registername");
  if (! $name) {
    $name =  $default["registername"];
  }


  $inputs.= "<div class=\"input-group\">";
  $inputs .= "<span class=\"input-group-addon\">Titel</span>";
  $inputs .= "<input type=\"text\" class=\"form-control\" name=\"config[registername]\" value=\"".$name."\" width='150px'/>";
  $inputs .= "</div>";

  $inputs .= "<div class=\"input-group\">";
  $inputs .= "<span class=\"input-group-addon\">Server Passwort</span>";
  $inputs .= "<input type=\"text\" class=\"form-control\" name=\"config[serverpassword]\" value=\"\"/>";
  $inputs .= "</div>";


} catch (Ice_LocalException $ex) {
  print($ex);
}


  $content = "<form action=\"control.php\" method=\"get\">
    <input type=\"hidden\" name=\"action\" value=\"configure\" />";
  $content .= "<input type=\"hidden\" name=\"id\" value=\"".$id."\" />";

  $content .= $inputs;

  $content .= 
  "</td></tr><tr><td><input type=\"submit\" class=\"btn btn-primary\" value=\"Speichern\"/>
    <input type=\"reset\" class=\"btn btn-warning\" value=\"Abbrechen\"/>
    </form>";

  $templateMain = file_get_contents("./template/main.thtml");

$templateMain = str_replace("{{title}}", "Configuration: ".$name, $templateMain);
  $templateMain = str_replace("{{content}}", $content, $templateMain);
  echo $templateMain;




?>
    
