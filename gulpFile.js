var gulp = require('gulp'),
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    concat = require('gulp-concat');

var jsFileList = [
    'node_modules/angular/angular.min.js',
    'node_modules/angular-ui-router/release/angular-ui-router.min.js',
    'node_modules/angular-resource/angular-resource.min.js',
    'assets/js/admin-app.js'
];

gulp.task( 'sass', function() {
    gulp.src('./assets/scss/styles.scss')
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('./build/css'));
});

gulp.task( 'js', function(){
    gulp.src(jsFileList)
        .pipe(concat('admin-app-scripts.js'))
        .pipe(gulp.dest('build/js/'))
});

gulp.task( 'watch', function(){
    gulp.watch('./assets/scss/styles.scss', ['sass'] );
    gulp.watch(jsFileList, ['js'] );
})

gulp.task( 'default', ['sass', 'js', 'watch'] );