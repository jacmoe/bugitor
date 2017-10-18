/**
 * Tasks for cleaning all the output and/or temporary folder.
 **/
const config = require("./config");
const gulp = require('gulp');
const clean = require('gulp-clean');

gulp.task('clean', () =>
    gulp.src([config.public.root]).pipe(clean({ force: true }))
);
