/**
 * Tasks for files management.
 **/
const config = require('./config');
const gulp = require('gulp');

gulp.task('copy:fonts', () =>
    gulp
        .src([config.bootstrap.fonts])
        .pipe(gulp.dest(config.public.fonts))
);

gulp.task('copy:bootstrap', () =>
    gulp
        .src(config.bootstrap.css)
        .pipe(gulp.dest(config.public.css))
);
    
gulp.task('copy', ['copy:fonts', 'copy:bootstrap'], (cb) => cb());

