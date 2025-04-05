module.exports = function( grunt ) {

	'use strict';

	/*
     * Grunt Tasks
     * load all grunt tasks matching the `grunt-*` pattern
     * Ref. https://npmjs.org/package/load-grunt-tasks
     */
    require( 'load-grunt-tasks' )( grunt );

	// Project configuration
	grunt.initConfig( {

		pkg: grunt.file.readJSON( 'package.json' ),

		// concat JS files
		concat: {
			options: {
				separator: ';',
			},
			dist: {
				src: ['src/scripts/*.js'],
				dest: 'assets/js/script.js',
			},
		},

		concat_css: {
			options: {
				// Task-specific options go here.
			},
			all: {
				src: ["src/styles/*.css"],
				dest: "assets/css/styles.css"
			},
		},

		// Uglify js
		uglify: {
			core: {
				files: {
					'assets/js/script.min.js': ['assets/js/script.js']
				}
			}
		},

		cssmin: {
			target: {
				files: [
					{
						expand: true,
						cwd: 'assets/css',
						src: ['*.css', '!*.min.css'],
						dest: 'assets/css',
						ext: '.min.css'
					}
				]
			}
		},

		// autoprefixer
        autoprefixer: {
            options: {
                browsers: [ 'last 2 versions', 'ie 9', 'ios 6', 'android 4' ],
                map: false,
            },
            files: {
                expand: true,
                flatten: true,
                src: 'assets/css/*.css',
                dest: 'assets/css/'
            }
        },

		addtextdomain: {
			options: {
				textdomain: 'ufaqsw',
			},
			update_all_domains: {
				options: {
					updateDomains: true
				},
				src: [ '*.php', '**/*.php', '!\.git/**/*', '!bin/**/*', '!node_modules/**/*', '!tests/**/*', '!vendor/**/*' ]
			}
		},

		makepot: {
			target: {
				options: {
					domainPath: '/languages',
					exclude: [ '\.git/*', 'bin/*', 'node_modules/*', 'tests/*' ],
					mainFile: 'init.php',
					potFilename: 'ultimate-faq-solution.pot',
					potHeaders: {
						poedit: true,
						'x-poedit-keywordslist': true
					},
					type: 'wp-plugin',
					updateTimestamp: true
				}
			}
		},
	} );


	grunt.registerTask( 'cssjs', ['concat', 'concat_css', 'autoprefixer', 'uglify', 'cssmin'] );
	grunt.registerTask( 'default', [ 'i18n', 'cssjs' ] );
	grunt.registerTask( 'i18n', ['addtextdomain', 'makepot'] );

	grunt.util.linefeed = '\n';

};
