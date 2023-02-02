var Modal =  new Class({
	id : null,
	selector : null,
	url : null,
	params: null,
	success: null,
	width: null,
	height: null,
	loading: true,
	initialize: function(url){
		if(Default.isEmpty(url)) this.url = url;
			this.create();
		this.autoload();
	},
	autoload: function(){
		var self = this;
		jQuery('body')
		// Fecha modal
		.delegate(".modal-close", 'click', function(){
            $('body').removeClass('modal-open');
			if(jQuery(this).data('reload')){
				var url = jQuery(this).data('url');
				self.closeAll();
				location.href = url;
			} else {
				self.closeAll();
			}
		})
		// Fecha modal e carrega pagina definida
		.delegate(".modal-load-url", 'click', function(e){
			e.preventDefault();

			if(!Default.isEmpty(jQuery(this).data('url'))) {
				var _url = jQuery(this).data('url');
			} else {
				var _url = jQuery(this).attr("href");
			}

			var _id = jQuery(this).data('id');
			jQuery(this).closest(".modal-ajax").remove();

			var modal = new Modal();
			var params = {id: _id};
			modal.setParams(params);
			modal.setUrl(_url);
			modal.execute();
		})
		// Envia formulario
		.delegate(".modal-submit", "click", function(){
			var modal = jQuery(this).closest(".modal");
			if( modal.find("form").length) {
			 modal.find("form").submit();
			} else {
				console.log("Modal: Não foi encontrado um formulário.");
			}
		})
		// Fecha o modal com a tecla 'Esc'
		jQuery(document).keydown(function(e){
			if( e.keyCode == 27 || e.keyWhich == 27 ){
				self.closeAll();
			}
		});

	},
	isOpen: function(){
		if( jQuery(this.selector).css('display') != "none" )
			return true;
		else
			return false;
	},
	calculationHeight: function(){
		var windowHeight = jQuery(window).height();
		var modalHeight = jQuery(this.selector).find(".modal-content").height();
		var finalHeight = ( ( windowHeight - modalHeight ) + 400 ) - 100;
		jQuery(this.selector).find(".modal-body").css("max-height", finalHeight + "px");
	},
	create: function(width){
		jQuery('body').addClass('modal-open');
		this.id = "modal-" + Math.floor((Math.random()*10000000)+1);
		this.selector = "#" + this.id;
		var length = jQuery(".modal").length;

		var div =  '<div id="'+this.id+'" class="modal fade modal-ajax in" aria-hidden="true" tabindex="-1" role="dialog" data-backdrop="true">';
			// div += '	<div class="modal-backdrop fade in"></div>';
			div += '	<div class="fade in"></div>';
			div += '	<div class="modal-dialog ' + width + '" role="document">';
			// div += '	<div class="modal-dialog" role="document">';
			div += '		<div class="modal-content">';
			div += '		</div>';
			div += '	</div>';
			div += '</div>';

		jQuery("body").append(div);
	},
	close: function(){
		jQuery('body').find(".modal-ajax").remove();
	},
	closeAll: function(){
		jQuery('body').find(".modal-ajax").remove();
	},
	setUrl: function(url){
		if(!Default.isEmpty(url) ) this.url = url;
	},
	setParams: function(params){
		this.params = params;
	},
	getId: function(){
		return this.selector;
	},
	execute: function(){

		var self = this;

		if( !Default.isEmpty(this.url) ){

			console.log("Modal: Buscando " + this.url);

			if(this.loading ){

				var div  = '<div class="modal-header">';
					div += '    <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>';
					div += '    <h4 class="modal-title">Carregando...</h4>';
					div += '</div>';
					div += '<div class="modal-body text-center">';
					div += '	<div class="loader"></div>';
					div += '</div>';

				jQuery(this.selector).find(".modal-content").html(div);
				jQuery(this.selector).attr("aria-hidden",false).addClass("show");
			}

			var ajax = new Ajax(this.url);

			if( !Default.isEmpty(this.params) )
				ajax.setParams(this.params);

			ajax.setSuccess(function(callback){

				jQuery(self.selector).attr("data-url", self.url);

				if( self.isOpen() ){
					jQuery(self.selector).find(".modal-content").fadeOut(200, function(){
						jQuery(self.selector).find(".modal-content").html(callback);
						jQuery(self.selector).find(".modal-content").fadeIn(200);
						//self.calculationHeight();
					});
				} else {
					jQuery(self.selector).fadeOut(0);
					jQuery(self.selector).find(".modal-content").html(callback);
					jQuery(self.selector).fadeIn(200);
				}

			});

			ajax.execute();

		} else {
			console.log("Modal: URL inválida.");
		}

	}
});
/*
jQuery(document).ready(function(){
	var modal = new Modal();
	modal.autoload();
});
*/
