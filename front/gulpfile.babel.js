require('require-dir')('./gulp');

const gulp = require('gulp');
const runSequence = require('run-sequence');

gulp.task('build', (cb) =>
    runSequence(['elm-bundle', 'styles'], 'copy', () => cb())
);

gulp.task('rebuild', ['clean'], (cb) =>
    runSequence(['elm-bundle', 'styles'], 'copy', () => cb())
);

gulp.task('default', () =>
    console.log('No default, buddy!')
);
