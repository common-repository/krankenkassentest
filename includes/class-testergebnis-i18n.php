<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.krankenkasseninfo.de
 * @since      1.0.0
 *
 * @package    Testergebnis
 * @subpackage Testergebnis/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Testergebnis
 * @subpackage Testergebnis/includes
 * @author     Krankenkasseninfo.de <info@krankenkasseninfo.de>
 */
class Testergebnis_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'testergebnis',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
