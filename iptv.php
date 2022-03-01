<?php
$server_url = 'http://192.168.6.1:5002/udp/';
$arr_ip = [
    '225.1.1.1',
    '225.1.2.1'
];
$arr_port = [5002];
$path = __DIR__;
$j = file_get_contents($path.'/iptv.json');
$arr_j = json_decode($j, true);

$files = scandir($path.'/img');
foreach($files as $file){
    if($file != '.' && $file != '..'){
	unlink($path.'/'.$file);
    }
}
foreach($arr_ip as $subip){
    $subip = substr($subip, 0, -1);
    for($i=1; $i<255; $i++){
	$ip = $subip . $i;
	foreach($arr_port as $port){
	    $url = $server_url . $ip . ':' . $port;
	    $key = $ip . '_' . $port;
	    $img = $path . '/img/' . $key . '.jpg';
	    $str = 'ffmpeg -i '. $url . ' -loglevel quiet -t 0.001 -y -f image2 -vcodec mjpeg '. $img;
	    shell_exec($str);
	    if(!$is_file($img)){
		continue;
	    }
	}
    }




}
print_r($j);
