var gulp = require('gulp');
var sass  = require('gulp-sass');
var concat = require('gulp-concat');

gulp.task('serve', ['sass'], function() {
    gulp.watch('./assets/sass/**/*.scss', ['sass']);
});

gulp.task('sass', function() {
  return gulp.src('./assets/sass/solograph.scss')
    .pipe(sass())
    .pipe(concat('style.min.css'))
    .pipe(gulp.dest('./'))
});

gulp.task('default', ['serve']);