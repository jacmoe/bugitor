const config = require('./config');
const gulp = require('gulp');
const elm  = require('gulp-elm');
const minify = require('gulp-minify');

gulp.task('elm-init', elm.init);

gulp.task('elm-bundle', ['elm-init'], () =>
    // separate min
    gulp.src(config.source.elm)
        .pipe(elm.bundle(`${config.output.elmApplicationName}.js`))
        .pipe(minify())
        .pipe(gulp.dest(config.public.js))
);
