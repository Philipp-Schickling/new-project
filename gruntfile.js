module.exports = function(grunt) {

  grunt.initConfig({


  // minify css
  cssmin: {
    my_target: {
      files: [{
          expand: true,
          cwd: 'web/assets/css',
          src: ['*.css'],
          dest: 'web/assets/css',
          ext: '.min.css'
      }]
    }
  },


  // convert less files in controller/assets/less/*.less to controller/assets/css/*.css
  less: {
    my_target: {
      files: [{
          expand: true,
          cwd: 'web/assets/css/src',
          src: ['master.less'],
          dest: 'web/assets/css',
          ext: '.css'
      }]
    }
  },

  // convert js files in controller/assets/js/*.js to controller/assets/min.js/*.min.js
  uglify: {
    my_target: {
      files: [{
          expand: true,
          cwd: 'web/assets/js/src',
          src: 'master.js',
          dest: 'web/assets/js',
          ext: '.min.js'
      }]
    }
  },

  // convert js files in controller/assets/js/*.js to controller/assets/min.js/*.min.js
  concat: {
    dist: {
      src: ['controller/assets/js/*.js'],
      dest: 'controller/assets/output.js'
    }
  },

  jshint: {
    beforeconcat: ['controller/assets/**/*.js'],
    afterconcat: ['controller/assets/*-error.js']
  },

//


/*

                  ###########
                  WATCH FILES
                  ###########

*/


  // make 'grunt watch' task in bash available
  // run less to css to css.min when less files are altered
  // run js to js.min when js files are altered
  watch: {

    less: {
      files: ['web/assets/css/src/*.less'],
      tasks: ['less', 'cssmin'],
      options: {
        spawn: false,
      },
    },

    scripts: {
      files: ['web/assets/js/src/*.js'],
      tasks: ['uglify'],
      options: {
        spawn: false,
      },
    },
  },



  });

  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-concat');
  
  grunt.registerTask('default', [
    'less',
    'uglify',
    'cssmin'
    ]);


};
