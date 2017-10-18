/**
 * Folders' definitions
 **/ 
const sourceRoot = './src/';
const publicRoot = './public/dist/'
const bootstrapRoot = './node_modules/bootstrap/';

module.exports = {
    source: {
        sass: sourceRoot + 'styles/**/*.scss',
        elm: sourceRoot + 'elm/**/*.elm',
        js: sourceRoot + '**/*.js'
    },
    public: {
        root: publicRoot,
        css: publicRoot + 'css/',
        js: publicRoot + 'js',
        fonts: publicRoot + 'fonts'
    },
    bootstrap: {
        fonts: bootstrapRoot + 'dist/fonts/*',
        css: bootstrapRoot + 'dist/css/*'
    },
    output: {
        elmApplicationName: 'app'
    }
};
