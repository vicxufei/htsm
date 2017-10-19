var gulp = require('gulp');
var concat = require('gulp-concat');
var header = require('gulp-header');
var connect = require("gulp-connect");
var less = require("gulp-less");
var autoprefixer = require('gulp-autoprefixer');
var ejs = require("gulp-ejs");
var uglify = require('gulp-uglify');
var ext_replace = require('gulp-ext-replace');
var cssmin = require('gulp-cssmin');
//var htmlminify = require("gulp-html-minify");
var htmlmin = require('gulp-htmlmin');
var tmodjs = require('gulp-tmod');
var rev = require('gulp-rev');
var revCollector = require('gulp-rev-collector');

var pkg = require("./package.json");

var banner =
  "/** \n\
  * jQuery WeUI V" + pkg.version + " \n\
* By 言川\n\
* http://lihongxun945.github.io/jquery-weui/\n \
*/\n";

gulp.task('js', function(cb) {

  count = 0;
  var end = function(){
    count ++;
    if(count >= 3) cb();
  };

  // gulp.src([
  //   './src/js/city-data.js',
  //   './src/js/city-picker.js'
  // ])
  //   .pipe(concat({ path: 'city-picker.js'}))
  //   .pipe(gulp.dest('./dist/js/'))
  //   .on("end", end);

  // gulp.src([
  //    'js/swiper.js',
  //   'js/swiper-init.js',
  //   'js/photo-browser.js'
  // ])
  //   .pipe(concat({ path: 'swiper.js'}))
  //   .pipe(gulp.dest('./dist/js/'))
  //   .on("end", end);

  gulp.src([
    './src/js/lib/zepto1.1.6.min.js',
    './src/js/sui/intro.js',
    './src/js/sui/util.js',
    './src/js/sui/zepto-adapter.js',
    './src/js/sui/device.js',
    './src/js/sui/fastclick.js',
    './src/js/sui/modal.js',
    //'./src/js/sui/calendar.js',
    //'./src/js/sui/picker.js',
    //'./src/js/sui/datetime-picker.js',
    './src/js/sui/iscroll.js',
    './src/js/sui/scroller.js',
    './src/js/sui/tabs.js',
    //'./src/js/sui/fixed-tab.js',
    './src/js/sui/pull-to-refresh-js-scroll.js',
    './src/js/sui/pull-to-refresh.js',
    './src/js/sui/infinite-scroll.js',
    './src/js/sui/searchbar.js',
    // './src/js/sui/panels.js',
    './src/js/sui/router.js',
    './src/js/sui/last-position.js',
    './src/js/sui/init.js',
    './src/js/sui/scroll-fix.js',
    //扩展
    './src/js/sui/swiper.js',
    './src/js/sui/swiper-init.js',
    //合并几个10kb以下的js文件
    './src/js/lib/store.min.js',
    './src/js/lib/blazy.min.js'

  ])
    .pipe(concat({ path: 'sm.js'}))
    //.pipe(header(banner))
    .pipe(gulp.dest('./dev/js/'))
    .on("end", end);
});

gulp.task('myjs', function() {
  gulp.src([
    './src/js/template.js','./src/js/functions.js','./src/js/index.js'
  ])
    .pipe(concat('main.js'))
    .pipe(gulp.dest('./dev/js/'))
});


gulp.task('uglify', function() {
  gulp.src('dev/js/*.js')
    .pipe(uglify({
      preserveComments: "license"
    }))
    .pipe(ext_replace('.min.js'))
    // .pipe(rev())
    .pipe(gulp.dest('./js/'));
    // .pipe(rev.manifest())                                   //- 生成一个rev-manifest.json
    // .pipe(gulp.dest('./dev/rev/js'));
});

gulp.task('less', function () {
  return gulp.src(['./src/less/*.less'])
    .pipe(less())
    // .pipe(base64({
    //   baseDir: 'public',
    //   extensions: ['svg', /\.jpg#datauri$/i],
    //   exclude:    [/\.server\.(com|net)\/dynamic\//, '--live.jpg'],
    //   maxImageSize: 8*1024, // 小于8KB
    //   debug: true
    // }))
    .pipe(autoprefixer())
    //.pipe(header(banner))
    .pipe(gulp.dest('./dev/css/'));
});

gulp.task('cssmin', ["less"], function () {
  gulp.src(['./dev/css/*.css', '!./css/*.min.css'])
    .pipe(cssmin())
    .pipe(ext_replace('.min.css'))
    // .pipe(rev())
    .pipe(gulp.dest('./css/'));
    // .pipe(rev.manifest())                                   //- 生成一个rev-manifest.json
    // .pipe(gulp.dest('./dev/rev/css'));
});

// gulp.task('rev', function() {
//   gulp.src(['./dev/rev/*/*.json', './*.html'])  //- 读取 rev-manifest.json 文件以及需要进行css名替换的文件
//     .pipe(revCollector())    //- 执行文件内css名的替换
//     .pipe(gulp.dest('./'));    //- 替换后的文件输出的目录-设置为替换
// });

// gulp.task('ejs', function () {
//   return gulp.src(["./src/pages/*.html", "!./src/pages/_*.html"])
//     .pipe(ejs({}))
//     .pipe(gulp.dest("./"));
// });


gulp.task('page', function () {
  var options = {
    removeComments: true,//清除HTML注释
    collapseWhitespace: true,//压缩HTML
    collapseBooleanAttributes: true,//省略布尔属性的值 <input checked="true"/> ==> <input />
    removeEmptyAttributes: true,//删除所有空格作属性值 <input id="" /> ==> <input />
    removeScriptTypeAttributes: true,//删除<script>的type="text/javascript"
    removeStyleLinkTypeAttributes: true,//删除<style>和<link>的type="text/css"
    minifyJS: true,//压缩页面JS
    minifyCSS: true//压缩页面CSS
  };
  gulp.src('./src/*.html')
    .pipe(htmlmin(options))
    .pipe(gulp.dest('./'));
});

gulp.task('copy', function() {
  gulp.src(['./src/img/*.*'])
    .pipe(gulp.dest('./img/'));
});

gulp.task('copy2', function() {
  gulp.src(['./src/less/fonts/*.*'])
    .pipe(gulp.dest('./css/fonts/'));
});


gulp.task('tpl', function(){
  var stream = gulp.src('./src/tpl/*.html')
    .pipe(tmodjs({
      templateBase: './src/tpl/'
    }))
    .pipe(gulp.dest('./src/js'));
  return stream;
});

gulp.task('watch', function () {
  gulp.watch('src/tpl/*.html', ['tpl']);
  gulp.watch('src/js/*.js', ['myjs']);
  gulp.watch('dev/js/*.js', ['uglify']);
  gulp.watch('src/less/**/*.less', ['less']);
  gulp.watch('dev/css/*.css', ['cssmin']);
  gulp.watch('src/*.html', ['page']);
  gulp.watch('src/img/*.*', ['copy']);
  // gulp.watch('dev/rev/*/*.json', ['rev']);
});

gulp.task('server', function () {
  connect.server();
});
gulp.task("default", ['tpl', 'uglify', 'cssmin', 'copy','copy2', 'page']);