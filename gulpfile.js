'use strict';

var gulp = require('gulp'),
    rename = require('gulp-rename'),
    sass = require('gulp-sass'),
    concat = require('gulp-concat'),
    nano = require('gulp-cssnano'),
    rimraf = require('gulp-rimraf'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps = require('gulp-sourcemaps'),
    jshint = require('gulp-jshint');   

var sassOptions = {
  errLogToConsole: true,
  outputStyle: 'expanded'
};

// sass source map
function buildCss() {
  return gulp
    .src('./scss/style.scss')
    .pipe(sourcemaps.init())
    .pipe(sass(sassOptions).on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(sourcemaps.write('./maps/'))
    .pipe(gulp.dest('./css/'));
};

function buildMinCss() {
  return gulp.src('./css/style.css')
        .pipe(nano())
        .pipe(rename({ extname: '.min.css' }))
        .pipe(gulp.dest('./css/'))
}

function watchCss() {
  gulp.watch('./scss', ['build-css']);
}

function cleanDist() {
  return gulp.src('./css/style.css', { read: false })
        .pipe(rimraf());
}

function lintJs() {
  return gulp.src('js/src/*.js')
  .pipe(jshint())
  .pipe(jshint.reporter('default'))
};


gulp.task('lint-js', lintJs);
gulp.task('build-css', buildCss);
gulp.task('build-min-css', ['build-css'], buildMinCss);
gulp.task('build-prod', ['build-min-css'], cleanDist);
gulp.task('watch-css', ['build-css'], watchCss);
gulp.task('default', ['watch-css']);
