<?php
//加载epg
$str = file_get_contents('https://epg.112114.xyz/pp.xml.gz?t='.time());
$str = gzdecode($str);
$xml = simplexml_load_string($str);

//加载iptv数据
$str = file_get_contents(__DIR__ . '/iptv.json');
$iptv = json_decode($str, true);
$tvg = [];
foreach($iptv as $v){
    if($v['tvg-name'] !== ''){
        array_push($tvg, $v['tvg-name']);
    }
}

//筛选频道
$c1 =  $xml->channel->count();
$j = 0;
for($i=0;$i<$c1;$i++){
    $arr = json_decode(json_encode($xml->channel[$j]), true);
    if(in_array($arr['@attributes']['id'], $tvg)){
        $j++;
    } else {
        unset($xml->channel[$j]);
    }
}

//筛选节目单
$c2 =  $xml->programme->count();
$k = 0;
for($i=0;$i<$c2;$i++){
    $arr = json_decode(json_encode($xml->programme[$k]), true);
    if(in_array($arr['@attributes']['channel'], $tvg)){
        $k++;
    } else {
        unset($xml->programme[$k]);
    }
}

//保存节目单
$str = str_ireplace("\t", '', str_ireplace(PHP_EOL, '', $xml->asXML()));
file_put_contents(__DIR__ . '/epg.xml', $str);
