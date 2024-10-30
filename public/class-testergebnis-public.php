<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.krankenkasseninfo.de
 * @since      1.0.0
 *
 * @package    Testergebnis
 * @subpackage Testergebnis/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Testergebnis
 * @subpackage Testergebnis/public
 * @author     Krankenkasseninfo.de <info@krankenkasseninfo.de>
 */
class Testergebnis_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->testergebnisse_options = get_option($this->plugin_name);

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Testergebnis_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Testergebnis_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/testergebnis-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Testergebnis_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Testergebnis_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/testergebnis-public.js', array( 'jquery' ), $this->version, false );

	}

	/* Fake Page Creation */

	public function define_hooks() {
		if(trim($this->testergebnisse_options['Testergebnis-Slug']) != '') {
			add_filter( 'the_posts', array( $this, 'KKTE_fakepage_detect' ), - 10 );
		}
	}

	/* FAKE Page Function */
	public function KKTE_fakepage_detect($posts){
		global $wp;
		global $wp_query;

		global $fakepage_detect; // used to stop double loading

		if(is_array($wp->query_vars)) {

			if ( ! $fakepage_detect && ( strtolower( $wp->request ) == $this->testergebnisse_options['Testergebnis-Slug'] || ( array_key_exists( 'page_id', $wp->query_vars ) && $wp->query_vars['page_id'] == $this->testergebnisse_options['Testergebnis-Slug'] ) ) ) {
				// stop interferring with other $posts arrays on this page (only works if the sidebar is rendered *after* the main page)
				$fakepage_detect = true;

				// create a fake virtual page
				$post                 = new stdClass;
				$post->post_author    = 1;
				$post->post_name      = $this->testergebnisse_options['Testergebnis-Site-Title'];
				$post->guid           = get_bloginfo( 'wpurl' ) . '/' . $this->testergebnisse_options['Testergebnis-Slug'];
				$post->post_title     = $this->testergebnisse_options['Testergebnis-Site-Title'];
				$post->post_content   = $this->KKTE_load_test();
				$post->ID             = - 999;
				$post->post_type      = 'page';
				$post->post_status    = 'static';
				$post->comment_status = 'closed';
				$post->ping_status    = 'closed';
				$post->comment_count  = 0;
				$post->post_date      = current_time( 'mysql' );
				$post->post_date_gmt  = current_time( 'mysql', 1 );
				$posts                = null;
				$posts[]              = $post;

				// make wpQuery believe this is a real page too
				$wp_query->is_page     = true;
				$wp_query->is_singular = true;
				$wp_query->is_home     = false;
				$wp_query->is_archive  = false;
				$wp_query->is_category = false;
				unset( $wp_query->query["error"] );
				$wp_query->query_vars["error"] = "";
				$wp_query->is_404              = false;
			}
		}

		return $posts;
	}


	/**
	 * Registers all shortcodes at once
	 *
	 * @return [type] [description]
	 */
	public function register_shortcodes() {
		add_shortcode( 'Testergebnisse', array( $this, 'KKTE_load_test') );
	}


	/* Load Content from Test */

	public function KKTE_load_test() {
		$slug = $this->testergebnisse_options['Option'];
		// jSON URL which should be requested
		$json_url = 'https://cdn.krankenkasseninfo.de/wp_api/index.php?option='. $slug;

		$response = wp_remote_get($json_url);
		$result = wp_remote_retrieve_body($response);

		$result = json_decode($result, true);
		$content = $this->formatContent($result);

		return $content;
	}

	public function formatContent($result) {

		$content = '';

		$server = $_SERVER['SERVER_NAME'];

		if($this->testergebnisse_options['CSS-Version'] === 'bootstrap3') {
			$row = 'row';
			$col1 = 'col-xs-12 col-sm-12';
			$col2 = 'col-xs-8 col-sm-9';
			$col3 = 'col-xs4 col-sm-3';
		} elseif($this->testergebnisse_options['CSS-Version'] === 'bootstrap4') {
			$row = 'row';
			$col1 = 'col-12';
			$col2 = 'col-9';
			$col3 = 'col-3';
		} else {
			$row = 'row testergebnis-row';
			$col1 = 'col-ts-12 testergebnis-col-1';
			$col2 = 'col-ts-9 testergebnis-col-2';
			$col3 = 'col-ts-3 testergebnis-col-3';
		}

		if(array_key_exists('name', $result)) {
			$content = '<h2 class="testergebnis_headline">'. $result['name'] .'</h2>'
			           .'<p class="testergebnis_description">'. $result['description'].'</p>';
		}

		if(array_key_exists('krankenkassen', $result)) {
			foreach($result['krankenkassen'] as $key => $value) {
				if(trim($value['text']) === '') {
					$value['text'] = '&nbsp;';
				}
				$content.= '<div class="'. $row .'">
								<div class="'. $col1 .'">
									<strong>'. $value['name'] .'</strong>
								</div>
								<div class="'. $col2 .'">
									'. $value['text'] .'
								</div>
								<div class="'. $col3 .'">';
				if($this->testergebnisse_options['Testergebnis-Sterne'] == 0) {
					$content.= '&nbsp;';
				} else {
					$content.= '<img src="'. plugin_dir_url( dirname( __FILE__ ) ) .'images/stern-'. $value['sterne'] .'_2.png" alt="'. $value['sterne'] .' Sterne für '. $value['name'] .'" />';
				}
				//$content.= '	</div>';

				if($this->testergebnisse_options['Testergebnis-Informationen'] == 1) {
					if($value['info_slug'] != '') {
						$content .= '<a href="' . $value['info_slug'] . '" alt="Informationen für ' . $value['name'] . ' anfordern?ref='. $server .'" class="btn btn-primary" rel="nofollow" target="_blank">Informationen anfordern</a>';
					}
				}

				$content.= '</div></div>';
			}
		}

		$content.= '<p>'. $result['version'] .'</p>';

		if(array_key_exists('error', $result)) {

			$content = '<p>Das Testergebnis kann nicht angezeigt werden.</p><p>Für eine aktuelle Übersicht besuchen sie bitte <a href="https://www.krankenkasseninfo.de/test/">krankenkasseninfo.de</a></p>';
		}

		return $content;

	}


}
