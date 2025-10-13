const { src, watch, dest, parallel } = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const plumber = require("gulp-plumber");
const cssnano = require("cssnano");
const sourcemaps = require("gulp-sourcemaps");
const autoprefixer = require("autoprefixer");
const postcss = require("gulp-postcss");

function css(done) {
  src("src/scss/**/*.scss") /* Identifar SASS archivo */
    .pipe(sourcemaps.init())
    .pipe(plumber())
    .pipe(sass()) /* compilar */
    .pipe(postcss([autoprefixer(), cssnano()]))
    .pipe(sourcemaps.write("."))
    .pipe(dest("build/css/")); /* Almacenar  */

  done(); /* Delimita el fin  */
}

function js(done) {
  src("src/js/**/*.js").pipe(plumber()).pipe(dest("build/scripts/"));

  done();
}

function dev(done) {
  watch("src/scss/**/*.scss", css);
  watch("src/js/**/*.js", js);

  done();
}

exports.css = css;
exports.js = js;
exports.dev = parallel(dev);
exports.default = parallel(dev);
