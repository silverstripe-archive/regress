Behaviour.register({
	'div.passfail input' : {
		initialize : function() {
			this.step = this.parentNode;
			while(this.step.tagName.toLowerCase() != 'li') this.step = this.step.parentNode;
		},
		onclick : function() {
			this.step.setStatus(this.value);
		}
	},
	
	'ul.steps li' : {
		initialize : function() {
			this.statusInputs = [];

			var i,input,inputs = this.getElementsByTagName('input');
			for(i=0;input=inputs[i];i++) {
				this.statusInputs[input.value] = input;
			}
			
			var div,divs = this.getElementsByTagName('div');
			for(i=0;div=divs[i];i++) {
				if(div.className == 'failReason') { this.failReason = div; break; }
			}
		},
	
		setStatus : function(status) {
			this.className = status;
			
			var candStatus;
			for(candStatus in this.statusInputs) {
				if(candStatus == status) {
					Element.addClassName(this.statusInputs[candStatus].parentNode, 'selected');
				} else {
					Element.removeClassName(this.statusInputs[candStatus].parentNode, 'selected');
				}
			}
			
			this.failReason.style.display = (status == 'pass') ? 'none' : '';
		}
	}
});