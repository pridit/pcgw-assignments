var gulp = require('gulp'),
    sass = require('gulp-sass'),
    image = require('gulp-image'),
    order = require("gulp-order"),
    concat = require('gulp-concat'),
    minify = require('gulp-minify'),
    minifyCSS = require('gulp-csso');

var paths = {
  css: {
    src: 'resources/assets/css/*.css',
    dest: 'public/css/'
  },
  image: {
    src: 'resources/assets/images/**/*',
    dest: 'public/images/'
  },
  js: {
    src: 'resources/assets/js/*.js',
    dest: 'public/js/'
  },
  scss: {
    src: 'resources/assets/sass/**/*.scss'
  }
};

gulp.task('css', function(){
  return gulp.src(paths.css.src)
    .pipe(minifyCSS())
    .pipe(concat('vendor.min.css'))
    .pipe(gulp.dest(paths.css.dest))
});

gulp.task('image', function () {
  return gulp.src(paths.image.src)
    .pipe(image())
    .pipe(gulp.dest(paths.image.dest));
});

gulp.task('js', function(){
  return gulp.src(paths.js.src)
    .pipe(order([
      'jquery.min.js',
      'bootstrap.min.js',
      'jquery-ui.min.js',
      'moment.js',
      'livestamp.min.js',
      'sweetalert.min.js',
      'sorttable.js'
    ]))
    .pipe(concat('vendor.js'))
    .pipe(minify({
        ext:{
            src: '.js',
            min: '.min.js'
        }
    }))
    .pipe(gulp.dest(paths.js.dest))
});

gulp.task('scss', function () {
  return gulp.src(paths.scss.src)
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
    .pipe(concat('app.min.css'))
    .pipe(gulp.dest(paths.css.dest))
});

gulp.task('watch', function () {
  gulp.watch(paths.scss.src, ['scss']);
});

gulp.task('default', ['css', 'js', 'scss', 'image']);
