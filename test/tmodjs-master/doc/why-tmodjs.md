#	进击！前端模板工程化

######	基于 TmodJS 前端模板工程化解决方案

##	前端模板

早期，开发人员都是直接在 js 文件中采用最原始的方式直接拼接 HTML 字符串：

	var html = '';
	for (var i = 0, users = data.users; i < users.length; i ++) {
		html += '<li><a href="'
		+ users[i].url
		+ '">'
		+ users[i].name
		+ '</a></li>';
	}
	//...

这种方式刚开始在一两个简单的页面中还是比较灵活的，但弊端也十分明显：UI 与逻辑代码混杂在一起，阅读起来会非常吃力。一旦随着业务复杂起来，或者多人维护的情况下，几乎会失控。

受 jquery 作者的 tmpl 模板引擎影响，从 09 年开始到现在，前端模板引擎出现了百花齐放的局面，涌现出一大批行色各异的引擎，几乎每个前端工程中都使用了模板引擎。一条前端模板类似这样：

	<script id="user_tmpl" type="text/html">
	{{each users as value}}
		<li>
			<a href="{{value.url}}">{{value.name}}</a>
		</li>
	{{/each}}
	</script>

它使用一个特殊的``<script type="text/html"></script>``标签来存放模板（由于浏览器不支持这种类型的声明，它存放的代码不会当作 js 运行，代码也不会被显示出来）。使用模板引擎渲染模板的示例：

	var html = tmpl('user_tmpl', data);
	document.getElementById('content').innerHTML = html;
	
