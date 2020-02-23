module.exports = function (gulp, plugins) {
  return function () {
    return gulp.src([
      // Bootstrap
      // './node_modules/bootstrap/dist/js/bootstrap.min.js',

      // Bootstrap Select
      './node_modules/bootstrap-select/dist/js/bootstrap-select.min.js',

      // AOS
      './node_modules/aos/dist/aos.css',
      './node_modules/aos/dist/aos.js',

      // Material design icon
      './node_modules/material-design-iconic-font/dist/css/material-design-iconic-font.min.css',
      // './node_modules/material-design-iconic-font/dist/fonts/*',

    ], {
        base: 'node_modules'
    }).pipe(gulp.dest('../symfony/public/build/vendors/'));
  };
};
