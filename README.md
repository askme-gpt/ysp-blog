# yaf-blog
### 用到的拓展和包：
基于yaf搭建的博客系统，用到的框架和包版本：
php ： ^7.0
拓展 ： 
"yaf":^3.2.0,
redis ，mysqli这两个无特殊要求；

下面的包都是用composer来管理，方便后期升级和维护：
"eftec/bladeone": "^3.43",
> blade模板，可选择启用缓存，仓库地址：https://github.com/EFTEC/BladeOne ，自认为比smarty好用很多，因为很喜欢laravel的模板引擎，所以选了这个，如果你习惯smarty，可以切换到add_smarty分支

"catfan/medoo": "^1.7"
> 轻量级的数据库连接，参考地址：https://medoo.in/api/select ，之所以选用这个，是因为项目比较简单，我需要速度极快的数据库连接，这个可以达到我的要求，使用起来也相对简单；

"hightman/xunsearch": "^1.4"
>支持中文的全文搜索引擎php客户端，服务器端参考下面两个链接：
https://blog.csdn.net/zhezhebie/article/details/105928625
http://www.xunsearch.com/doc/php/guide/ini.guide

目前就这几个包。后续验证码，图片处理之类的只用简单的库文件就行了。

### TODO
后期准备添加sphinx支持，参考文章：
https://www.jianshu.com/p/513413143b80



本想使用elasticsearch，es对服务器要求比较高，不太划算，如果你想要用，可以参考：
https://github.com/elastic/elasticsearch-php