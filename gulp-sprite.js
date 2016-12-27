// ------------------------
// Build SVG symbol sprites
// ------------------------
// Each directory matching assets/icons/sprite-* may contain individual SVG files,
// and will be turned into a SVG symbol spritesheet.

let gulp = require('gulp');
let path = require('path');
let glob = require('glob');
let svgSprite = require('gulp-svg-sprite');

gulp.task('build-svg', function() {
    let svgDest = './public/images/sprites';

    return glob('./resources/assets/icons/sprite-*', function(err, dirs) {
        dirs.forEach(function(dir) {
            console.log(dir);
            gulp.src( path.join(dir, '*.svg') )
                .pipe( svgSprite(makeSvgSpriteOptions(dir)) )
                //.pipe( size({ showFiles: true, title: svgDest }) )
                .pipe( gulp.dest(svgDest) )
        })
    });

    /**
     * Make a svg-sprite configuration object for a symbol sprite with a custom file name
     */
    function makeSvgSpriteOptions(dirPath) {
        return {
            mode: {
                symbol: {
                    dest: '.',
                    sprite: path.basename(dirPath).replace('sprite-', '') + '.svg'
    }
    },
        shape: {
            id: {separator: '-'},
            transform: ['svgo']
        }
    };
    }
});