[在线示例](http://aui.github.io/artTemplate/demo/basic.html)
	
通过前端模板引擎将 UI 分离后，模板的书写与修改就变得简单多了，也提升了可维护性。但是，随着这种方式规模化后其弊端也随之而来。

##	模板内嵌的弊端

### 开发调试

每次修改前端模板需要改动页面的代码，如果不是纯静态页面，无法使用类似 fiddler 的工具将页面映射到本地进行开发，开发调试依赖只能服务端环境，效率低下。

### 自动化构建

在现代 web 前端工程体系中，几乎每一个环节都拥有相应的优化工具，这些几乎都被 grunt 这个自动构建工具连接起来。但是前端模板若内嵌到页面中，复杂度会比较高，现有工具因为受限难以进行自动优化。

### 模块化

前端模板集中在一个文件中这必然会引起多人协作的问题，随着项目复杂度增加，按文件模块化迫在眉睫。

## 现有的模板外置解决方案

目前越来越的项目已经将模板从页面中迁移出来，主要有两种方式：

###	ajax 拉取方案

通过 ajax 加载远程模板，然后再使用模板引擎解析。这种方式的好处就是模板可以按文件存放，书写起来也是十分便利，但弊端相当明显：由于浏览器同源策略限制的，导致模板无法部署到 CDN 网络中。

###	在 JS 文件中存放模板

为避免上述加载模板方案无法跨域的致命缺陷，模板存放在 js 文件中又成了最佳实践方式，但是这种情况下需要对回车符进行转义，对书写不友好，严重影响开发效率。例如：

	var user_tmpl =
	'{{each users as value}}\
		<li>\
			<a href="{{value.url}}">{{value.name}}</a>\
		</li>\
	{{/each}}';
	
或者：

	var user_tmpl =
	 '{{each users as value}}'
	+	'<li>'
	+		'<a href="{{value.url}}">{{value.name}}</a>'
	+	'</li>'
	+'{{/each}}';
	
##	现有模板组织方案优劣总结

组织方式 | 开发效率 | 优化空间 | 本地调试 | 代码复用 | 团队协作
------ | ------ | ------ | ------ | ------ | ------
内嵌业务页中 | ✓ | ✗ | ✗ | ✗ | ✗
Ajax 远程加载 | ✓  | ✗| ✓ | ✓ | ✓
嵌入 js 文件 | ✗ | ✓ | ✓ | ✓ | ✓

总结：方便优化的模式不利于开发；利于开发的模式不利于优化。

## 理想模式

看下服务端模板是如何做的：

一、**模板按文件与目录组织模板**

```
template('tpl/home/main', data)
```

二、**模板使用 include 语句完成复用**

```
{{include '../public/header'}}
```

这一切看起来很美，前端是否也可以采用这样的模式？但是现实告诉我们，这是一个艰巨的任务。

## 现实难题

* 浏览器对文本加载会有跨域限制
* 浏览器同步加载会引起界面卡顿
* 加载大量的模板文件会带来 http 资源消耗问题

## 解决方案

为了实现上述“理想模式”，我们推出了 TmodJS——模板预编译器，以下是它的简介：

> TmodJS（原名 atc）是一个简单易用的前端模板预编译工具。它通过预编译技术让前端模板突破浏览器限制，实现后端模板一样的同步“文件”加载能力。它采用目录来组织维护前端模板，从而让前端模板实现工程化管理，最终保证前端模板在复杂单页 web 应用下的可维护性。同时预编译输出的代码经过多层优化，能够在最大程度节省客户端资源消耗。

它采用三种方案来解决难题：

###	1.本地构建

模板编写完成后，通过一个本地工具将模板编译成浏览器可执行的代码——js，这样就可以用脚本的方式来加载模板，不必受浏览器的同源策略限制，模板可以部署到任意 CDN，而无需处理跨域问题。

工具内部采用模板引擎——[artTemplate](https://github.com/aui/artTemplate) 完成模板编译，输出 js 文件。artTemplate 也是来自腾讯的开源项目，它支持预编译，编译后的代码可以无需引擎运行。

###	2.种子文件

为了实现``template(path, data)``这种同步接口，TmodJS 会不断的更新一个名为 template.js 的种子文件，这个文件合并了公用方法与编译后的模板，项目只需要引用这个文件就可以按路径同步的方式调用模板。例如：

	var tpl = template('home/index');
	var html = tpl(data);
	document.getElementById('content').innerHTML = html;

###	3.模板目录

为了让团队成员、自动化工具能更好的管理模板，前端模板不再内嵌到页面中，而是使用专门的目录进行组织维护；使用路径作为模板的 ID，这样与源文件保持对应关系——这样好处就是极大的增加了可维护性。例如：页面头部底部的公用模板可以放在``tpl/public``目录下，首屏的模板可以放在``tpl/home``下面。

## 模板与工程化

TmodJS 采用了自动编译机制，一经启动后就无需人工干预，每次模板创建与更新都会自动编译，直到正式上线都无需对代码进行任何修改。实现文件系统的前端模板只是 TmodJS 最基本的任务，它在背后还做了这些优化：

###	模板压缩与合并

TmodJS 编译之前会压缩掉模板的空白字符，编译为 js 后又会进行一次压缩，此时输出的 js 甚至会比原始模板更小（最高可减少一半的体积）。

在默认设置下，TmodJS 会将模板目录所有模板编译后再进行压缩与合并，输出后的 template.js 称之为模板包（内部名称叫 TemplateJS 格式）这种打包的方式非常适合移动端单页 WebApp，输出后的模板包可直接可作为开发阶段与线上运行的文件，适合中小型项目。

[查看编译后的模板示例](http://microtrend.cdc.tencent.com/tpl/dist/template.js)

###	依赖管理

当然，将所有前端模板都打包在一个文件中不一定适合每一个项目，因为很多大型项目需要更细致的优化，将模板编译为 AMD、CMD、CommonJS 类型的的模块是一个不错的选择，此时模板内部的``include``语句会编译成``requier('xxx/xxx')``形式声明依赖，接入对应的 grunt 插件可自动完成依赖合并。

###	本地调试

因为模板已经被独立出来，所以使用 fiddler 将线上模板映射到本地进行开发调试将十分容易。如果线上模板报错，开启 TmodJS 的``debug``模式后可以直接找到出错的模板路径以及所在行号，例如：

	Template Error

	<id>
	public/header

	<name>
	Render Error

	<message>
	Cannot read property '0' of undefined

	<line>
	5

	<source>
	{{users[0].name}}
	
###	沙箱与扩展

很多开发者习惯在模板中访问页面中全局定义的函数，如果模板内嵌到页面中问题似乎不大，一旦模板外置后这种隐含的依赖关系将会导致严重的维护问题，TmodJS 采用沙箱机制来解决此问题：限制开发者访问外部对象，模板用到的所有变量在闭包中被强制指向模板数据。

为了方便扩展模板的功能，可指定一个外部 js 作为公用方法（辅助方法），这个 js 会被合并到到 template.js 中。

###	安全过滤

模板的变量输出默认都会被过滤函数包裹，在运行时进行过滤，从而避免模板开发者因为疏忽导致站点 XSS 漏洞。例如：

模板

	<h3>{{title}}</h3>

编译代码

	"<h3>" + $escape(title) + "</h3>"
	
###	与第三方自动化构建工具配合

目前 TmodJS 已有 grunt 与 gulp 这两种流行的自动化构建工具的插件，未来将支持更多的自动化工具。
	
###	前后端模板共享

TmodJS 与 artTemplate 模板引擎使用同样的模板语法，而 artTemplate 提供了 NodeJS 版本，可以直接读取 TmodJS 的模板目录，这意味着可以轻松的做到前后端模板共享，技术方案自由切换。

##	成果

组织方式 | 开发效率 | 优化空间 | 本地调试 | 代码复用 | 团队协作
------ | ------ | ------ | ------ | ------ | ------
TmodJS | ✓ | ✓ | ✓ | ✓ | ✓


##	关于 TmodJS

起源于腾讯内部公用组件平台的开源项目（atc），历经无数次的改进，目前已经有多个项目在使用。

<https://github.com/aui/tmodjs>

###	案例

*	QQ空间
*	腾讯视频
*	爱奇艺
*	爱拍原创
*	Spa（迅雷）
*	MicroTrend（腾讯）
*	Tracker（腾讯）
*	UR（腾讯）
*	……

###	愿景

忘记前端模板。

