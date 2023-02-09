<?php
require __DIR__ . '/vendor/autoload.php';

//udpxy服务器
$udpxy = 'http://192.168.6.1:5002/udp/';
//截图目录
$imgPath = __DIR__ . '/img';
//扫描的ip地址
$arr_ip = [
    '225.1.1.1',
    '225.1.2.1'
];
//扫描的端口号
$arr_port = [
    5002
];
//配置ffmpeg
$ffmpeg = FFMpeg\FFMpeg::create([
    'timeout' => 60,
    'ffmpeg.binaries' => '/usr/bin/ffmpeg',
    'ffprobe.binaries' => '/usr/bin/ffprobe'
]);
//加载数据
$data = file_get_contents(__DIR__ . '/iptv.json');
$data = json_decode($data, true);

//01_cli扫描
if (php_sapi_name() === 'cli') {
    foreach ($arr_ip as $subip) {
        $subip = substr($subip, 0, -1);
        for ($i = 1; $i < 255; $i++) {
            $ip = $subip . $i;
            foreach ($arr_port as $port) {
                $key = $ip . '_' . $port;
                echo $ip . ':' . $port;
                $imgName = $imgPath . '/' . $ip . '_' . $port . '.jpg';
                $height = probe($ffmpeg, $udpxy, $ip, $port, $imgName);
                echo $height > 0 ? " \033[32mtrue\033[0m" : " \033[31mfalse\033[0m";
                echo PHP_EOL;
                $imgTime = is_file($imgName) ? filemtime($imgName) : 0;
                if (isset($data[$key])) {
                    $data[$key]['videoHeight'] = $height;
                    $data[$key]['imgTime'] = $imgTime;
                } else {
                    if ($height > 0) {
                        $data[$key] = [
                            'ip' => $ip,
                            'port' => $port,
                            'title' => 'new',
                            'tvg-name' => '',
                            'group-title' => 'other',
                            'videoHeight' => $height,
                            'imgTime' => $imgTime,
                            'sort' => '9999',
                            'state' => '0'
                        ];
                    }
                }
            }
        }
    }
    file_put_contents(__DIR__ . '/iptv.json', json_encode($data));
    exit();
}

//02_修改数据
if (isset($_GET['id'])) {
    $height = 0;
    $data[$_GET['id']]['title'] = $_POST['title'];
    $data[$_GET['id']]['tvg-name'] = $_POST['tvg-name'];
    $data[$_GET['id']]['group-title'] = $_POST['group-title'];
    $data[$_GET['id']]['sort'] = $_POST['sort'];
    $data[$_GET['id']]['state'] = $_POST['state'];
    file_put_contents(__DIR__ . '/iptv.json', json_encode($data));
    echo $height;
    exit();
}

//03_展示数据
$sort = [];
foreach ($data as $key => $value) {
    $sort[$key] = $value['sort'];
}
asort($sort);
$str = '#EXTM3U' . PHP_EOL;
foreach ($sort as $key => $value) {
    $datum = $data[$key];
    if ($datum['state'] == 1) {
        $str .= '#EXTINF:-1 ';
        if ($datum['tvg-name'] != '') {
            $str .= 'tvg-id="' . $datum['tvg-name'] . '" tvg-name="' . $datum['tvg-name'] . '" ';
        }
        $str .= 'group-title="' . $datum['group-title'] . '",' . $datum['title'] . PHP_EOL;
        $str .= $udpxy . $datum['ip'] . ':' . $datum['port'] . PHP_EOL;
    }
}
echo trim($str);

//利用ffmpeg截图
function probe($ffmpeg, $udpxy, $ip, $port, $imgName): int
{
    $imgName_s = substr($imgName, 0, -4) . 's.jpg';
    try {
        $video = $ffmpeg->open($udpxy . $ip . ':' . $port);
        $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(1))->save($imgName);
        $img = new imagick($imgName);
        $height = $img->getImageHeight();
        $img->thumbnailImage(200, 0);
        file_put_contents($imgName_s, $img);
        chmod($imgName, 0776);
        chmod($imgName_s, 0776);
        return $height;
    } catch (Exception $e) {
        return 0;
    }
}
