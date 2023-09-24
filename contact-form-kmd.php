<?php
/*
Plugin Name: Contact Form KMD
Description: Create a contact form and use the shortcode to display whenever you want.
Version: 1.0
Author: Jeferson
*/

// Block direct access to the file for security reasons
if (!defined('ABSPATH')) exit;

/**
 * Enqueue the plugin's CSS stylesheet.
 *
 * This function hooks into WordPress to enqueue the stylesheet specifically for our plugin. 
 * It ensures our styles are loaded only when needed, improving performance.
 */
function kmd_enqueue_plugin_styles() {
    wp_enqueue_style('kmd-plugin-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
}
add_action('wp_enqueue_scripts', 'kmd_enqueue_plugin_styles');

// Load necessary classes for our plugin.
// Organized in the 'includes' directory to maintain a clean structure.
require_once(plugin_dir_path(__FILE__) . 'includes/class-admin-page.php');
require_once(plugin_dir_path(__FILE__) . 'includes/class-shortcode-manager.php');
require_once(plugin_dir_path(__FILE__) . 'includes/class-migrations.php');
require_once(plugin_dir_path(__FILE__) . 'includes/class-submission-handler.php');

// Setup actions upon plugin activation.
// For instance, the 'KMD_Migrations::run' method initializes our database tables.
register_activation_hook(__FILE__, array('KMD_Migrations', 'run'));

// Handle data seeding if necessary during admin initialization.
add_action('admin_init', array('KMD_Form_Handler', 'seed_data'));

/**
 * KMD_Plugin_Initializer class
 * 
 * Initializes various components of our plugin.
 * This class serves as the bootstrap for setting up all other features of our plugin.
 */
class KMD_Plugin_Initializer {

    /**
     * Constructor: Initializes the various components.
     */
    public function __construct() {
        $this->initializeAdminPage();
        $this->initializeShortcodes();
        $this->initializeSubmissions();
    }

    /**
     * Sets up the admin page functionality.
     */
    private function initializeAdminPage() {
        new Class_Admin_Page();
    }

    /**
     * Sets up shortcode management for our plugin.
     */
    private function initializeShortcodes() {
        $shortcode_manager = new ShortcodeManager();
        $shortcode_manager->register_shortcodes();
    }

    /**
     * Initializes the submission handler. 
     * The handler is responsible for processing form submissions.
     */
    private function initializeSubmissions() {
        new KMD_Submission_Handler();
    }
}

// Kick-start our plugin by creating an instance of our initializer.
new KMD_Plugin_Initializer();

