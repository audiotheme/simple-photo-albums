module.exports = function(grunt) {
	'use strict';

	require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

	grunt.initConfig({

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
	 * Generate a POT file for translating plugin strings.
	 *
	 * The WordPress i18n tools needs to exist at '/wp-content/i18n-tools/',
	 * with php-cli and gettext in the system path to run this task.
	 *
	 * @link http://i18n.svn.wordpress.org/tools/trunk/
	 */
	grunt.registerTask('makepot', function() {
		var done = this.async();

		grunt.util.spawn({
			cmd: 'php',
			args: [
				'../../i18n-tools/makepot.php',
				'wp-plugin',
				'.',
				'languages/simple-photo-albums.pot'
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