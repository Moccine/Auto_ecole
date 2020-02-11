var gulp = require('gulp');
var mjml = require('gulp-mjml');

var plugins = require('gulp-load-plugins')({
  'config': require('./package.json'),
  'pattern': ['*'],
  'scope': ['dependencies','devDependencies']
});

gulp.task('copy-vendor', require('./tasks/copy-vendor')(gulp, plugins));

// Optional
gulp.task('iconfont', require('./tasks/iconfont')(gulp, plugins));

gulp.task('watch:iconfont', function () {
  gulp.watch('./fonts/iconfont/*.svg', gulp.series('iconfont'));
});

gulp.task('mjml', function () {
  return gulp.src('./mail/mail.mjml')
    .pipe(mjml())
    .pipe(gulp.dest('./mail/html'))
});

gulp.task('mjml:newsletter', function () {
  return gulp.src('./mail/newsletter.mjml')
    .pipe(mjml())
    .pipe(gulp.dest('./mail/html'))
});

gulp.task('watch:mjml', function() {
    gulp.watch(['./mail/mail.mjml', './mail/newsletter.mjml'], gulp.series(['mjml','mjml:newsletter']));
});

gulp.task('watch', gulp.parallel( 'watch:iconfont'));

gulp.task('default', gulp.parallel('copy-vendor', 'iconfont'));
