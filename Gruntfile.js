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

		shell: {
			makepot: {
				command: [
					'php ../../../wp-cli.phar i18n make-pot .',
					'inc/languages/ultimate-faq-solution.pot',
					'--exclude=node_modules,vendor,tests,assets,block/node_modules',
				].join(' ')
			}
		},

		// Clean up build directory
        clean: {
            main: ['build/']
        },

        // Copy the plugin into the build directory
        copy: {
            main: {
                src: [
                    '**',
                    '!node_modules/**',
                    '!build/**',
                    '!bin/**',
                    '!.git/**',
					'!.wordpress-org/**',
                    '!Gruntfile.js',
                    '!package.json',
                    '!package-lock.json',
                    '!phpcs.ruleset.xml',
					'!readme.txt',
					'!README.md',
                    '!phpunit.xml.dist',
                    '!webpack.config.js',
                    '!tmp/**',
                    '!views/assets/src/**',
                    '!src/**',
                    '!debug.log',
                    '!phpunit.xml',
                    '!export.sh',
                    '!.gitignore',
                    '!.env',
                    '!.gitmodules',
                    '!codeception.yml',
                    '!npm-debug.log',
                    '!plugin-deploy.sh',
                    '!readme.md',
                    '!composer.json',
                    '!composer.lock',
                    '!prev.json',
                    '!secret.json',
                    '!assets/src/**',
                    '!assets/less/**',
                    '!tests/**',
                    '!**/Gruntfile.js',
                    '!**/package.json',
					'!**/package-lock.json',
					'!**/src/**',
					'!**/node_modules/**',
                    '!**/customs.json',
                    '!nbproject',
                    '!phpcs.xml',
					'!deploy.sh',
                    '!phpcs-report.txt',
                    '!**/*~',
                    '!.eslintrc.js',
                    '!.editorconfig',
                    '!babel.config.js',
                    '!composer.phar',
					'!postcss.config.js',
					'!tailwind.config.js',
                ],
                dest: 'build/'
            }
        },

		compress: {
			main: {
			  options: {
				archive: 'ultimate-faq-solution.zip'
			  },
			  files: [{
				expand: true,
				cwd: 'build/',
				src: ['**/*'],
				dest: '/'
			  }]
			}
		  },

		run: {
            options: {},

            removeDev:{
                cmd: 'composer',
                args: ['install', '--no-dev']
            },

            dumpautoload:{
                cmd: 'composer',
                args: ['dumpautoload', '-o']
            },
        }
	} );

	grunt.loadNpmTasks( 'grunt-contrib-compress' );
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
    grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-run' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-shell' );


	grunt.registerTask( 'cssjs', ['concat', 'concat_css', 'autoprefixer', 'uglify', 'cssmin'] );
	grunt.registerTask( 'default', [ 'cssjs' ] );
	grunt.registerTask( 'i18n', [ 'shell:makepot' ] );

	grunt.registerTask( 'release', [
		'default',
		'i18n',
        'clean',
        'run:removeDev',
        'run:dumpautoload',
        'copy',
        'compress',
        'run:removeDev',
        'run:dumpautoload',
		'clean',
    ]);

	grunt.util.linefeed = '\n';

};
