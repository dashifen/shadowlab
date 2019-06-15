const gulp = require('gulp');
const bump = require('gulp-bump');

gulp.task('bump', function (complete) {
  gulp.src('./docroot/wp-content/themes/shadowlab/style.css').pipe(bump())
    .pipe(gulp.dest('./docroot/wp-content/themes/shadowlab/'));

  gulp.src('./docroot/wp-content/plugins/cheatsheet/style.css').pipe(bump())
    .pipe(gulp.dest('./docroot/wp-content/plugins/cheatsheet/'));

  gulp.src('./composer.json').pipe(bump()).pipe(gulp.dest('./'));
  gulp.src('./package.json').pipe(bump()).pipe(gulp.dest('./'));
  complete();
});