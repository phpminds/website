'use strict';
 
var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var minifyCss = require('gulp-minify-css');
var uglify = require("gulp-uglify");
var concat = require("gulp-concat");
var gulpServiceWorker = require('gulp-serviceworker');
var rename = require('gulp-rename');

gulp.task('generate-service-worker', ['default'], function() {
    return gulp.src(['public/*'])
        .pipe(gulpServiceWorker({
            rootDir: 'public/'
        }));
});

gulp.task('sass', function () {
  gulp.src('./build/sass/**/+(*.scss|*.sass)')
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle:'compressed'}).on('error', sass.logError))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('./public/css'));
});
 
gulp.task('sass:watch', function () {
  gulp.watch('./build/sass/**/+(*.scss|*.sass)', ['sass']);
});

var paths = {
 scripts: [
     'node_modules/src/Modernizr.js',
     'build/js/app.js'
 ],
 dist: 'public/js/'
};


// gulp.task('build-jquery', function(){
//     gulp.src('node_modules/detached-jquery-2.1.4/js/index.js')
//         .pipe(rename('node_modules/detached-jquery-2.1.4/js/jquery.min.js'))
//         .pipe(uglify())
//         .pipe(gulp.dest(paths.dest));
// });

gulp.task('bundle-js', function(){
    gulp.src(paths.scripts)
     .pipe(concat('libs.js'))
     .pipe(uglify())
    .pipe(gulp.dest("./public/js/"));
});

var autoprefixer = require('gulp-autoprefixer');

gulp.task('prefix', function () {
    return gulp.src('build/text.sass')
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(gulp.dest('public/css/text.css'));
});



gulp.task('minify-css', function() {
    return gulp.src('public/css/*.css')
        .pipe(minifyCss({compatibility: 'ie8'}))
        .pipe(gulp.dest('public/css/'));
});
gulp.task('default',['sass:watch','bundle-js','prefix']);
