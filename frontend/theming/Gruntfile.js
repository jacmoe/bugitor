module.exports = function(grunt)
{
  // -----------------------------------------
  // Start Grunt configuration
  // -----------------------------------------

  grunt.initConfig({

    // Load package.json file
    pkg: grunt.file.readJSON('package.json'),


    // --------------------------------------
    // Clean Configuration
    // --------------------------------------

    clean: {
      options: {
        force: true
      },
      js:    ["../web/js/"],
      css:   ["../web/css/"]
    },


    // --------------------------------------
    // Sass Configuration
    // --------------------------------------

    sass: {
      options: {
        loadPath: ['vendor/bower/bootstrap-sass/assets/stylesheets']
      },
      dist: {
        options: {
          sourcemap: 'none',
          style: 'nested'
        },
        files: [{
          expand: true,
          cwd: 'scss',
          src: ['*.scss'],
          dest: '../web/css',
          ext: '.css'
        }]
      }
    },


    // --------------------------------------
    // CSS Minify Configuration
    // --------------------------------------

    cssmin: {
      target: {
        files: {
          'dist/css/app.min.css': ['srccss/fluidbox.css',
          'srccss/solarized_dark.css',
          'srccss/scrollup.css',
          'foundation-icons/foundation-icons.css',
          'dist/css/app.css']
        }
      }
    },


    // --------------------------------------
    // Concatenate Configuration
    // --------------------------------------

    concat: {
      options: {
        separator: ';'
      },
      script: {
        src: [
          'bower_components/foundation/js/foundation/foundation.js',
          'bower_components/foundation/js/foundation/foundation.topbar.js',
          'bower_components/foundation/js/foundation/foundation.dropdown.js',
          // ...more foundation JS you might want to add
          'js/toc.js',
          'js/highlight.pack.js',
          'js/jquery.fluidbox.js',
          'js/jquery.scrollUp.js',
          'js/script.js'
        ],
        dest: 'dist/js/script.js'
      },
      modernizr: {
        src: [
          'bower_components/modernizr/modernizr.js',
          'js/custom.modernizr.js'
        ],
        dest: 'dist/js/modernizr.js'
      }
    },


    // --------------------------------------
    // Uglify Configuration
    // --------------------------------------

    uglify: {
      dist: {
        files: {
          'dist/js/jquery.min.js': ['bower_components/jquery/dist/jquery.js'],
          'dist/js/modernizr.min.js': ['dist/js/modernizr.js'],
          'dist/js/script.min.js': ['dist/js/script.js']
        }
      }
    },


    // --------------------------------------
    // Image minify Configuration
    // --------------------------------------

    //imagemin: {
    //  dynamic: {
    //    files: [{
    //      expand: true,
    //      cwd:  'img/',
    //      src:  ['**/*.{png,jpg,gif}'],
    //      dest: 'dist/img/'
    //    }]
    //  }
    //},


    // --------------------------------------
    // Watch Configuration
    // --------------------------------------

    watch: {
      grunt: { files: ['Gruntfile.js'], tasks: ['default'] },

      sass: {
        files: 'scss/**/*.scss',
        tasks: ['buildCss']
      },

      script: {
        files: 'js/**/*.js',
        tasks: ['buildJs']
      }

      //images: {
      //  files: 'img/**/*.{png,jpg,gif}',
      //  tasks: ['buildImg']
      //}
    }


  });


  // -----------------------------------------
  // Load Grunt tasks
  // -----------------------------------------

  grunt.loadNpmTasks('grunt-newer');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  //grunt.loadNpmTasks('grunt-contrib-imagemin');


  // -----------------------------------------
  // Register Grunt tasks
  // -----------------------------------------

  //grunt.registerTask('buildImg', ['newer:imagemin']);
  grunt.registerTask('buildCss', ['clean:css', 'sass', 'cssmin']);
  grunt.registerTask('buildJs',  ['clean:js', 'concat', 'uglify']);
  //grunt.registerTask('default',  ['clean', 'buildCss', 'buildJs', 'buildImg', 'watch']);
  grunt.registerTask('default',  ['clean', 'buildCss', 'buildJs', 'watch']);
}
