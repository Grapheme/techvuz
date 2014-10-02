module.exports = function(grunt) {
  grunt.initConfig({
    concat: {
      dist: {
        // the files to concatenate
        src: [
          'js/vendor/jquery-ui.min.js',
          'js/vendor/jquery.cookie.js',
          'js/main.js',
          'js/plugins.js'
        ],
        // the location of the resulting JS file
        dest: 'js/index.js'
      }
    },
    autoprefixer: {
        options: {
          browsers: ['last 7 versions', 'ie 8', 'ie 9']
        },
        dist: {
          files: {
            'css/main.css' : 'sass/main.css'
          }
        }
    },
    sass: {
      development: {
        options: { // Target options
          style: 'expanded'
        },
        files: {
          // target.css file: source.scss file
          "sass/main.css": "sass/main.scss"
        }
      }
    },
    watch: {
      styles: {
        files: ['sass/**/**/*.scss', 'js/**/*.js'], // which files to watch
        tasks: ['sass', 'autoprefixer'],
        options: {
          nospawn: true
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-autoprefixer');

  grunt.registerTask('default', ['watch']);
};