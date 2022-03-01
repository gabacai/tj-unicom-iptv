<?php
$path = __DIR__;
$j = file_get_contents($path.'/iptv.json');
$data = json_decode($j, true);
$sort = [];



foreach($data as $key => $value){

    $sort[$key] = $value['sort'];
}
asort($sort);
//print_r($sort);
//print_r($data);
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
            height: 410px;
        }
	.m2{

            margin-left: 10px;
            margin-bottom: 12px;
            height: 160px;
        }
    </style>
    <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
    <script type="application/javascript">
function update(ip, port){
	console.log(ip);
	console.log(port);
    id = '#n_'+ip+'_'+port+'_title';
	console.log(id);
	console.log($(id).val());








}
/*
        function update(str) {
            let arr = str.split('_');
            let ip = arr[0];
            let port = arr[1];
            let id = arr[2];

            //console.log();
            //return;
            let data = {
                //'ip': ip,
                //'port': port,
                'title': $('#title_'+id).val(),
                'tvg-name': $('#channelName_'+id).val(),
                'tvg-code': $('#channelCode_'+id).val(),
                'group-title': $('#class_'+id).val(),
                'sort': $('#sort_'+id).val(),
                'tvg-id': $('#epgId_'+id).val(),
                //'epgName': $('#epgName_'+id).val(),
                'state': $("input[name='state_"+id+"']:checked").val()
            };
            //console.log(data);
            $.post('/iptv-show?ip='+ip+'&port='+port, data, function (result) {
                console.log(result);
            });
        }
 */
    </script>
</head>
<body>
<div><?php foreach($sort as $key=>$value){ 
$datum = $data[$key];
$img = '/img/' . $datum['ip'] . '_' . $datum['port'] . '.jpg';
if(!is_file($path . $img)){
    continue;
}
$id = 'n_'.$datum['ip'].'_'.$datum['port'];
?>

    <div class="m1">
        <div class="m2">
            <a target="_blank" href="<?php echo $img;?>">
                <img style="width: 200px;" src="<?php echo $img;?>"/>
	    </a>
        </div>
	<p>
            <a href="http://192.168.6.1:5002/udp/<?php echo $datum['ip'] . ':' . $datum['port']; ?>">
                    <?php echo $datum['ip'] . ':' . $datum['port'] ?></a>
        </p>
	<p><input id="<?php echo $id;?>_title" type="text" value="<?php echo $datum["title"]; ?>"/></p>
	<p><input id="<?php echo $id?>_tvg-name" type="text" value="<?php echo $datum['tvg-name'];?>"/></p>
	<p><input id="<?php echo $id?>_sort" type="text" value="<?php echo $datum['sort'];?>" /></p>
	<p><input id="<?php echo $id?>_state" type="text" value="<?php echo $datum['state'];?>" /></p>
	<p style="padding-right: 26px;" align="right"><a href="javascript:void(0);" onclick="update('<?php echo $datum['ip'];?>', <?php echo $datum['port'];?>)" >保存</a></p>
    </div><?php } ?>

</div>
</body></html>
