<?php
if (Ice_intversion() >= 30400) {
  if (!stream_resolve_include_path('Ice.php')) {
		exit('The required file Ice.php could not be found in the PHP include path(s).');
  }
  if (!stream_resolve_include_path('Murmur.php')) {
		exit('The required file Murmur.php could not be found in the PHP include path(s).');
  }
  require 'portFunctions.php';
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


  $action = $_GET['action'];
  $id = $_GET['id'];

  if($action == "start")
  {
  	$s = $meta->getServer((int)$id);
  	$s->start();
  	header("Location: index.php");
	die();
  }
  else if($action == "stop")
  {
  	$s = $meta->getServer((int)$id);
  	$s->stop();
  	header("Location: index.php");
	die();
  }
  else if($action == "configure")
  {
  	$config = $_GET["config"];

  	$s = $meta->getServer((int)$id);
  	foreach ($config as $key => $c) {
      if($key == "serverpassword" && $c == "")
      {

      }
      else
      {
        $s->setConf($key, $c);
      }
  		
  	}
  	header("Location: index.php");
	die();
  }
  else if($action == "new")
  {
    $config = $_GET["config"];
    $s = $meta->newServer();
    foreach ($config as $key => $c) {
      $s->setConf($key, $c);
    }
    $s->start();

    setPortUsed($config['port']);
    
    header("Location: index.php");

  die();
  }
  else if($action == "destroy")
  {
    $s = $meta->getServer((int)$id);

    $port = $s->getConf('port');

    $s->stop();
    
    $s->delete();

    setPortUnused($port);
    
    header("Location: index.php");

  die();
  }
} catch (Ice_LocalException $ex) {
  print($ex);
}

?>