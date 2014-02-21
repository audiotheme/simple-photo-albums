module.exports = function(grunt) {
	'use strict';

	require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

	grunt.initConfig({

		makepot: {
			plugin: {
				options: {
					mainFile: 'simple-photo-albums.php',
					type: 'wp-plugin'
				}
			}
		}

	});

	/**
	 * Generate documentation using ApiGen.
	 *
	 * @link http://apigen.org/
	 */
	grunt.registerTask('apigen', function() {
		var done = this.async();

		grunt.util.spawn({
			cmd: 'apigen',
			args: [
				'--source=.',
				'--destination=docs',
				'--exclude=*/.git*,*/docs/*,*/node_modules/*',
				'--title=Simple Photo Albums',
				'--main=SimplePhotoAlbums',
				'--report=docs/_report.xml'
			],
			opts: { stdio: 'inherit' }
		}, done);
	});

	/**
	 * PHP Code Sniffer using WordPress Coding Standards.
	 *
	 * @link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards
	 */
	grunt.registerTask('phpcs', function() {
		var done = this.async();

		grunt.util.spawn({
			cmd: 'phpcs',
			args: [
				'-p',
				'-s',
				'--standard=WordPress',
				'--extensions=php',
				'--ignore=*codesniffer-report.txt,*/docs/*,*/node_modules/*',
				'--report-file=codesniffer-report.txt',
				'.'
			],
			opts: { stdio: 'inherit' }
		}, done);
	});

};
