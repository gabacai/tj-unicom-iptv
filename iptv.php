<?php
//网关地址
$getway = '192.168.6.1';
//扫描的ip地址
$arr_ip = [
    '225.1.1.1',
    '225.1.2.1'
];
//扫描的端口
$arr_port = [
    5002
];
$path = __DIR__;
$j = file_get_contents($path.'/iptv.json');
$data = json_decode($j, true);
$server_url = 'http://'.$getway.':5002/udp/';

if(php_sapi_name() === 'cli'){
    //抓取节目单
    $epgUrl = 'https://epg.112114.xyz/pp.xml';
    $xml = file_get_contents($epgUrl);
    $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    $programme = $arr['programme'];
    $str = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
    $str .=  '<!DOCTYPE tv SYSTEM "http://api.torrent-tv.ru/xmltv.dtd">'.PHP_EOL;
    $str .=  '<tv generator-info-name="iptv"  generator-info-url="http://localhost/iptv">'.PHP_EOL;
    $channelArr = [];
    foreach($data as $value){
        if($value['tvg-name'] != ''){
            array_push($channelArr, $value['tvg-name']);
	    $str .= '<channel id="'.$value['tvg-name'].'"><display-name lang="zh">'.$value['tvg-name'].'</display-name></channel>'.PHP_EOL;
	}
    }
    foreach($programme as $arr){
	if(in_array($arr['@attributes']['channel'] ,$channelArr)){
	    $title = htmlspecialchars($arr['title']);
	    $str .= "<programme start=\"{$arr['@attributes']['start']}\" stop=\"{$arr['@attributes']['stop']}\" channel=\"{$arr['@attributes']['channel']}\">";
	    $str .= "<title lang=\"zh\">{$title}</title>";
	    $str .= "</programme>".PHP_EOL;
	}
    }
    $str .= '</tv>'.PHP_EOL;
    file_put_contents($path.'/epg.xml', $str);
    //扫描频道
    $files = scandir($path.'/img');
    foreach($files as $file){
        if($file != '.' && $file != '..'){
    	unlink($path.'/img/'.$file);
        }
    }
    foreach($arr_ip as $subip){
        $subip = substr($subip, 0, -1);
        for($i=1; $i<255; $i++){
    	$ip = $subip . $i;
    	    foreach($arr_port as $port){
    	        $url = $server_url . $ip . ':' . $port;
    	        $key = $ip . '_' . $port;
    	        echo $key.PHP_EOL;
    	        $img = $path . '/img/' . $key . '.jpg';
    	        $str = 'ffmpeg -i '. $url . ' -loglevel quiet -t 0.001 -y -f image2 -vcodec mjpeg '. $img;
    	        shell_exec($str);
    	        if(is_file($img) && !isset($data[$key])){
		    $imgInfo = getimagesize($img);
	            $data[$key] = [
	                'title' => 'new',		
			'tvg-name' => '',
			'group-title' => 'other',			
			'videoHeight' => $imgInfo[1],
			'sort' => 9999,
			'state' => 0
		    ];
    	        }
    	    }
        }
    }
    file_put_contents($path.'/iptv.json', json_encode($data));
}
