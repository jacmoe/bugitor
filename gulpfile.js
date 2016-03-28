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
*	Copyright (c) 2009 - 2016 Jacob Moen
*	Licensed under the MIT license
*/

// Load plugins
var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var browsersync = require('browser-sync');
var rimraf = require('rimraf');
var yargs = require('yargs');
var yaml = require('js-yaml');
var fs = require('fs');

// Check for --production flag
const PRODUCTION = !!(yargs.argv.production);

// Load settings from settings.yml
var config = loadConfig();

function loadConfig() {
  ymlFile = fs.readFileSync('config.yml', 'utf8');
  return yaml.load(ymlFile);
}


var sassOptions = {
  errLogToConsole: true,
  outputStyle: 'expanded',
  includePaths: config.PATHS.sass
};

var autoprefixerOptions = {
  browsers: config.COMPATIBILITY
};


// Styles
function styles() {
  return gulp.src('assets/src/scss/all.scss')
    .pipe($.sourcemaps.init())
    .pipe($.sass(sassOptions).on('error', $.sass.logError))
    .pipe($.autoprefixer(autoprefixerOptions))
    .pipe($.sourcemaps.write('.', { sourceRoot: '../../assets/src/scss/' }))
    .pipe(gulp.dest('assets/dist/css'))
    .pipe($.if('*.css', $.rename({ suffix: '.min' })))
    .pipe($.if('*.css', $.cssnano()))
    .pipe($.if('*.css', gulp.dest('assets/dist/css')))
    //.pipe($.if('*.css', $.notify({ message: 'Styles task complete' })));
};

// Scripts
function scripts() {
  return gulp.src(config.PATHS.javascript)
    .pipe($.sourcemaps.init())
    .pipe($.concat('all.js'))
    .pipe($.sourcemaps.write('.', { sourceRoot: '../../assets/src/js/' }))
    .pipe(gulp.dest('assets/dist/js'))
    .pipe($.if('*.js', $.rename({ suffix: '.min' })))
    .pipe($.if('*.js', $.uglify()))
    .pipe($.if('*.js', gulp.dest('assets/dist/js')))
    //.pipe($.if('*.js', $.notify({ message: 'Scripts task complete' })));
};

// Copy fonts
function fonts() {
  return gulp.src(config.PATHS.fonts)
    .pipe(gulp.dest(config.PATHS.dist + '/fonts'));
};

// Clean
function clean(done) {
    rimraf(config.PATHS.dist, done);
}

// Build the "assets/dist" folder by running all of the above tasks
gulp.task('build', gulp.series(
  clean,
  gulp.parallel(styles, scripts, fonts)
));

// Watch
function watch() {

  // Initialize Browsersync
  browsersync.init({
    proxy: config.PROXY
  });

  // Watch .scss files
  gulp.watch('assets/src/scss/**/*.scss', styles);

  // Watch .js files
  gulp.watch('assets/src/js/**/*.js', scripts);

  // Watch any view files in 'views', reload on change
  gulp.watch(['views/**/*.php']).on('change', browsersync.reload);

  // Watch any files in 'assets/dist', reload on change
  gulp.watch(['assets/dist/js/*']).on('change', browsersync.reload);
  gulp.watch(['assets/dist/css/*']).on('change', browsersync.reload);
};

gulp.task('default', gulp.series('build', watch));
