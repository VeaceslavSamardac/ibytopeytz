$(document).ready(function() {
  $(".login-link").overlay({ mask: { color: '#000', opacity: 0.4 }, effect: 'default', fixed: false, onBeforeLoad: function() {
			var wrap = this.getOverlay().find(".popupcontent");
			if(wrap.is(":empty")) wrap.load("/user/register");
  }});
});




