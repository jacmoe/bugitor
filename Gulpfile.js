/*
* This file is part of
*  _                 _ _
* | |__  _   _  __ _(_) |_ ___  _ __
* | '_ \| | | |/ _` | | __/ _ \| '__|
* | |_) | |_| | (_| | | || (_) | |
* |_.__/ \__,_|\__, |_|\__\___/|_|
*              |___/
*                 issue tracker
*
*	Copyright (c) 2010 - 2016 Jacob Moen
*	Licensed under the MIT license
*/
// fix problems with undefined Promise class
// http://stackoverflow.com/questions/32490328/gulp-autoprefixer-throwing-referenceerror-promise-is-not-defined
require('es6-promise').polyfill();

// Load plugins
var gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    cssnano = require('gulp-cssnano'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    cache = require('gulp-cache'),
    browsersync = require('browser-sync'),
    sourcemaps = require('gulp-sourcemaps'),
    del = require('del'),
    gulpif = require('gulp-if'),
    runSequence = require('run-sequence');

var sassOptions = {
  errLogToConsole: true,
  outputStyle: 'expanded'
};

var autoprefixerOptions = {
  browsers: ['last 2 versions', '> 5%', 'Firefox ESR']
};


// Styles
gulp.task('styles', function() {
  return gulp.src('scss/all.scss')
    .pipe(sourcemaps.init())
    .pipe(sass(sassOptions).on('error', sass.logError))
    .pipe(autoprefixer(autoprefixerOptions))
    .pipe(sourcemaps.write('.', { sourceRoot: '../../scss/' }))
    .pipe(gulp.dest('assets/dist/css'))
    .pipe(gulpif('*.css', rename({ suffix: '.min' })))
    .pipe(gulpif('*.css', cssnano()))
    .pipe(gulpif('*.css', gulp.dest('assets/dist/css')))
    .pipe(gulpif('*.css', notify({ message: 'Styles task complete' })));
});

// Scripts
gulp.task('scripts', function() {
  return gulp.src(require('./js/all.json'))
    //.pipe(jshint('.jshintrc'))
    //.pipe(jshint.reporter('default'))
    .pipe(sourcemaps.init())
    .pipe(concat('all.js'))
    .pipe(sourcemaps.write('.', { sourceRoot: '../../js/' }))
    .pipe(gulp.dest('assets/dist/js'))
    .pipe(gulpif('*.js', rename({ suffix: '.min' })))
    .pipe(gulpif('*.js', uglify()))
    .pipe(gulpif('*.js', gulp.dest('assets/dist/js')))
    .pipe(gulpif('*.js', notify({ message: 'Scripts task complete' })));
});

// Images
gulp.task('images', function() {
  return gulp.src('img/**/*')
    .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
    .pipe(gulp.dest('assets/dist/img'))
    .pipe(notify({ message: 'Images task complete' }));
});

// Copy fonts
gulp.task('fonts', function() {
  return gulp.src(
    [   'vendor/bower/bootstrap-sass/assets/fonts/bootstrap/*',
        'vendor/bower/font-awesome/fonts/*'
    ])
    .pipe(gulp.dest('./assets/dist/fonts'));
});

// Clean
gulp.task('clean', function() {
  return del(['assets/dist/css/*', 'assets/dist/js/*', 'assets/dist/fonts/*']);
});

// Build the "assets/dist" folder by running all of the above tasks
gulp.task('build', function(callback) {
  runSequence('clean', ['styles', 'scripts', 'fonts'], callback);
});

// Watch
gulp.task('watch', function() {

  // Initialize Browsersync
  browsersync.init({
    proxy: "https://bugitor.dev"
  });

  // Watch .scss files
  gulp.watch('scss/**/*.scss', ['styles']);

  // Watch .js files
  gulp.watch('js/**/*.js', ['scripts']);

  // Watch image files
  //gulp.watch('img/**/*', ['images']);

  // Watch any view files in 'views', reload on change
  gulp.watch(['views/**/*.php']).on('change', browsersync.reload);

  // Watch any files in 'assets/dist', reload on change
  gulp.watch(['assets/dist/js/*']).on('change', browsersync.reload);
  gulp.watch(['assets/dist/css/*']).on('change', browsersync.reload);
});

gulp.task('default', ['build', 'watch'], function() {});
