let gulp       = require('gulp');
var rename     = require('gulp-rename'); 
var notify     = require("gulp-notify");
const minify   = require("gulp-babel-minify");
let sourcemaps = require('gulp-sourcemaps');
const babel    = require('gulp-babel');

gulp.task('compressscripts', function () {
    return gulp.src(['./assets/js/*.js', './assets/js/page/*.js'])
    .pipe(sourcemaps.init())
    .pipe(babel())
    .pipe(minify({
        mangle: {
            toplevel: true ,
            keepClassName: true
        },
    }))
    .pipe(rename({suffix: '.min'}))
    .pipe(sourcemaps.write("./maps"))
    .pipe(gulp.dest('./assets/js/dist'))
    .pipe(notify({
        message: 'Scripts task complete!',
        onLast : true
    }));
});

gulp.task('solstar', gulp.series('compressscripts'));