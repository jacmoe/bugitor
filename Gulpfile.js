// fix problems with undefined Promise class
// http://stackoverflow.com/questions/32490328/gulp-autoprefixer-throwing-referenceerror-promise-is-not-defined
require('es6-promise').polyfill();

// Load plugins
var gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-minify-css'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    cache = require('gulp-cache'),
    browsersync = require('browser-sync'),
    sourcemaps = require('gulp-sourcemaps'),
    del = require('del');

    var sassOptions = {
      errLogToConsole: true,
      outputStyle: 'expanded'
    };

    var autoprefixerOptions = {
      browsers: ['last 2 versions', '> 5%', 'Firefox ESR']
    };


// Styles
gulp.task('styles', function() {
  return gulp
    .src('scss/all.scss')
    .pipe(sourcemaps.init())
    .pipe(sass(sassOptions).on('error', sass.logError))
    .pipe(autoprefixer(autoprefixerOptions))
    .pipe(gulp.dest('frontend/web/css'))
    .pipe(gulp.dest('backend/web/css'))
    .pipe(rename({ suffix: '.min' }))
    .pipe(minifycss())
    .pipe(sourcemaps.write('../css/',{includeContent: false, sourceRoot: '../scss/'}))
    .pipe(gulp.dest('frontend/web/css'))
    .pipe(gulp.dest('backend/web/css'))
    .pipe(notify({ message: 'Styles task complete' }));
});

// Scripts
gulp.task('scripts', function() {
  return gulp.src(require('./js/all.json'))
    //.pipe(jshint('.jshintrc'))
    //.pipe(jshint.reporter('default'))
    .pipe(sourcemaps.init())
    .pipe(concat('all.js'))
    .pipe(gulp.dest('frontend/web/js'))
    .pipe(gulp.dest('backend/web/js'))
    .pipe(rename({ suffix: '.min' }))
    .pipe(uglify())
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('frontend/web/js'))
    .pipe(gulp.dest('backend/web/js'))
    .pipe(notify({ message: 'Scripts task complete' }));
});

// Images
gulp.task('images', function() {
  return gulp.src('img/**/*')
    .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
    .pipe(gulp.dest('web/img'))
    .pipe(notify({ message: 'Images task complete' }));
});

// Copy fonts
gulp.task('fonts', function() {
  gulp.src(['vendor/bower/bootstrap/fonts/*','scss/2-vendors/fontawesome/fonts/*', 'scss/2-vendors/fonts/josefine-sans/*'])
  .pipe(gulp.dest('./web/fonts'));
});

// Clean
gulp.task('clean', function(cb) {
    del(['frontend/web/css', 'frontend/web/js'], cb)
    del(['backend/web/css', 'backend/web/js'], cb)
});

// Build the "web" folder by running all of the above tasks
gulp.task('build', ['clean', 'styles', 'scripts'], function() {});

// Watch
gulp.task('watch', function() {

  // Initialize Browsersync
  browsersync.init({
    proxy: "http://bugitor.dev"
  });

  // Watch .scss files
  gulp.watch('scss/**/*.scss', ['styles']);

  // Watch .js files
  gulp.watch('js/**/*.js', ['scripts']);

  // Watch image files
  //gulp.watch('img/**/*', ['images']);

  // Watch any view files in 'views', reload on change
  gulp.watch(['frontend/views/**/*.php']).on('change', browsersync.reload);
  gulp.watch(['backend/views/**/*.php']).on('change', browsersync.reload);

  // Watch any files in 'web', reload on change
  gulp.watch(['frontend/web/js/*']).on('change', browsersync.reload);
  gulp.watch(['backend/web/js/*']).on('change', browsersync.reload);
  gulp.watch(['frontend/web/css/*']).on('change', browsersync.reload);
  gulp.watch(['backend/web/css/*']).on('change', browsersync.reload);
});

gulp.task('default', ['build', 'watch'], function() {});
