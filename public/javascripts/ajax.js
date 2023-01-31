_Ajax = null;
var Ajax = new Class({
	url: null,
	dataType: "html",
	contentType: this.type,
	processData: this.process,
	params: {},
	success: null,
	beforeAjax: null,
	afterAjax: null,
	beforeSend: null,
	appId: null,
	type : "POST",
	error: function(jqXHR, textStatus, errorThrown){
		console.log(jqXHR);		
		console.log(textStatus);		
		console.log(errorThrown);		
	},
	initialize: function(url){
		this.setUrl(url);
	},
	setBeforeAjax: function(beforeAjax){
		if(Default.isFunction(beforeAjax))
			this.beforeAjax = beforeAjax;
	},
	setAfterAjax: function(afterAjax){
		if(Default.isFunction(afterAjax))
			this.afterAjax = afterAjax;
	},
	setbeforeSend: function (beforeSend) {            
		if(Default.isFunction(beforeSend))
			this.beforeSend = beforeSend;
	},
	setSuccess: function(success){
		if(Default.isFunction(success))
			this.success = success;
	},
	setApp: function (app) {
		if(!Default.isEmpty(app))
		this.app = app
	},
	setUrl: function(url){
		if(!Default.isEmpty(url))
			this.url = url;
	},
	setDataType: function(dataType){
		if(!Default.isEmpty(dataType))
			this.dataType = dataType;
	},
	setParams: function(params){
		if(!Default.isEmpty(params))
			this.params = params;
	},
	setType: function(type){
		if(!Default.isEmpty(type))
			this.type = type;
	},
	execute: function(){		
		var self = this;		
		if(Default.isEmpty(this.url)){
			console.log("Ajax: URL não foi especificada.")
			return;
		}		
		if(_Ajax && (_Ajax.readyState != 4 || _Ajax.status != 200)){
			console.log("Ajax: Abortado.");
			console.log( _Ajax );
			_Ajax.abort();
		}		
		// console.log("Ajax: Buscando " + AJAXPATH + this.url);		
		console.log("Ajax: Buscando " + this.url);		
		if(Default.isFunction(this.beforeAjax) )
			this.beforeAjax();
            
		// var newurl = self.app == null ? AJAXPATH + this.url : this.url;
		var newurl = this.url;
		
		_Ajax = jQuery.ajax({
			url:    newurl,
			type:   this.type,
			dataType:   this.dataType,
			data:   this.params,
			cache:  false,
			error:  this.error,
			beforeSend: function(request){
				request.setRequestHeader('Authorization', self.app);
			},
			success: function(callback){
				if(Default.isFunction(self.success))
					self.success(callback);
	
				if(Default.isFunction(self.afterAjax))
					self.afterAjax();
				console.log("Ajax: Finalizado.");
			},
			statusCode: {
				404: function() {
					console.log("Ajax (404): Página não encontrada.");
				},
				500: function(){
					console.log("Ajax (500): Erro de servidor.");				
				}
			}
		});		
	}
});