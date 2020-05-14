/**
 * plugin.js
 *
 * Released under LGPL License.
 * Copyright (c) 1999-2015 Ephox Corp. All rights reserved
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/*jshint unused:false */
/*global tinymce:true */

/**
 * zotop 扩展工具插件
 */
tinymce.PluginManager.add('zotop_tools', function(editor, url) {	
	
	zotop_tools(editor, editor.settings.tools);

	function zotop_tools(editor, tools){
	    
	    $.each(tools, function(index, tool){
	    	
	        editor.addButton(index, {
	            text: tool.text,
	            title: tool.title || tool.text,
	            icon: tool.icon,
	            onclick: function(){

	                var dialog = $.dialog({
	                    id           : 'insert-html',
	                    url          : tool.url || tool.href,
	                    title        : tool.title || tool.text,
	                    width        : tool.width || '90%',
	                    height       : tool.height || '80%',
			            padding      : 0,
			            ok           : $.noop,
			            cancel       : $.noop,
			            oniframeload : function() {
			                this.loading(false);
			            },            
	        			opener       :window
	                }, true).loading(true); ;

	                dialog.addEventListener('close', function(){
	                		var data = this.returnValue;
	                        console.log(data);

	                        if ( typeof(data) == 'string' ){
	                            editor.insertContent(data);
	                            return true;
	                        }
	                      
	                        var html='';

	                        for (var i=0; i<data.length; i++) {
	                        	var item = data[i];
	                            
	                            if ( item.type == 'image') {
	                                html += '<p style="text-align:center;text-indent:0"><img src="'+ item.url +'" alt="'+ item.name +'" class="insert-image"/></p>';
	                            } else if (item.type == 'video') {
	                            	html += '<p style="text-align:center;text-indent:0"><video src="'+ item.url +'" controls="controls" width="500" height="400">Your browser does not support the video element.</video></p>';
	                            	//html += '<p><embed quality="high" src="'+ item.url +'" type="application/x-shockwave-flash" allowScriptAccess="always" allowFullScreen="true" mode="transparent" width="500" height="400"></embed></p>';
	                            } else if (item.type == 'audio') {
	                            	html += '<p style="text-align:center;text-indent:0"><audio src="'+ item.url +'" controls="controls">Your browser does not support the audio element.</audio></p>';
	                            } else {
	                            	html += '<a href="'+ item.url +'" title="'+ item.name +'" target="_blank">'+ item.name +'</a>';
	                            }
	                        }

	                        editor.insertContent(html);
	                        return true;
	                    });
	            }
	        });

	    });
	}	
});
