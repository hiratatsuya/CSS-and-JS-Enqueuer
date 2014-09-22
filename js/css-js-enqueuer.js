/*
	License: GNU General Public License v2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
	Description: Functionality specific to CSS and JS Enqueuer.
 */
( function( $ ) {

	$( function() {

		var enqueue_css = CodeMirror.fromTextArea( document.getElementById( 'enqueue_css' ) , {
			mode: 'text/plain',
			autofocus: true
		});

		var enqueue_js = CodeMirror.fromTextArea( document.getElementById( 'enqueue_js' ) , {
			mode: 'text/plain'
		});

		var custom_css = CodeMirror.fromTextArea( document.getElementById( 'custom_css' ) , {
			mode: 'text/css'
		});

		var custom_js = CodeMirror.fromTextArea( document.getElementById( 'custom_js' ) , {
			mode: 'text/javascript'
		});

	} );

} )( jQuery );
