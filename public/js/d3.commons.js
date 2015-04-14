$(function() {
	
	$.d3POST = function(path,params,callback,async){

        var _async = async==undefined?true:false;

        NProgress.start();

        $.ajax({
            async: _async,
            url: path,
            data: params,
            type: "post",
            cache: true,
            dataType: "json",
            success: function (data) {
                NProgress.done();
                callback(data);
            }
        });
    };

    $.d3GET = function(path,params,callback,async){

        var _async = async==undefined?true:false;

        NProgress.start();

        $.ajax({
            async: _async,
            url: path,
            data: params,
            type: "get",
            cache: true,
            dataType: "json",
            success: function (data) {
                NProgress.done();
                callback(data);
            }
        });
    };

    // Similar al str_replace de php
    $.str_replace = function(busca, repla, orig) {
        str     = new String(orig);

        rExp    = "/"+busca+"/g";
        rExp    = eval(rExp);
        newS    = String(repla);

        str = new String(str.replace(rExp, newS));

        return str;
    };

    $.validateEmail = function(email) { 
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    $.ucwords = function(str) {
      return (str + '')
        .replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function($1) {
          return $1.toUpperCase();
        });
    }

});

var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    },
    iPhone: function() {
        return navigator.userAgent.match(/iPhone/i);
    }
};