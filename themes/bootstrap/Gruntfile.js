module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    concat: {
      js: {
        src: ['js/source/bootstrap-affix.js',
                'js/source/bootstrap-alert.js',
                'js/source/bootstrap-button.js',
                'js/source/bootstrap-carousel.js',
                'js/source/bootstrap-collapse.js',
                'js/source/bootstrap-dropdown.js',
                'js/source/bootstrap-modal.js',
                'js/source/bootstrap-scrollspy.js',
                'js/source/bootstrap-tab.js',
                'js/source/bootstrap-transition.js',
                'js/source/bootstrap-tooltip.js',
                'js/source/bootstrap-popover.js',
                'js/source/bootstrap-typeahead.js'],
        dest: 'js/bootstrap.js'
      }
    },
    uglify: {
            options: {
              banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - ' +
                '<%= grunt.template.today("yyyy-mm-dd") %> */'
            },
            js: {
            files: {
                'js/bootstrap.min.js': ['js/bootstrap.js']
            }
        }
    },
    compass: {
    dist: {
      options: {
        config: 'config.rb'
      }
    }
  },
    cssmin: {
        options: {
          banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - ' +
                '<%= grunt.template.today("yyyy-mm-dd") %> */'
        },
        compress: {
            files: {
              'css/bugitor.min.css': ['css/bugitor.css']
            }
          }
    }
});

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-cssmin');

  // Default task.
  grunt.registerTask('default', ['concat', 'uglify', 'compass', 'cssmin']);

};