/**
 * Compile SASS, concat and minify with CSS.
 **/
const config = require('./config');
const gulp = require('gulp');
const sass = require('gulp-sass');
const minify = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');
const concat = require('gulp-concat');

gulp.task('styles:sass', () =>
    gulp.src(config.source.sass)
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(concat('styles.min.css'))
        .pipe(minify())
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(config.public.css))
);

gulp.task('styles', ['styles:sass'], (cb) => cb());
