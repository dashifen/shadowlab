const gulp = require('gulp');
const bump = require('gulp-bump');

gulp.task('bump', function (complete) {
  gulp.src('./docroot/wp-content/themes/shadowlab/style.css').pipe(bump())
    .pipe(gulp.dest('./docroot/wp-content/themes/shadowlab/'));

  gulp.src('./docroot/wp-content/plugins/shadowlab-cheatsheets/index.php').pipe(bump())
    .pipe(gulp.dest('./docroot/wp-content/plugins/shadowlab-cheatsheets/'));

  gulp.src('./composer.json').pipe(bump()).pipe(gulp.dest('./'));
  gulp.src('./package.json').pipe(bump()).pipe(gulp.dest('./'));
  complete();
});

const folder = "./docroot/wp-content/themes/shadowlab/assets/";
const elixir = require("laravel-elixir");
require("laravel-elixir-webpack-official");
require("laravel-elixir-vue-2");

elixir((mix) => {
  mix.sass(folder + "styles/dashifen.scss", folder + "dashifen.css");
  mix.webpack(folder + "scripts/dashifen.js", folder + "dashifen.js");
});