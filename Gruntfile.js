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
                    'css/app.css': 'scss/app.scss'
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
                ],
            },
            //dynamic_mappings: {
            //    // Grunt will search for "***/*//*.js" under "lib/" when the "uglify" task
            //    // runs and build the appropriate src-dest file mappings then, so you
            //    // don't need to update the Gruntfile when files are added or removed.
            //    files: [
            //        {
            //            expand: true,     // Enable dynamic expansion.
            //            cwd: 'js/',      // Src matches are relative to this path.
            //            src: ['***/*//*.js'], // Actual pattern(s) to match.
            //            dest: 'js/dist/',   // Destination path prefix.
            //            ext: '.min.js',   // Dest filepaths will have this extension.
            //            extDot: 'first'   // Extensions in filenames begin after the first dot
            //        },
            //    ],
            //},
        },

        watch: {
            options: {
                livereload: true
            },
            grunt: {files: ['Gruntfile.js']},

            sass: {
                files: 'scss/**/*.scss',
                tasks: ['sass']
            }
        }
    });

    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('build', ['sass']);
    grunt.registerTask('default', ['uglify', 'watch']);

};