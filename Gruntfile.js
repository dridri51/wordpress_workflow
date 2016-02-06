/**
 * Created by Adrien on 04/02/2016.
 */
module.exports = function(grunt){
    grunt.initConfig({
        sass: {
            dist: {
                options: {
                    style: 'expanded'
                },
                files: {
                    'wp-content/themes/twentyfifteen-child/app.css' :'wp-content/themes/twentyfifteen-child/sass/app.sass'
                }
            }
        },
        watch: {
            css: {
                files: '**/*.sass',
                tasks: ['sass','cssmin']
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            target: {
                files: {
                    'wp-content/themes/twentyfifteen-child/app.min.css': ['wp-content/themes/twentyfifteen-child/app.css']
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');


    grunt.registerTask('css', ['sass','cssmin']);
    grunt.registerTask('default', ['watch']);
};