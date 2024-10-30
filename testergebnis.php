<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.krankenkasseninfo.de
 * @since             1.0.0
 * @package           Testergebnis
 *
 * @wordpress-plugin
 * Plugin Name:       Krankenkassentest
 * Plugin URI:        https://www.krankenkasseninfo.de/
 * Description:       Wir bieten Ihnen eine dauerhaft aktuelle Liste von Testergebnissen aller geöffneten gesetzlichen Krankenkassen aus über 60 Bereichen, darunter Osteopathie, Zusatzbeitrag und professionelle Zahnreinigung. Zusätzlich erhalten Sie dabei einen Überblick über alle geöffneten gesetzlichen Krankenkassen und deren Leistungen. Unsere Liste kann via Shortcode oder virtueller Seite eingebunden werden. Fügen Sie Ihrer Website aktuelle Informationen von einem der führenden Branchenanbieter zu.
 * Version:           1.0.0
 * Author:            Krankenkasseninfo.de
 * Author URI:        https://www.krankenkasseninfo.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       testergebnis
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'KRANKENKASSENTEST_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-testergebnis-activator.php
 */
function activate_testergebnis() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-testergebnis-activator.php';
	Testergebnis_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-testergebnis-deactivator.php
 */
function deactivate_testergebnis() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-testergebnis-deactivator.php';
	Testergebnis_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_testergebnis' );
register_deactivation_hook( __FILE__, 'deactivate_testergebnis' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-testergebnis.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_testergebnis() {

	$plugin = new Testergebnis();
	$plugin->run();

}
run_testergebnis();
