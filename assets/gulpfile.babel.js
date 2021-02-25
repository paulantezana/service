import {src, dest, task, watch, series, parallel} from 'gulp';
import sass from 'gulp-sass';
// import minify from 'gulp-minify';

function scss(done) {
    src(['./scss/*.scss'])
        .pipe(sass({
            errLogToConsole: true,
            outputStyle: 'compressed'
        }))
        .on('error', console.error.bind(console))
        .pipe(dest('./css'));
    done();
}

// function script(done) {
//     src(['./src/script/*.js'])
//         .pipe(minify())
//         .pipe(dest('./build/script'));
//     done();
// }

function watch_files() {
    watch('./scss/**/*.scss', series(scss));
    // watch('./src/script/**/*.js', series(script));
}

task("default", parallel(watch_files));