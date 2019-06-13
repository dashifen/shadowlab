const gulp = require('gulp');
const bump = require('gulp-bump');

gulp.task('bump', function (complete) {
  gulp.src('./composer.json').pipe(bump()).pipe(gulp.dest('./'));
  gulp.src('./package.json').pipe(bump()).pipe(gulp.dest('./'));
  complete();
});