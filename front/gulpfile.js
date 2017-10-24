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

// Html (Pug)
function html() {
  return gulp.src(config.PATHS.src + '/index.pug')
    .pipe($.if(PRODUCTION, $.replace('main.css', 'main.min.css')))
    .pipe($.pug({pretty: true}))
    .pipe(gulp.dest(config.PATHS.dist));
};

// Elm init
function elm_init() {
  $.elm.init();
};

// Elm compile
function elm_compile() {
    // By explicitly handling errors, we prevent Gulp crashing when compile fails
    function onErrorHandler(err) {
      // No longer needed with gulp-elm 0.5
  // console.log(err.message);
  }
  return gulp.src(config.PATHS.src + '/elm/Main.elm')             // "./src/Main.elm"
	.pipe($.elm({"debug": true}))
	.on('error', onErrorHandler)
	.pipe( $.if(PRODUCTION, $.uglify()) )   // uglify
	.pipe(gulp.dest(config.PATHS.dist));
};

// Styles
function styles() {
  return gulp.src(config.PATHS.src +'/sass/main.scss')
    .pipe($.sourcemaps.init())
    .pipe($.sass(sassOptions).on('error', $.sass.logError))
    .pipe($.autoprefixer(autoprefixerOptions))
    .pipe($.if(PRODUCTION, $.rename({ suffix: '.min' })))
    .pipe($.if(PRODUCTION, $.cssnano()))
    .pipe($.if(!PRODUCTION, $.sourcemaps.write('.', { sourceRoot: '../../src/sass/' })))
    .pipe(gulp.dest(config.PATHS.dist + '/css'))
    .pipe($.notify({ message: 'Styles task complete' }));
};

// Clean
function clean(done) {
    rimraf(config.PATHS.dist, done);
}

// The main build task
gulp.task('build', gulp.series(
  clean,
  gulp.parallel(elm_compile, styles, html)
));

// Watch
function watch() {

  // Initialize Browsersync
  browsersync.init({
		server: {
			baseDir: "./dist"
		}
  });

  // Watch .elm files
  gulp.watch(config.PATHS.src + '/elm/**/*.elm', elm_compile);
  // Watch .pug file
  gulp.watch(config.PATHS.src + '/index.pug', html);
  // Watch .scss files
  gulp.watch(config.PATHS.src + '/scss/**/*.scss', styles);
  gulp.watch([config.PATHS.dist + '/*']).on('change', browsersync.reload);
  // Watch any files in 'assets/dist', reload on change
  gulp.watch([config.PATHS.dist + '/css/*']).on('change', browsersync.reload);
};

// Default task runs build and then watch
gulp.task('default', gulp.series('build', watch));

// Export these functions to the Gulp client
gulp.task('clean', clean);
gulp.task('styles', styles);
gulp.task('html', html);
gulp.task('elm-init', elm_init);
gulp.task('elm-compile', elm_compile);
  