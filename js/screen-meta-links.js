

(function($, links, panels){
	'use strict'	
	
	$(document).on('ready', function(){
		var container = $('#screen-meta-links');
		var container_panels = $('#screen-meta');
		var linkTag; //if we have a panel it's a button - otherwise it's a link anchor
		debugger;
		if (!container.length){
			container = $('<div />')
				.attr({
					'id' : 'screen-meta-links'
				});
			container.insertAfter('#screen-meta');
		}
		
		$.each( links, function( i, element ) {
			
			if (panels[i]){
				container_panels.append(
					$('<div />')
						.attr({
							'id' : element.id + '-wrap',
							'class' : 'hidden',
							'tabindex' : '-1',
							'aria-label' : element.text + ' Tab'
						})
						.html(panels[i])
				);
				
				linkTag = '<button />'; 
			}else{
				linkTag = '<a />';
			}
			
			container.append(
				$('<div />')
					.attr({
						'id' : element.id + '-link-wrap',
						'class' : 'hide-if-no-js screen-meta-toggle custom-screen-meta-link-wrap'
					})
					.append( $( linkTag, element) )
			);
		});
	});

	

})( jQuery, sml.links, sml.panels );