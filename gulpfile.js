/**
 * Created by yefeng on 16/4/6.
 */
var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var requirejsOptimize = require('gulp-requirejs-optimize');
var input = 'src/scss/*.scss';
var output = 'dist/css';

var panini = require('panini');
// Copy page templates into finished HTML files
gulp.task('pages', function() {
  gulp.src('src/pages/**/*.{html,hbs,handlebars}')
    .pipe(panini({
      root: 'src/pages/',
      layouts: 'src/layouts/',
      partials: 'src/partials/',
      data: 'dist/rev/js',
      helpers: 'src/helpers/'
    }))
    .pipe(gulp.dest('dist'));
});

gulp.task('pages:reset', function(cb) {
  panini.refresh();
  gulp.run('pages');
  cb();
});

gulp.task('style', function () {
  return gulp
    .src(input)
    .pipe($.sass())
    .pipe($.autoprefixer({
      browsers: [
        'ie >= 9',
        'ie_mob >= 10',
        'ff >= 30',
        'chrome >= 34',
        'safari >= 7',
        'opera >= 23',
        'ios >= 7',
        'android >= 4.4',
        'bb >= 10'],
      cascade: false
    }))
  //.pipe(gulp.dest(output))
    .pipe($.sourcemaps.init())
    .pipe(
      $.postcss([
        require('postcss-sorting')({ "sort-order": "zen" }),
//        http://cssnano.co/options/
        require('cssnano')
      ])
    )
    .pipe($.rename({suffix: '.min'}))
    .pipe($.rev())
    .pipe($.sourcemaps.write('../map'))
    .pipe(gulp.dest(output))
    .pipe($.rev.manifest())                                   //- 生成一个rev-manifest.json
    .pipe(gulp.dest('./dist/rev/css'));
});

gulp.task('rev', function() {
  gulp.src(['./dist/rev/*/*.json', './dist/**/*.html'])  //- 读取 rev-manifest.json 文件以及需要进行css名替换的文件
    .pipe($.revCollector())    //- 执行文件内css名的替换
    .pipe(gulp.dest('./dist'));    //- 替换后的文件输出的目录-设置为替换
});


gulp.task('scripts', function () {
  return gulp.src('src/js/app/*.js')
    .pipe(requirejsOptimize({
      mainConfigFile: 'src/js/common.js'
    }))
    .pipe($.rev())
    .pipe(gulp.dest('dist/js'))
    .pipe($.rev.manifest())
    .pipe(gulp.dest('./dist/rev/js'));
});

//https://github.com/kangax/html-minifier
gulp.task('minify', function() {
  return gulp.src('dist/**/*.html')
    .pipe($.htmlmin({
      removeComments: true,//清除HTML注释
      collapseWhitespace: true //压缩HTML
    }))
    .pipe(gulp.dest('./dist'))
});

gulp.task('watch', function() {
  gulp.watch(input, ['style']);
  gulp.watch(['src/{layouts,pages,partials}/**/*.html'], ['pages:reset']);
  gulp.watch('.dist/rev/*.json', ['pages:reset','rev']);
});

// The default task (called when you run `gulp` from cli)
gulp.task('build', ['pages', 'scripts', 'style','rev']);


