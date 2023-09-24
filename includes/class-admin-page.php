<?php

/**
 * Class_Admin_Page
 * 
 * This class represents the administration page for the Contact Form KMD plugin.
 * It provides functionality to manage forms, view submissions, and handle 
 * CRUD operations related to the forms and submissions.
 */
class Class_Admin_Page {

    // Form and Submission handler instances
    private $form_handler;
    private $submission_handler;

    /**
     * Constructor: Hook into WordPress admin and initialize handlers.
     */
    public function __construct() {
        // Add a new menu item in the WordPress admin dashboard
        add_action('admin_menu', array($this, 'add_admin_page'));
        
        // Require and instantiate form and submission handlers
        require_once(plugin_dir_path(__FILE__) . '../includes/class-form-handler.php');
        require_once(plugin_dir_path(__FILE__) . '../includes/class-submission-handler.php');
        $this->form_handler = new KMD_Form_Handler();
        $this->submission_handler = new KMD_Submission_Handler();
    }

    /**
     * Register a new menu page in the admin dashboard.
     */
    public function add_admin_page() {
        add_menu_page(
            'Contact Forms KMD', 'Contact Forms KMD', 'manage_options',
            'contact-form-kmd', array($this, 'render_admin_page'), 
            'dashicons-email', 100
        );
    }

    /**
     * Display the admin page contents, including tabs for managing forms and viewing submissions.
     */
    public function render_admin_page() {
        // Determine the active tab (default to 'manage_forms')
        $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'manage_forms';

        // Output admin page structure and tabs
        echo '<div class="wrap">';
        echo '<h2>Contact Form KMD</h2>';
        echo '<h2 class="nav-tab-wrapper">';

        // Tab links
        echo '<a href="?page=contact-form-kmd&tab=manage_forms" class="nav-tab ' . ($active_tab == 'manage_forms' ? 'nav-tab-active' : '') . '">Manage Forms</a>';
        echo '<a href="?page=contact-form-kmd&tab=view_submissions" class="nav-tab ' . ($active_tab == 'view_submissions' ? 'nav-tab-active' : '') . '">View Submissions</a>';
        echo '</h2>';

        // Display content based on the active tab
        switch ($active_tab) {
            case 'manage_forms':
                $this->render_forms_tab();
                break;
            case 'view_submissions':
                $this->render_submissions_tab();
                break;
        }

        echo '</div>';
    }

    /**
     * Render the "Manage Forms" tab.
     */
    public function render_forms_tab() {
        // Log that we're in the admin page (useful for debugging)
        error_log("Rendering the admin page");

        // Handle the creation of a new form
        if (isset($_POST['kmd_new_form_nonce']) && wp_verify_nonce($_POST['kmd_new_form_nonce'], 'kmd_new_form')) {
            $form_name = sanitize_text_field($_POST['form_name']);
            error_log("About to create form with name: $form_name");
            $this->form_handler->create_form($form_name);
            
            // Redirect after form creation to prevent resubmission
            wp_redirect(admin_url('admin.php?page=contact-form-kmd'));
            exit;
        }

        // Handle the deletion of a form
        if (isset($_POST['delete_form_nonce']) && wp_verify_nonce($_POST['delete_form_nonce'], 'delete_form')) {
            $form_id = intval($_POST['form_id']); 
            $this->form_handler->delete_form($form_id);
        }

        // Retrieve all forms to display in the view
        $forms = $this->form_handler->get_forms();

        // Include the view for managing forms
        require_once(plugin_dir_path(__FILE__) . '../admin/views/contact-forms-list.php');
    }

    /**
     * Render the "View Submissions" tab.
     */
    public function render_submissions_tab() {
        // Handle the deletion of a submission
        if (isset($_POST['delete_submission'])) {
            $submission_id = intval($_POST['delete_submission']);             
            $this->submission_handler->delete_submission($submission_id);
            
            // Redirect after deletion to prevent resubmission
            wp_redirect(admin_url('admin.php?page=contact-form-kmd&tab=view_submissions'));
            exit;
        }

        // Fetch submissions and their associated form names
        global $wpdb;
        $table_name_submissions = $wpdb->prefix . 'kmd_submissions';
        $table_name_forms = $wpdb->prefix . 'kmd_forms';

        $submissions = $wpdb->get_results(
            "SELECT s.*, f.name as form_name 
             FROM {$table_name_submissions} s
             LEFT JOIN {$table_name_forms} f ON s.form_id = f.id 
             ORDER BY s.submitted_at DESC"
        );

        // Include the view for viewing submissions
        include plugin_dir_path(__FILE__) . '../admin/views/submissions-view.php';  
    }
}
