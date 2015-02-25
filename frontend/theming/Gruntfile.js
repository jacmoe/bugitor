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
          '../web/css/bugitor.min.css': ['../web/css/bugitor.css']
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
          'js/yii.js',
          'js/yii.validation.js',
          'js/yii.activeForm.js'
        ],
        dest: '../web/js/script.js'
      }
    },


    // --------------------------------------
    // Uglify Configuration
    // --------------------------------------

    uglify: {
      dist: {
        files: {
          '../web/js/script.min.js': ['../web/js/script.js']
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
