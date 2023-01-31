var Loader = new Class({
	message: null,
	initialize: function(message){
		this.setMessage(message);
	},
	setMessage: function(message){
		if(!Default.isEmpty(message)) this.message = message;
	},
	show: function(){
		if(!Default.isEmpty(this.message)){
			this.message;
		} else {
			this.message = 'Executando ação, por favor aguarde.';
			// this.message = '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
		}

		jQuery("body").prepend("<div id='ajax-loader'><div class='background'></div><div class='center text-center'><div class='loader'></div><br>"+this.message+"</div></div>");
		jQuery("#ajax-loader").hide().fadeIn('slow');
	},
	hide: function(){
		jQuery("#ajax-loader").show().fadeOut('slow',function(){
			this.remove();
		});        
		console.log('hide loader');}
});