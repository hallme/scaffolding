// Require Libraries.
var gulp = require( 'gulp' ),
	path = require( 'path' ),
	compass = require( 'gulp-compass' ),
	browserSync = require('browser-sync');
	WP = require( 'wp-cli' );

// Current Working Directory.
var cwd = process.env.INIT_CWD;

// Project Working Directory.
var pwd = __dirname;

// System Variables.
var config;
var response;

// Paths.
var paths = {
	sass: cwd + '/scss/',
	css: cwd + '/css/',
	js: cwd + '/js/'
};

// Asset Locations.
var assets = {
	sass: [paths.sass + '**/*'],
	css: [paths.css + '**/*'],
	js: [paths.js + '**/*'],
};

gulp.task('serve', function() {

	WP.discover( {path:'../../../'}, function( WP ) {
		WP.option.get( ['siteurl'], function( err,result ){

			siteurl = result;

			browserSync.init({
				proxy: siteurl,
				ghostMode: {
					clicks: true,
					forms: true,
					scroll: true
				}
			});

			gulp.watch( cwd + '/**/*.php', ['browsersync']);
		});
	});
});

// Compass Task.
gulp.task( 'compass', function () {
	gulp.src( paths.sass )
		.pipe( compass( {
			config_file: cwd + '/scss/config.rb',
			css: paths.css,
			sass: paths.sass
		}));
});

// Watch Task.
gulp.task( 'watch', function () {
	gulp.watch( assets.sass, ['compass'] );
	gulp.watch( assets.css, ['browsersync'] );
	gulp.watch( assets.js, ['browsersync'] );
	gulp.watch( cwd + '**/*.php', ['browsersync'] );
});

// CSS Reload.
gulp.task( 'browsersync', function () {
	browserSync.reload();
});

// Default.
gulp.task( 'default', ['serve', 'compass', 'watch'] );