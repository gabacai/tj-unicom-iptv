## 自助管理天津联通IPTV电视频道

### 0. 文件

iptv.php:扫描电视频道,生成M3U playlist.  
epg.php:下载节目单整理生成epg.xml文件.  
index.html:电视频道管理文件.通过浏览器访问.  
iptv.json:电视频道数据文件.  

### 1. 配置

修改文件 iptv.php 变量参数  
```
composer install
npm install
php -S 0.0.0.0:8088
```

### 2. 使用
#### 扫描频道
```
php iptv.php
```
#### 获取节目单
```
php epg.php
```
#### 管理频道
访问http://server:8088  
频道表单内容依次为:  
 * 自定义频道名称  
 * 节目单频道名称  
 * 节目单  
 * 分组名称  
 * 频道序号  
 * 频道状态  

#### 配置kodi下iptv插件
电视频道地址:http://server:8088/iptv.php  
电视节目指南地址:http://server:8088/epg.xml  

### 3. 演示
如果你觉得上面操作难以理解,可以使我整理过的频道  
<https://gabacai.github.io/iptv/>
