/*! CMS js */

/**
 * 翻译 TODO 引入翻译文件
 * 
 * @param  sting source 待翻译文本
 * @param  mixed params 可选 参数
 * @return string 翻译后文本
 */
$.trans = function(source, params) {
    if ( arguments.length === 1 ) {
        return function() {
            var args = $.makeArray( arguments );
            args.unshift( source );
            return $.trans.apply( this, args );
        };
    }
    if ( params === undefined ) {
        return source;
    }
    if ( arguments.length > 2 && params.constructor !== Array  ) {
        params = $.makeArray( arguments ).slice( 1 );
    }
    if ( params.constructor !== Array ) {
        params = [ params ];
    }
    $.each( params, function( i, n ) {
        source = source.replace( new RegExp( "\\{" + i + "\\}", "g" ), function() {
            return n;
        } );
    } );
    return source;
};