<?php

class qToolsShortcodes {
	public function __construct() {

		// Add shortcodes
		$this::qTools_sc_init();
	}

	public function qTools_sc_init() { 
		add_shortcode( 'qt_quote', array( $this, 'qTools_sc_quote' ) );
		add_shortcode( 'qt_button', array( $this, 'qTools_sc_button' ) );
		add_shortcode( 'qt_lead', array( $this, 'qTools_sc_lead' ) );
	}
	
	public function qTools_sc_quote( $atts, $content = 'Quote' ) {
		$atts = shortcode_atts( array(
					'author' => null,
					'source' => null,
				), $atts, 'qt_quote' );
		
		$content = do_shortcode( $content );

		$sc_output = '';
		
		if ( ! empty( $atts['author'] ) ) {
			$author = esc_attr( $atts['author'] );
			
			$sc_output = $author;
			
			if ( ! empty ( $atts['source'] ) ) {
				$source = esc_url( $atts['source'] );
				
				$sc_output = '<a href="' . $source . '">' . $author . '</a>';
			}
			
			$sc_output = '<cite>' . $sc_output . '</cite>';
		}
		
		$sc_output = '<blockquote>' . balanceTags( $content ) . $sc_output . '</blockquote>';
		
		global $allowedtags;
		$allowedtags['p'] = array( 'style' => array(), 'class' => array() );
		
		return wp_kses( $sc_output, $allowedtags );
	}
	
	public function qTools_sc_button( $atts, $content = 'Button' ) {
		$atts = shortcode_atts( array(
					'url' => '#'
				), $atts, 'qt_button' );
		
		$sc_output = '<a href="' . esc_url( $atts['url'] ) . '" title="' . esc_attr( $content ). '" class="button">' . esc_html( $content ) . '</a>';
		
		return wp_kses( $sc_output, array( 'a' => array( 'href' => array(), 'title' => array(), 'class' => array() ) ) );
		
	}
	
	public function qTools_sc_lead( $atts, $content = null ) {
		$sc_output = '<p class="lead">' . do_shortcode( $content ) . '</p>';
		
		global $allowedtags;
		$allowedtags['p'] = array( 'style' => array(), 'class' => array() );
		
		return wp_kses( $sc_output, $allowedtags );
	}

}

$qtools_shortcodes = new qToolsShortcodes();

?>