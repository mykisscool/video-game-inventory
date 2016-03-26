module.exports = function (grunt) {

	var _ = require('underscore');

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		sass: {
			'datatables-responsive': {
				options: {
					style: 'compact'
				},			
				files: [{
					expand: true,
					cwd: 'bower_components/datatables-responsive/css',
					src: 'responsive.dataTables.scss',
					dest: 'src/css',
					ext: '.css'
				}]
			}
		},
		handlebars: {
			compile: {
				options: {
					namespace: 'Templates',
					processName: function (filepath) {
						var pieces = filepath.split('/');
						return pieces[pieces.length - 1].split('.')[0];
					}
				},
				files: {
					'src/js/templates.js': 'src/templates/*.hbs'
				}
			}
		},
		bower_concat: {
			bower: {
				dest: 'dist/<%= pkg.name %>-bower-components.min.js',
				cssDest: 'dist/<%= pkg.name %>-bower-components.min.css',
				mainFiles: {
					fontawesome: 'css/font-awesome.min.css',
					hover: 'css/hover-min.css',
					datatables: [
						'media/js/jquery.dataTables.min.js',
						'media/js/dataTables.bootstrap.min.js',
						'media/css/dataTables.bootstrap.min.css'
					],
					bootstrap: [
						'dist/js/bootstrap.min.js', 
						'dist/css/bootstrap.min.css'
					],
					underscore: 'underscore-min.js',
					backbone: 'backbone-min.js',
					'backbone.validation': 'dist/backbone-validation-min.js',
					moment: 'min/moment.min.js',
					'loaders.css': 'loaders.min.css',
					'magnific-popup': [
						'dist/jquery.magnific-popup.min.js',
						'dist/magnific-popup.min.css'
					]
				},
				exclude: 'datatables-responsive',
				dependencies: {
					'handlebars': ['jquery', 'bootstrap', 'underscore', 'moment'],
					'backbone': ['jquery', 'underscore']
				},
				callback: function (mainFiles, component) {
					return _.map(mainFiles, function (filepath) {
						var min = filepath.replace(/\.js$/, '.min.js');
						return grunt.file.exists(min) ? min : filepath;
					});
				}
			}
		},
		'local-googlefont': {
			lato: {
				options: {
					family: 'Lato',
					sizes: [400, 700],
					cssDestination: 'src/css',
					fontDestination: 'google-fonts'
				}
			},
			pressstart2p: {
			  options: {
				  family: 'Press Start 2P',
				  sizes: [400],
				  cssDestination: 'src/css',
				  fontDestination: 'google-fonts'
			  }
			}
		},
		uglify: {
			'datatables-responsive': {
				options: {
					mangle: true,
					compress: true
				},
				files: [{
					expand: true,
					cwd: 'bower_components/datatables-responsive/js',
					src: 'dataTables.responsive.js',
					dest: 'dist'
				}]
			},
			templates: {
				options: {
					mangle: true,
					compress: true,
					banner: '// templates\n'			
				},
				files: {
					'dist/templates.min.js': 'src/js/templates.js'
				}
			},
			mine: {
				options: {
					mangle: true,
					compress: true,
					banner: [
						'/*!',
						' * video-game-inventory v1.0.0',
						' * Copyright 2016 Mike Petruniak \<mike.petruniak@gmail.com\>',
						' * Licensed under the The MIT License (http://opensource.org/licenses/MIT)',
						' */'].join('\n') + '\n',
				},
				files: {
					'dist/<%= pkg.name %>.min.js': [
						'src/js/models/*.js', 
						'src/js/collections/*.js', 
						'src/js/views/*.js',
						'src/js/app.js'
					]
				},
			}
		},
		cssmin : {
			mine: {
				src: 'src/css/<%= pkg.name %>.css',
				dest: 'dist/<%= pkg.name %>.min.css'
			},
			fonts: {
				src: 'src/css/*.styl',
				dest: 'dist/fonts.min.styl'
			},
			'datatables-responsive': {
				src: 'src/css/responsive.css',
				dest: 'dist/dataTables.responsive.min.css'				
			},
			'magnific-popup': {
				src: 'bower_components/magnific-popup/dist/magnific-popup.css',
				dest: 'bower_components/magnific-popup/dist/magnific-popup.min.css'
			}
		},
		concat: {
			js: {
				src: [
					'dist/<%= pkg.name %>-bower-components.min.js',
					'dist/dataTables.responsive.js',
					'dist/templates.min.js',
					'dist/<%= pkg.name %>.min.js'
				],
				dest: 'dist/<%= pkg.name %>.concat.min.js'
			},
			css: {
				src: [
					'dist/<%= pkg.name %>-bower-components.min.css',
					'dist/fonts.min.styl',
					'dist/dataTables.responsive.min.css',
					'dist/<%= pkg.name %>.min.css'
				],
				dest: 'dist/<%= pkg.name %>.concat.min.css'
			}
		},
		copy: {
			googlefonts: {
				files: [
					{
						expand: true,
						cwd: '.',
						src: 'google-fonts/*',
						dest: 'dist/'
					}
				]
			},
			fontawesomefonts: {
				files: [
					{
						expand: true,
						flatten: true,
						cwd: '.',
						src: 'bower_components/fontawesome/fonts/*',
						dest: 'fonts/',
						isFile: true
					}
				]
			},
			bootstrapfonts: {
				files: [
					{
						expand: true,
						flatten: true,
						cwd: '.',
						src: 'bower_components/bootstrap/fonts/*',
						dest: 'fonts/',
						isFile: true
					}
				]
			}
		},
		clean: {
			js: '<%= concat.js.src %>',
			css: '<%= concat.css.src %>',
			googlefonts: 'google-fonts/'
		},
		watch: {
			mine: {
				files: [
					'src/js/models/*.js', 
					'src/js/collections/*.js',
					'src/js/views/*.js',
					'src/js/app.js',
					'src/css/<%= pkg.name %>.css',
					'src/templates/*.hbs'
				],
				tasks: [
					'handlebars',
					'bower_concat:bower',
					'uglify:datatables-responsive',
					'uglify:templates',
					'uglify:mine',
					'cssmin:mine',
					'cssmin:fonts',
					'cssmin:datatables-responsive',
					'concat:js',
					'concat:css',
					'clean:js',
					'clean:css'
				],
				options: {
					spawn: false
				}
			}
		}
	});

	grunt.loadNpmTasks('grunt-bower-concat');
	grunt.loadNpmTasks('grunt-local-googlefont');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-handlebars');

	grunt.registerTask('default', [
		'sass:datatables-responsive',
		'handlebars',
		'cssmin:magnific-popup',
		'bower_concat:bower',
		'local-googlefont:lato',
		'local-googlefont:pressstart2p',
		'uglify:datatables-responsive',
		'uglify:templates',
		'uglify:mine',
		'cssmin:mine',
		'cssmin:fonts',
		'cssmin:datatables-responsive',
		'concat:js',
		'concat:css',
		'copy:googlefonts',
		'copy:fontawesomefonts',
		'copy:bootstrapfonts',
		'clean:js',
		'clean:css',
		'clean:googlefonts'
	]);

	grunt.registerTask('changes', 'watch:mine');
};
