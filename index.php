<?php
include __DIR__.'/iptv.php';

//echo $path.PHP_EOL;
//print_r($data);
//die;



if($_POST){
    $ip = $_GET['ip'];
    $port = $_GET['port'];
    $id = $ip . '_' . $port;
    $data[$id]['title'] = $_POST['title'];
    $data[$id]['tvg-name'] = $_POST['tvg-name'];
    $data[$id]['group-title'] = $_POST['group-title'];
    $data[$id]['state'] = $_POST['state'];
    $data[$id]['sort'] = $_POST['sort'];
    $j = json_encode($data);
    echo $j;
    file_put_contents($path.'/iptv.json', $j);
    exit();
}




$sort = [];
foreach($data as $key => $value){
    $sort[$key] = $value['sort'];
}

asort($sort);
?><!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="data:image/ico;base64,aWNv">
    <title>Document</title>
    <style>
        body {
            margin: 10px;
        }

        p {
            margin: 0px;
            height: 24px;
        }
        a{
            text-decoration: none;
        }
	.m1{
            float: left;
            width: 220px;
            height: 360px;
        }
	.m2{
            margin-left: 10px;
            margin-bottom: 12px;
            height: 160px;
        }
    </style>
    <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
    <script type="application/javascript">
function update(id, ip, port){
    data = {
        "title": $('#'+id+'_title').val(),
	"tvg-name": $('#'+id+'_tvg-name').val(),
	"group-title": $('#'+id+'_group-title').val(),
	"sort": $('#'+id+'_sort').val(),
	"state": $('#'+id+'_state').val()
    }
    $.post('./index.php?ip='+ip+'&port='+port, data);
}

    </script>
</head>
<body>
<div><?php 
$count = 0;
foreach($sort as $key=>$value){ 
$datum = $data[$key];
$img = '/img/' . $datum['ip'] . '_' . $datum['port'] . '.jpg';
if(!is_file($path . $img)){
    continue;
}
$id = 'n'.$count;
?>

    <div class="m1">
        <div class="m2">
            <a target="_blank" href=".<?php echo $img;?>">
                <img style="width: 200px;" src=".<?php echo $img;?>"/>
	    </a>
        </div>
	<p>
            <a href="http://192.168.6.1:5002/udp/<?php echo $datum['ip'] . ':' . $datum['port']; ?>">
	    <?php echo $datum['ip'] . ':' . $datum['port'] ?></a>[<?php echo $datum['videoHeight']?>]
        </p>
	<p><input id="<?php echo $id;?>_title" type="text" value="<?php echo $datum["title"]; ?>"/></p>
	<p><input id="<?php echo $id?>_tvg-name" type="text" value="<?php echo $datum['tvg-name'];?>"/></p>
	<p><input id="<?php echo $id?>_group-title" type="text" value="<?php echo $datum['group-title'];?>"/></p>
	<p><input id="<?php echo $id?>_sort" type="text" value="<?php echo $datum['sort'];?>" /></p>
	<p><input id="<?php echo $id?>_state" type="text" value="<?php echo $datum['state'];?>" /></p>
	<p style="padding-right: 26px;" align="right"><a href="javascript:void(0);" onclick="update(<?php echo "'{$id}', '{$datum['ip']}', {$datum['port']}";?>)" >保存</a></p>
    </div><?php 
$count++;
} ?>

</div>
</body></html>
