(function(){

    if (!window.session) window.session = {};

    session.loadConfig = function(callback){

        var me = this,
            config = Ext.create('config');

        config.load({
            callback: function(record){
                session.config = record;
                callback();
            }
        });
    };

    session.instanceSession = function(params, callback){

        var me = this;
        // var appName = "minimap";

        var callbackCheck = function(code){

            if(code) {
                callback(code);
                return;
            }

            session.rememberHistory();
            callback(code);

            //             var sess = wialon.core.Session.getInstance();
            //             var token = sess.getToken();

            //             me.checkService(session.config.get('appName'), function(code){
            //                 if(token){
            //                     if(token.app !== appName) callback(7);
            //                 }

            //                 session.rememberHistory();
            //                 callback(code);
            //             });
        };


        this.loadLibraries(Ext.bind(me.auth, me, [params, callbackCheck]));
    };


    session.auth = function(options, callback){
        var me = this,
            sess = wialon.core.Session.getInstance(),
            params = options || me.getParams() || {};
		
        sess.initSession(params.hostUrl || session.config.get('hostUrl'));

        if(params.token || params.access_token){
            sess.loginToken(params.token, callback);
        }else if(params.login || params.user && params.password){
            sess.login(params.login || params.user, params.password, "", callback);
        }else if(params.sid){
            sess.duplicate(params.sid, "", true, callback);
        } else if(params.authhash){
            sess.loginAuthHash(params.authhash, "", true, callback);
        } else callback(8);
    };

    session.rememberHistory = function(){
        var sess = wialon.core.Session.getInstance(),
            token = sess.getToken();

        if(token) return;

        var params = {
            sid: sess.getId()
        };

        if(session.getParams('lang')){
            params.lang = session.getParams('lang');
        }

        if(session.getParams('hostUrl')){
            params.hostUrl = session.getParams('hostUrl');
        }

        history.replaceState({sid: sess.getId()},
                             sess.getId(),
                             "?"+Ext.Object.toQueryString(params));

    };

    session.checkService = function(appName, callback){ 
        wialon.util.Apps.getApplications(1, null, function(codeApp, dataApps){
            var appsId = Ext.Array.pluck(dataApps, 'serviceName');
            var flag = Ext.Array.contains(appsId, appName);
            callback((flag)? 0: 7);
        });
    };
    
    session.loadLocale = function(callback){
        var lang = session.getParams('lang') || session.config.get('lang');
        
        var url = Ext.util.Format.format("resources/data/locale/ext-lang-{0}.js",
                                 lang);

        Ext.Loader.loadScript({
            url: url,
            onLoad: callback
        });
    };
    
    session.loadLibraries = function(callback){
        this.loadWialonApi(Ext.bind(this.loadLocale, this, [callback], false));
    };

    session.loadGoogleApi = function(callback){
        Ext.Loader.loadScript({
            url: 'https://www.google.com/jsapi',
            onLoad: function(){
                google.load("maps", "3", {
                    other_params:"sensor=false&libraries=places",
                    callback: callback
                });
            }
        });
    };

    session.loadWialonApi = function(callback){
        Ext.Loader.loadScript({
            url: 'https://hst-api.wialon.com/wsdk/script/wialon.js',
            onLoad: callback
        });

    };

    session.getParams = function(name){
        var params = null;

        if(location.search){
            params = Ext.Object.fromQueryString(location.search);
        }

        return (params && name)? params[name]: params;
    };

    session.getErrorText = function(code){
        return wialon.core.Errors.getErrorText(code);
    };
})();