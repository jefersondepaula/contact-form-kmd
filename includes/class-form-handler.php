<?php 

/**
 * KMD_Form_Handler
 *
 * This class provides a handler for operations related to the KMD Forms. 
 * It offers methods to fetch, delete, create forms, and seed data.
 * 
 */
class KMD_Form_Handler {

    /**
     * @var object $wpdb WordPress database access object.
     */
    private $wpdb;

    /**
     * @var string $table_name The name of the forms table in the database.
     */
    private $table_name;

    /**
     * Constructor.
     *
     * Initializes the WordPress DB object and sets the table name.
     */
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'kmd_forms';       
    }

    /**
     * Get all forms.
     *
     * Retrieves all forms from the database.
     *
     * @return array An array of forms.
     */
    public function get_forms() {
        return $this->wpdb->get_results("SELECT * FROM {$this->table_name}");
    }

    /**
     * Delete a form and its related submissions.
     *
     * @param int $form_id The ID of the form to delete.
     * @return void
     */
    public function delete_form($form_id) {
        // Delete all submissions related to this form
        $submissions_table = $this->wpdb->prefix . 'kmd_submissions';
        $this->wpdb->delete($submissions_table, array('form_id' => $form_id));

        // Delete the form itself
        $this->wpdb->delete($this->table_name, array('id' => $form_id));
    }

    /**
     * Create a new form.
     *
     * Inserts a new form into the database and generates its associated shortcode.
     *
     * @param string $form_name The name of the form to create.
     * @return void
     */
    public function create_form($form_name) {
        $data = array('name' => $form_name);
        
        // String format for insert
        $format = array('%s');        
        $this->wpdb->insert($this->table_name, $data, $format);

        // Generate shortcode using the newly created form's ID
        $form_id = $this->wpdb->insert_id;
        $shortcode = 'contact-kmd-' . $form_id;

        // Update the form with its shortcode
        $this->wpdb->update(
            $this->table_name,
            array('shortcode' => $shortcode),
            array('id' => $form_id),
            array('%s'),
            array('%d')
        );
    }   

    /**
     * Seed data for forms.
     *
     * Checks if the seed action is requested, then seeds data accordingly.
     * Finally, it redirects to the form page.
     * 
     * @return void
     */
    public static function seed_data() {
        if (isset($_GET['action']) && $_GET['action'] === 'seed') {
            KMD_Migrations::seed();
            wp_redirect(admin_url('admin.php?page=contact-form-kmd'));
            exit;
        }
    }
}
