<?php
include __DIR__.'/iptv.php';
$sort = [];
foreach($data as $key => $value){
    $sort[$key] = $value['sort'];
}
asort($sort);
echo '#EXTM3U'.PHP_EOL;
foreach($sort as $key=>$value){ 
    $datum = $data[$key];
    if($datum['state'] == 1){
        echo '#EXTINF:-1 ';
	if($datum['tvg-name'] != ''){
            echo 'tvg-id="'.$datum['tvg-name'].'" tvg-name="'.$datum['tvg-name'].'" ';	
	}
	echo 'group-title="'.$datum['group-title'].'",'.$datum['title'].PHP_EOL;
	echo $server_url.$datum['ip'].':'.$datum['port'].PHP_EOL;
    }
} 
