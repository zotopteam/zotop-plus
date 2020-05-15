/**
 * tools
 * zotop 扩展工具插件
 */
tinymce.PluginManager.add('tools', function(editor, url) {	

    var tools = editor.getParam('tools', []);
    var icons = editor.ui.registry.getAll().icons;

    // 显示弹窗，并监视回调
    var showDialog = function(type, tool) {
			
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
    			opener       : window
            }, true).loading(true); ;

            dialog.addEventListener('close', function() {
            		var data = this.returnValue;
                    console.log(data);

                    if ( typeof(data) == 'string' ) {
                        editor.insertContent(data);
                        return true;
                    }
                  
                    var html='';

                    for (var i=0; i<data.length; i++) {
                    	var item = data[i];
                        
                        if ( item.type == 'image') {
                            html += '<img src="'+ item.url +'" alt="'+ item.name +'" class="insert-image"/></p>';
                        } else if (item.type == 'video') {
                        	html += '<video src="'+ item.url +'" controls="controls" class="insert-video">Your browser does not support the video element.</video>';
                        } else if (item.type == 'audio') {
                        	html += '<audio src="'+ item.url +'" controls="controls" class="insert-audio">Your browser does not support the audio element.</audio>';
                        } else {
                        	html += '<a href="'+ item.url +'" title="'+ item.name +'" target="_blank">'+ item.name +'</a>';
                        }
                    }

                    editor.insertContent(html);
                    return true;
                });    	
    };

    if (tools) {

	    // 定义tools图标
	    icons[tools] || editor.ui.registry.addIcon('tools','<svg t="1589471905806" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="13431" width="16" height="16" data-spm-anchor-id="a313x.7781069.0.i40"><path d="M512 853.333333C323.84 853.333333 170.666667 700.16 170.666667 512 170.666667 323.84 323.84 170.666667 512 170.666667 700.16 170.666667 853.333333 323.84 853.333333 512 853.333333 700.16 700.16 853.333333 512 853.333333M512 85.333333C276.48 85.333333 85.333333 276.48 85.333333 512 85.333333 747.52 276.48 938.666667 512 938.666667 747.52 938.666667 938.666667 747.52 938.666667 512 938.666667 276.48 747.52 85.333333 512 85.333333M554.666667 298.666667 469.333333 298.666667 469.333333 469.333333 298.666667 469.333333 298.666667 554.666667 469.333333 554.666667 469.333333 725.333333 554.666667 725.333333 554.666667 554.666667 725.333333 554.666667 725.333333 469.333333 554.666667 469.333333 554.666667 298.666667Z" p-id="13432"></path></svg>');

	    // 注册为菜单
	    editor.ui.registry.addMenuButton('tools', {
	    	//text: 'insert',
	    	icon: 'tools',
			fetch: function (callback) {

				var items = [];

				$.each(tools, function(type, tool) {
					items.push({
						type: 'menuitem',
				        text: tool.text,
				        icon: icons[tool.icon] ? tool.icon : null,
				        tooltip: tool.tooltip,
				        onAction: function(_) {
				            showDialog(type, tool);
				        }					
					});
				});

				callback(items);
			}
	    });
    }


    // $.each(tools, function(index, tool){
    // 	console.log(index, tool);

	   //  editor.ui.registry.addButton(index, {
	   //      text: tool.icon,
	   //      tooltip: tool.tooltip,
	   //      onAction: function() {
	            
	   //      }
	   //  });

	   //  editor.ui.registry.addMenuItem(index, {
	   //      text: tool.text,
	   //      onAction: function() {
	            
	   //      }
	   //  });    	
    // });

    return {
        getMetadata: function() {
            return  {
                name: 'zotop_tools',
                url: "http://zotop.com",
            };
        }
    };    
});
