module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        sass: {
            options: {
                includePaths: ['bower_components/foundation/scss']
            },
            dist: {
                options: {
                    outputStyle: 'compressed'
                },
                files: {
                    'stylesheets/app.css': 'scss/app.scss'
                }
            }
        },

        uglify: {
            options: {
                // the banner is inserted at the top of the output
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
            },
            static_mappings: {
                // Because these src-dest file mappings are manually specified, every
                // time a new file is added or removed, the Gruntfile has to be updated.
                files: [
                    {src: 'js/app.js', dest: 'js/dist/app.min.js'},
                    {src: 'js/app-admin.js', dest: 'js/dist/app-admin.min.js'},
                ]
            }
        },

        watch: {
            options: {
                livereload: true
            },
            grunt: {
                files: ['Gruntfile.js']
            },
            sass: {
                files: 'scss/**/*.scss',
                tasks: ['sass']
            },
            src: {
                files: 'js/*.js',
                tasks: ['uglify']
            }
        }
    });

    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('build', ['sass']);
    grunt.registerTask('default', ['uglify', 'watch']);

};