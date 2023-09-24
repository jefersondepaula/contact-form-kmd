<?php 

/**
 * KMD_Submission_Handler
 * 
 * A handler class responsible for managing operations related to KMD Form submissions.
 * This includes tasks such as fetching all submissions, storing a new submission, 
 * and deleting an existing submission.
 * 
 */
class KMD_Submission_Handler {

    /**
     * @var object $wpdb Instance of WordPress's database access object.
     */
    private $wpdb;

    /**
     * @var string $table_name The name of the submissions table in the database.
     */
    private $table_name;

    /**
     * Constructor.
     * 
     * Sets up the WordPress DB object and initializes the table name for form submissions.
     */
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'kmd_submissions';
    }

    /**
     * Fetches all submissions from the database.
     *
     * @return array An array containing details of all form submissions.
     */
    public function get_submissions() {
        return $this->wpdb->get_results("SELECT * FROM {$this->table_name}");
    }

    /**
     * Saves a new form submission to the database.
     * 
     * @param array $data An associative array containing submission details.
     * @return int|bool The number of rows inserted, or false on error.
     */
    public function save_submission($data) {
        $result = $this->wpdb->insert(
            $this->table_name,
            array(
                'form_id' => intval($data['form_id']),
                'full_name' => sanitize_text_field($data['full_name']),
                'address' => sanitize_textarea_field($data['address']),
                'telephone' => sanitize_text_field($data['telephone']),
                'email' => sanitize_email($data['email']),
                'message' => sanitize_textarea_field($data['message'])
            ),
            array('%d', '%s', '%s', '%s', '%s', '%s')
        );

        return $result;
    }
    
    /**
     * Deletes a specific form submission from the database.
     * 
     * @param int $submission_id The ID of the submission to delete.
     * @return int|bool The number of rows affected, or false on error.
     */
    public function delete_submission($submission_id) {
        return $this->wpdb->delete(
            $this->table_name,
            array('id' => $submission_id),
            array('%d')
        );
    }
}
