#rootCMS
# 交流
* 官方QQ群：
* 官方支持站点：[http://www.rootcms.cn](http://www.rootcms.cn)

----
#环境要求
* PHP版本需要5.4+以上才可以。

----
#rootCMS简介 
* rootCMS 基于[ThinkPHP5](http://www.thinkphp.cn)框架开发，采用独立分组的方式开发的内容管理系统。
* 支持模块安装/卸载，模型自定义，整合UCenter通行证等。
* 同时系统对扩展方面也支持比较大，可以使用内置的行为控制，对现有功能进行扩展。

## 目录结构

目录结构如下：

~~~
/  WEB部署目录（或者子目录）
├─extend                  rootCMS系统扩展类库文件
│  ├─behavior             行为类库
│  │  ├─ ...              行为类库文件
│  ├─driver               服务驱动
│  │  ├─ ...              服务驱动与servic对应
│  ├─service              服务类库目录
│  │  ├─Attachement.php   附件上传服务
│  │  └─Passport.php      用户登录认证服务
│  ├─system               系统扩展类库
│  │  ├─ ...              系统扩展类库文件
│  └─util                 普通扩展类库
│     ├─ ...              系统扩展类库文件
├─rootcms                 rootCMS系统app目录
│  ├─addon                扩展模块目录
│  │  ├─ ...              一个扩展模块对应一个目录
│  ├─application          应用目录
│  │  ├─admin             系统后台模块
│  │  │  ├─controller     控制器目录
│  │  │  ├─model          模型目录
│  │  │  ├─view           视图目录
│  │  │  └─ ...           更多类库目录
│  │  ├─api               系统api目录
│  │  ├─common            系统公用模块目录
│  │  ├─content           系统内容模块目录
│  │  ├─ ...              更多系统模块
│  │  └─command.php       全局公用函数文件
│  ├─config               系统配置目录
│  │  ├─config.php        全局配置文件
│  │  ├─route_admin.php   后台路由配置文件
│  │  ├─route_content.php 前台路由配置文件
│  │  ├─route_api.php     api路由配置文件
│  │  ├─alias.php         类库别名配置文件
│  │  ├─tags.php          行为配置文件
│  │  ├─extend.php        扩展配置文件
│  │  ├─database.php      数据库配置文件
│  │  └─version.php       系统版本置文件
│  ├─core                 框架系统目录
│  │  ├─lang              语言文件目录
│  │  ├─library           框架类库目录
│  │  │  ├─think          Think类库包目录
│  │  │  └─traits         系统Trait目录
│  │  ├─tpl               系统模板目录
│  │  ├─base.php          基础定义文件
│  │  ├─console.php       控制台入口文件
│  │  ├─convention.php    框架惯例配置文件
│  │  ├─helper.php        助手函数文件
│  │  ├─phpunit.xml       phpunit配置文件
│  │  └─start.php         框架入口文件
│  ├─cron                 系统cron定时执行任务
│  └─common.php           公共函数文件
├─statics                 WEB公用文件目录
│  ├─admin                后台模板公用文件，如css，js，font文件存放目录
│  │  └─default           后台default模板，公用文件
│  └─index                网站前台模板公用文件
│      └─default          前台default模板，公用文件
├─template                系统模板目录
│  ├─admin                后台模板文件
│  │  └─default           后台default模板
│  │      ├─admin         后台模块admin的模板目录
│  │      ├─common        后台公用模块common模板目录
│  │      ├─preview.jpg   后台default模板示例图片
│  │      ├─error.php     后台错误提示模板文件
│  │      └─success.php   后台成功提示模板文件
│  └─index                网站前台模板公用目录
│      └─default          前台default模板，公用文件
│          ├─content      前台default模板的内容显示模板目录
│          ├─member       前台default模板的会员显示模板目录
│          ├─preview.jpg  前台default模板示例图片
│          ├─error.php    前台错误提示模板文件
│          └─success.php  前台成功提示模板文件
├─#runtime                运行时目录（可写，可定制）
├─LICENSE                 授权说明文件
├─README.md               README 文件
├─index.php               入口文件
└─.htaccess               用于apache的重写
~~~