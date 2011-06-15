if (typeof(botrObject) == 'undefined') {
	var botrObject = {};
	botrObject.players = [];
	botrObject.isDomReady = function() {
		var d = document;
		if (d && d.getElementsByTagName && d.getElementById && d.body) {
			clearInterval(botrObject.domTimer);
			for(var i=0; i<botrObject.players.length; i++) {
				botrObject.writePlayer(i);
			}
			botrObject.domDone = true;
		}
	};
	botrObject.canPlayFlash = function () {
		var version = '0,0,0,0';
		try {
			try {
				var axo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash.6');
				try { axo.AllowScriptAccess = 'always'; }
				catch(e) { version = '6,0,0'; }
			} catch(e) {}
			version = new ActiveXObject('ShockwaveFlash.ShockwaveFlash').GetVariable(
				'$version').replace(/\D+/g, ',').match(/^,?(.+),?$/)[1];
		} catch(e) {
			try {
				if(navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin){
					version = (navigator.plugins["Shockwave Flash 2.0"] || 
						navigator.plugins["Shockwave Flash"]).description.replace(/\D+/g, ",").match(/^,?(.+),?$/)[1];
				}
			} catch(e) {}
		}
		var major = parseInt(version.split(',')[0]);
		var minor = parseInt(version.split(',')[2]);
		if(major > 9 || (major == 9 && minor > 97)) {
			return true;
		} else {
			return false;
		}
	};
	botrObject.canPlayVideo = function () {
		try {
			return !!document.createElement('video').canPlayType('video/mp4; codecs="avc1.42E01E, mp4a.40.2"');
		} catch (e) { 
			return false;
		}
	};
	botrObject.writePlayer = function(idx) {
		myid = botrObject.players[idx].container;
		var elm = document.getElementById(myid);
		if(botrObject.canPlayFlash()) {
			addme = botrObject.players[idx].getFlashHTML();
			elm.innerHTML = addme;
		} else if (botrObject.canPlayVideo()) {
			addme = botrObject.players[idx].getVideoHTML()
			elm.innerHTML =addme;
		} else {
			addme = botrObject.players[idx].getLinkHTML()
			elm.innerHTML = addme;
		}
		if ($("#"+myid+"_cloned").length) {
			getid = $(addme).attr('id');
			elm = document.getElementById(myid+"_cloned");
			reg = new RegExp(getid,"gm");
			elm.innerHTML =addme.replace(reg, getid+"_cloned");
		}
	};
	botrObject.swf = function (src,btn,img,lnk,id,wth,hei) {
		if (!document.getElementById) { return; }
		this.source = src;
		this.button = btn;
		this.image = img;
		this.link = lnk;
		this.sources = [];
		this.id = id+'_swf';
		this.container = id+'_div';
		this.width = wth;
		this.height = hei;
		this.flashvars = {id:this.id};
		this.params = {
			'allowfullscreen':'true',
			'allowscriptaccess':'always',
			'bgcolor':'#000000',
			'wmode':'opaque'
		};
		botrObject.players.push(this);
		if (botrObject.domDone) {
			var len = botrObject.players.length-1;
			setTimeout(function(){botrObject.writePlayer(len)},50);
		}
	};
	botrObject.swf.prototype = {
		getFlashHTML:function() {
			var html = "";
			var fv = this.getVariables();
			if (navigator.plugins && navigator.mimeTypes && navigator.mimeTypes.length) {
				html = '<embed type="application/x-shockwave-flash" src="'+ this.source +'" ';
				html += 'width="'+ this.width +'" height="'+ this.height +'"';
				html += ' id="'+ this.id +'" name="'+ this.id +'" ';
				for(var key in this.params) {
					html += [key] +'="'+ this.params[key] +'" ';
				}
				html += 'flashvars="'+ fv +'" />';
			} else {
				html = '<object id="'+ this.id +'" name="'+ this.id +'" width="'+ this.width + '" ';
				html += 'classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" height="'+ this.height +'">';
				html += '<param name="movie" value="'+ this.source +'" />';
				for(var key in this.params) { 
					html += '<param name="'+ key +'" value="'+ this.params[key] +'" />';
				}
				html += '<param name="flashvars" value="'+ fv +'" />';
				html += "</object>";
			}
			return html;
		},
		getVideoHTML:function() {
			var idx = 0;
			if(this.sources.length == 0) { 
				return this.getLinkHTML();
			}
			this.sources.sort(function(a,b){return a.width-b.width;});
			for(var i=0; i<this.sources.length; i++) {
				if(this.sources[i]['width'] > screen.width * 1.2 || this.sources[i]['width'] > 1000) {
					break;
				} else {
					idx = i;
				}
			}
			var html = "<video src='"+this.sources[idx].url+"' ";
			html += "width='"+this.width+"' ";
			html += "height='"+this.height+"' ";
			html += "poster='"+this.image+"' ";
			html += "style='margin: 0 auto; padding:0; border:0;' preload='none' ";
			html += "controls>"+this.flashvars.title+"</video>";
			return html;
		},
		getLinkHTML:function() {
			var html = "";
			html += "<a href='"+this.link+"' ";
			html += "title='"+this.flashvars.title+"' style='display:block; position:relative; ";
			html += "width:"+this.width+"px; ";
			html += "height:"+this.height+"px; ";
			html += "background: #000000 url("+this.image+") no-repeat center center;'>";
			if(this.link.substr(-4) != '.jpg') { 
				html += "<img src='"+this.button+"' alt='Click to play video' ";
				html += "style='position:absolute; top:"+(Math.round(this.height/2)-30)+"px; ";
				html += "left:"+(Math.round(this.width/2)-30)+"px; border:0;' />";
			}
			html += "</a>";
			return html;
		},
		addSource: function(url,wid) {
			this.sources.push({url:url,width:wid});
		},
		addVariable: function(name,value) {
			this.flashvars[name] = decodeURIComponent(value);
		},
		getVariables: function () {
			var pairs = new Array();
			for(var key in this.flashvars) {
				pairs[pairs.length] = key+"="+encodeURIComponent(this.flashvars[key]);
			}
			return pairs.join('&');
		}
	};
	botrObject.domTimer = setInterval(botrObject.isDomReady,50);
}
