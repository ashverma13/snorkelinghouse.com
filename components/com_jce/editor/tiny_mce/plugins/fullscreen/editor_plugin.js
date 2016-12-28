(function(){var each=tinymce.each;tinymce.create('tinymce.plugins.FullScreen',{init:function(ed,url){var t=this,state=false,DOM=tinymce.DOM;this.editor=ed;ed.addCommand('mceFullScreen',function(){t._toggleFullScreen(state);state=!state});this.width=0;this.height=0;this.tmpWidth=0;this.tmpHeight=0;ed.onInit.add(function(ed){var iframe=DOM.get(ed.id+'_ifr');t.tmpWidth=iframe.clientWidth;t.tmpHeight=iframe.clientHeight});ed.addButton('fullscreen',{title:'fullscreen.desc',cmd:'mceFullScreen'});ed.onNodeChange.add(function(ed,cm,n){cm.setActive('fullscreen',tinymce.DOM.hasClass(ed.getContainer(),'fullscreen'))})},_isActive:function(){return tinymce.DOM.hasClass(this.editor.getContainer(),'fullscreen')},getWidth:function(){return this._isActive()?this.width:this.tmpWidth},getHeight:function(){return this._isActive()?this.height:this.tmpHeight},_toggleFullScreen:function(state){var ed=this.editor,cm=ed.controlManager,element=ed.getElement(),container=ed.getContainer(),DOM=tinymce.DOM,w,h,f,th=0,fh=0;var iframe=DOM.get(ed.id+'_ifr');if(!state){var toolbar=DOM.getParent(ed.id+'_toolbargroup','tr');var footer=DOM.getParent(ed.id+'_path_row','tr');if(toolbar){th=toolbar.offsetHeight}if(footer){fh=footer.offsetHeight}this.tmpWidth=iframe.clientWidth;this.tmpHeight=iframe.clientHeight;DOM.addClass(container,'fullscreen');var win=DOM.getViewPort();this.width=win.w-(container.clientWidth-iframe.clientWidth);this.height=win.h-(th+fh+15);DOM.setStyles(iframe,{width:this.width,height:this.height});DOM.hide('text_resize')}else{DOM.removeClass(container,'fullscreen');DOM.setStyles(container,{width:'auto',height:'auto'});DOM.setStyles(iframe,{width:this.getWidth(),height:this.getHeight()});DOM.show('text_resize')}ed.focus();if(tinymce.isGecko){f=DOM.add(document.body,'input');f.focus();DOM.remove(f);ed.focus()}window.scrollTo(0,container.offsetTop)},getInfo:function(){return{longname:'FullScreen',author:'Ryan Demmer',authorurl:'http://www.joomlacontenteditor.net',infourl:'http://www.joomlacontenteditor.net',version:'2.0.4'}}});tinymce.PluginManager.add('fullscreen',tinymce.plugins.FullScreen)})();