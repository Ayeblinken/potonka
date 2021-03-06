module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      build: {
        src: 'src/<%= pkg.name %>.js',
        dest: 'build/<%= pkg.name %>.min.js'
      }
    }
  });

	bowerInstall: {

  target: {

    // Point to the files that should be updated when
    // you run `grunt bower-install`
    src: [
      '*.html'   // .html support... 
    ],

    // Optional:
    // ---------
    cwd: '',
    dependencies: true,
    devDependencies: false,
    exclude: [],
    fileTypes: {},
    ignorePath: '',
    overrides: {}
  }
}

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-bower-install');

  // Default task(s).
  grunt.registerTask('default', ['uglify']);

};
