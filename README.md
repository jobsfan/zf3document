# zf3document
I wanna to invite some developers to work with me to build a zf3 drived and with admin UI foreign tech documents translation system.   
php官方框架的翻译项目，如果侵权请告知   

## 源码储存在src文件夹 ##

## 2018年的小目标 ##
1、一个简易的后台UI，用之前的，整合进来   
2、简单的登录，注册模块   
3、简易的授权   
4、采集待翻译数据   
5、翻译逻辑搭建   

## To do list ##
1. 用zf3框架搭建一个网站 www.wendangs.com   
2. 简易首页
3. 数据库的建立
4. 注册页  
5. 登陆页
6. 授权控制   
7. 常用插件的继承，比如blueimp，uploadify，时间插件等   
....   

## 目标 ##
1. 建成一个zend framework版的通用后台    
2. 前端采用bootstrap，更多前端插件，见项目中具体说明   
3. 数据库结构公开，代码以及资源公开，任何人都可以下载并使用   
4. 外文文档的翻译，当然先定一个小目标，就是zend framework 3的翻译   
5. 代码和网站 www.wendangs.com 同步，所有贡献者的代码被merge后都会在网站呈现出来   
6. 文档pdf下载服务   
...   

## 数据库 ##   
数据库在 doc/database-sample 里面，来自官网的demo，我先是用sqlite3进行尝试，后面用mysql替代了sqlite3，原先的sqlite3你可以在data这个目录中找   
...   

## 有感 ##  
官方教程blog有点小复杂，它通过ControllerFactory来new了一个Controller，通过key【PostRepositoryInterface】指向了过渡key别名【ZendDbSqlRepository】的ZendDbSqlRepositoryFactory，在这个factory里面实例化了一个ZendDbSqlRepository，把这个repository实例传到controller的构造函数，用以进行增删改查   

## 贡献者除了会在项目代码的注释中有体现外，还会在网站显眼位置有公示 ##