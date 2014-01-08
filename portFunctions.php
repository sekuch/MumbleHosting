<?php



function readPorts()
{
	$my_file = 'ports.txt';
	$handle = fopen($my_file, 'r');
	$data = fread($handle,filesize($my_file));
	return $data;
}	

function writePorts($ports)
{
	$my_file = 'ports.txt';
	$handle = fopen($my_file, 'w');

	$data = "";

	foreach ($ports as $port) {
		$data .= $port[0].";".$port[1]."\n";
	}

	$data = substr($data, 0, -1); 

	fwrite($handle, $data);
}

function getAvailablePorts()
{
	$data = readPorts();
	$lines = explode("\n", $data);
	$array = array(  );
	foreach ($lines as $line) {
		$line = split(";", $line);
		if($line[1] == 0)
		{
	 		$array[] = $line;
	 	}
	 }

	return $array;
}

function setPortUsed( $usePort )
{
	$data = readPorts();
	$lines = explode("\n", $data);
	$array = array(  );
	foreach ($lines as $line) {
		$line = split(";", $line);
		if($line[0] == $usePort)
		{
			$line[1] = 1;
	 	}
	 	$array[] = $line;
	}

	print_r($array);

	writePorts($array);
}

function setPortUnused( $usePort )
{
	$data = readPorts();
	$lines = explode("\n", $data);
	$array = array(  );
	foreach ($lines as $line) {
		$line = split(";", $line);
		if($line[0] == $usePort)
		{
			$line[1] = 0;
	 	}
	 	$array[] = $line;
	}

	print_r($array);

	writePorts($array);
}

?>