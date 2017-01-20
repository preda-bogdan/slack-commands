/*global module:false*/
module.exports = function (grunt) {

    // Project configuration.
    grunt.initConfig({

    });

    // Default task.
    grunt.registerTask('gen-conf', function() {
        YAML = require('yamljs');
        var type = grunt.option( 'type' ) || 'history';
        if(type == 'history') {
            var theme_slug = grunt.option('theme') || 'theme_slug';
            conf_file = grunt.file.readYAML('base_config.yaml');
            conf_file.domains = { theme : 'https://demo.themeisle.com/' + theme_slug};
            conf_file.directory = "compare/" + theme_slug + "_shots";
            conf_file.history_dir = "compare/" + theme_slug + "_history";
            grunt.file.write('configs/' + theme_slug + '_config.yaml', YAML.stringify(conf_file));
        } else if( type == 'history_spyder' ) {
            var theme_slug = grunt.option('theme') || 'theme_slug';
            conf_file = grunt.file.readYAML('base_config.yaml');
            conf_file.domains = { theme : 'https://demo.themeisle.com/' + theme_slug};
            conf_file.directory = "compare_spyder/" + theme_slug + "_shots";
            conf_file.history_dir = "compare_spyder/" + theme_slug + "_history";
            conf_file.imports = 'spyders/' + theme_slug + '_paths.yml';
            grunt.file.write('configs/' + theme_slug + '_config.yaml', YAML.stringify(conf_file));
        } else if( type == 'compare' ) {
            var domain1 = grunt.option('domain1') || 'domain1';
            var domain2 = grunt.option('domain2') || 'domain2';
            var name = grunt.option('name') || 'tmp';
            conf_file = grunt.file.readYAML('base_config_compare.yaml');
            conf_file.domains = {
                domain_one : domain1,
                domain_two : domain2,
            };
            var arr_one = domain1.split("/");
            var arr_two = domain2.split("/");

            arr_one[2] = arr_one[2].replace(/\./g,'_');
            arr_two[2] = arr_two[2].replace(/\./g,'_');

            conf_file.directory = "compare_" + name + "_shots";
            grunt.file.write('configs/compare_' + name + '_config.yaml', YAML.stringify(conf_file));
        } else {
            console.log('Spyder Case');
            var theme_slug = grunt.option('theme') || 'theme_slug';
            conf_file = grunt.file.readYAML('base_config_spyder.yaml');
            conf_file.domains.demo = conf_file.domains.demo + theme_slug;
            conf_file.domains.dev = conf_file.domains.dev + theme_slug;
            conf_file.imports = 'spyders/' + theme_slug + '_paths.yml';
            conf_file.directory = "spyder_" + theme_slug + "_shots";
            grunt.file.write('configs/spyder_' + theme_slug + '_config.yaml', YAML.stringify(conf_file));
        }
    });

};
