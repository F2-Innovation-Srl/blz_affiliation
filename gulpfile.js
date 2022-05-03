var gulp = require('gulp'),                     // base
    
    //fs       = require('fs'),                   
    //file     = require('gulp-file'),            // creates a new file

    del = require('del');                  // deletes old dist files
    rename = require('gulp-rename');
    

var buildFolder = 'src/assets/',    
    scriptsBuildFolder, jsLibBuildFolder;

const setFolders = (folder = 'static/') => {
    buildFolder = folder;    
    scriptsBuildFolder = buildFolder + 'js/';
    jsLibBuildFolder  = scriptsBuildFolder + '/libs';
}

setFolders(buildFolder);

// task to delete each file in the dist directory
gulp.task('clean', () => del([ jsLibBuildFolder ]));


/**
 * Aggiorna la cartella delle librerie js
 */
 gulp.task('scripts:lib', function() {

    const libs_src = [
        'node_modules/tracker/dist/tracker.min.js'
    ];

    return gulp.src( libs_src )
        .pipe(rename('blz_tr.js'))
        .pipe( gulp.dest( jsLibBuildFolder ) );
});



gulp.task( 'common-chain', gulp.series('clean', 'scripts:lib') );

gulp.task( 'default',
    gulp.series( 'common-chain' )
);

