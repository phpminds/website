'use strict';
 
var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
 
gulp.task('sass', function () {
  gulp.src('./build/sass/**/*.scss')
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle:'compressed'}).on('error', sass.logError))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('./public/css'));
});
 
gulp.task('sass:watch', function () {
  gulp.watch('./build/sass/**/*.scss', ['sass']);
});

var paths = {
 scripts: ['bower_components/jquery/dist/jquery.min.js','bower_components/foundation/js/foundation.min.js','bower_components/foundation/js/foundation/foundation.topbar.js'], 
 dist: 'public/js/'
};

gulp.task('move', function(){
 gulp.src(paths.scripts)
 .pipe(gulp.dest(paths.dist));
});

gulp.task('default',['sass:watch','move']); 
