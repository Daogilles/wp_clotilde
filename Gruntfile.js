module.exports = function(grunt) {
    
    require('time-grunt')(grunt);

    /**
     * Project configuration.
     */
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json')
    });

    grunt.config.set('http', {
        gatherAssets: {
            url: grunt.config.get('pkg').url+grunt.config.get('pkg').root+'/grunt.php',
            method: 'POST',
            dest: 'assets.json'
        }
    });
    
    grunt.loadNpmTasks('grunt-http');

    /**
     * compress-css : concat & minify styles
     * example : grunt compress-css --force
     */
    grunt.registerTask('compress-css', '', function() {
        grunt.loadNpmTasks('grunt-contrib-cssmin');
        //config
        var root = grunt.config.get('pkg').root;
        var pathGenerated = root+grunt.config.get('pkg').pathGenerated;

        // delete generated css
        if(pathGenerated != null && pathGenerated != '' && pathGenerated != undefined && grunt.file.exists(pathGenerated)) {
            var styles = grunt.file.expand({}, pathGenerated+'*.css');
            if(styles.length) {
                var i = styles.length;
                while(i--) {
                    grunt.log.writeln('delete '+styles[i]);
                    grunt.file.delete(styles[i]);
                }
            }
            if(grunt.file.exists(pathGenerated+'version_css.php')) {
                grunt.log.writeln('delete '+pathGenerated+'version_css.php');
                grunt.file.delete(pathGenerated+'version_css.php');
            }
        } else {
            grunt.file.mkdir(pathGenerated);
            grunt.log.writeln('mkdir '+pathGenerated);
        }

        // get current timestamp for versionning
        var date = new Date(),
            versionNum = date.getTime();

        // recupere le fichier assets.json avec tous les assets requis
        var assets = grunt.file.readJSON('assets.json');

        var files = {};
        for(var groupName in assets.styles) {
            files[pathGenerated+groupName+'_'+versionNum+'.css'] = assets.styles[groupName].map(function(src) {
                return root+src;
            });
        }

        grunt.config.set('cssmin', {
            combine: {
                files: files
            }
        });
        grunt.task.run('cssmin');

        grunt.file.write(pathGenerated+'version_css.php', '<?php $CURRENT_VERSION = "'+versionNum+'"; ?>');
    });

    /**
     * compress-js : concat & uglify javascript
     * example : grunt compress-js --force
     */
    grunt.registerTask('compress-js', '', function() {
        grunt.loadNpmTasks('grunt-contrib-uglify');

        //config
        var root = grunt.config.get('pkg').root;
        var pathGenerated = root+grunt.config.get('pkg').pathGenerated;

        // delete generated js
        if(pathGenerated != null && pathGenerated != '' && pathGenerated != undefined && grunt.file.exists(pathGenerated)) {
            var styles = grunt.file.expand({}, pathGenerated+'*.js');
            if(styles.length) {
                var i = styles.length;
                while(i--) {
                    grunt.log.writeln('delete '+styles[i]);
                    grunt.file.delete(styles[i]);
                }
            }
            if(grunt.file.exists(pathGenerated+'version_js.php')) {
                grunt.log.writeln('delete '+pathGenerated+'version_js.php');
                grunt.file.delete(pathGenerated+'version_js.php');
            }
        } else {
            grunt.file.mkdir(pathGenerated);
            grunt.log.writeln('mkdir '+pathGenerated);
        }
        // get current timestamp for versionning
        var date = new Date(),
            versionNum = date.getTime();
        
        // recupere le fichier assets.json avec tous les assets requis
        var assets = grunt.file.readJSON('assets.json');


        // construit les options de chaque tache
        var uglifyConfig = {
            compress: {
				options: {
					mangle: {
						except: ['jQuery', 'Backbone']
					}
				},
                files: {}
            }
        };
        
        for(var groupName in assets.scripts) {
            if(assets.scripts[groupName].length) {
                uglifyConfig.compress.files[pathGenerated+groupName+'_'+versionNum+'.js'] = assets.scripts[groupName].map(function(src) {
                    return root+src;
                });
            }
        }
        
        grunt.config.set('uglify', uglifyConfig);
        
        grunt.task.run('uglify');
        
        // sauvegarde timestamp pour le versionning
        grunt.file.write(pathGenerated+'version_js.php', '<?php $CURRENT_VERSION = "'+versionNum+'"; ?>');
    });

    /**
     * refresh JSON assets cache from php API
     * example : grunt gather
     */
    grunt.registerTask('gather', ['http']);

    /**
     * deploy : make everything u need for prod
     * example : grunt deploy --force
     *           grunt deploy:upload --force to directly upload to the FTP
     */
    grunt.registerTask('deploy', function(ftpupload) {
        var tasks = ['gather', 'compress-js', 'compress-css'];
        if(ftpupload === 'upload') {
            tasks.push('ftp-upload');
        }
        grunt.task.run(tasks);
    });

    grunt.registerTask('ftp-upload', function() {
        grunt.loadNpmTasks('grunt-ftp-deploy');
        var root = grunt.config.get('pkg').root;
        var pathGenerated = root+grunt.config.get('pkg').pathGenerated;
        var pathGeneratedOnline = grunt.config.get('pkg').ftpDest+grunt.config.get('pkg').pathGenerated;

        grunt.config.set('ftp-deploy', {
            build: {
                auth: {
                    host: grunt.config.get('pkg').ftpHost,
                    port: 21,
                    authKey: 'ftpaccess'
                },
                src: pathGenerated,
                dest: pathGeneratedOnline
            }
        });
        grunt.task.run('ftp-deploy');
    });



        /**
         * gather info about tablename in database
         */
        grunt.registerTask('gatherdatabase', function(tablename) {
            if(!tablename || !tablename.length) {
                grunt.fail.fatal("Need tablename argument");
            }
            grunt.config.set('http', {
                gatherTablename: {
                    url: grunt.config.get('pkg').url+grunt.config.get('pkg').root+'/grunt.php?task=bdd&tablename='+tablename,
                    method: 'POST',
                    dest: '_'+tablename+'.json'
                }
            });
            grunt.task.run('http:gatherTablename');
        });

        grunt.registerTask('createmodel', function(tablename, classname, packagename) {
            if(!tablename || !tablename.length || !classname || !classname.length || !packagename || !packagename.length) {
                grunt.fail.fatal("Need tablename, classname and packagename arguments");
            }

            //grunt.loadNpmTasks('grunt-lodash');

            //config
            var root = grunt.config.get('pkg').root;
            var pathModel = root+grunt.config.get('pkg').pathModels;

            //test
            if(!grunt.file.exists(pathModel)) {
                grunt.fail.fatal(pathModel+" doesn't exist");
            }

            //check if table exist
            if(!grunt.file.exists('_'+tablename+'.json')) {
                grunt.fail.fatal("You have to call 'grunt gatherdatabase:"+tablename+"' before");
            }
            var fields = grunt.file.readJSON('_'+tablename+'.json');

            var alreadyExists = grunt.file.exists(pathModel+packagename);
            if(alreadyExists) {
                grunt.fail.warn('Model already exist ');
            } else {
                grunt.file.mkdir(pathModel+packagename);
                grunt.log.write("Create dir "+packagename);
            }



            var obj = {
                id_field : '',
                fields : [],
                classname : classname,
                tablename : tablename,
                namespace : packagename
            };


            var dbTypeToPhp = {
              'varchar' : 'string',
              'text' : 'string',
              'string' : 'string',
              'blob' : 'string',
              'datetime' : 'string',
              'tinyint' : 'int',
              'int' : 'int'
            };

            for(var i = 0; i < fields.length; i++) {
                if(fields[i].Extra === 'auto_increment' && fields[i].Key === 'PRI') {
                    obj.id_field = fields[i].Field;
                }
                var type = fields[i].Type.split('(')[0];
                var phpType = dbTypeToPhp[type] ? dbTypeToPhp[type] : 'mixed';
                var field = {
                  libelle : fields[i].Field,
                  field : fields[i].Field,
                  type : type,
                  required : fields[i].Null === 'NO' ? 'false' : 'true',
                  vartype : phpType
                };
                obj.fields.push(field);
            }

            var tmplSrc = grunt.file.read('tmpl/model/ClassName.txt');
            var model = grunt.template.process(tmplSrc, {data : obj});
            grunt.file.write(pathModel+packagename+'/'+classname+'.php', model);

            var tmplSrc = grunt.file.read('tmpl/model/ClassNameCollection.txt');
            var col = grunt.template.process(tmplSrc, {data : obj});
            grunt.file.write(pathModel+packagename+'/'+classname+'Collection.php', col);


            grunt.file.delete('_'+tablename+'.json');

        });

    
};
