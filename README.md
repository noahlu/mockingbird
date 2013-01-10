# Mockingbird

Mockingbird is a light-weighted website-building system far more than merely a blog system. There is a admin dashboard where you can configure your site or add/remove/modify html files easily.


## Benefits
* Search Engine Optimizaiont
> Let your sites be extremely SEO friendly.

* Better Performance
> With all pages html file, they make your site even faster than ever.

## Basic Assumption
* one sub-category only
> we assume your sites' subcategories to be one level. Limit your sites' subcategories will alse improve SEO ability.

## How-Tos
* 默认登陆超时session为10个小时，在/src/check.php 里修改；
* 数据库信息在/src/dbConfig.php 里设置；
* 日志摘要留空，则日志不会出现在日志列表页面；
* 日志文件名不能是index
* 删除一个分类的方法：先把分类下的日志全部删除（此时日志的html也都删除了），再删除此分类（分类下的index被删除，然后目录被删除）  
* 注册用户有三种状态：1. 未激活邮箱（inactive）2.已激活能正常使用（active）3.需要审核（waitApproved）
* 保存日志之后会自动发布日志的html文件，并更新同目录下的index.htm文件
* 使用本系统需要修改网站服务器为，以apache(httpd.conf)为例，把网站根目录设置为htdocs目录，admin目录设置为上层的src目录：
<VirtualHost *:80>
	ServerName blog.noahlu.com
	ServerAlias blog.noahlu.com
	DocumentRoot /Users/luhua/mysites/blogDev/src/htdocs
	Alias /admin /Users/luhua/mysites/blogDev/src
	<Directory /Users/luhua/mysites/blogDev/src>
		Options Indexes MultiViews
		AllowOverride None
		Order allow,deny
		Allow from all
	</Directory>
</VirtualHost>
* 必须设置htdocs目录为777权限（所有用户可读、写、运行）


