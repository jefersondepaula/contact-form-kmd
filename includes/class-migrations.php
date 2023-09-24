<?php
/**
 * KMD_Migrations
 *
 * This class is responsible for setting up and migrating database tables required 
 * by our plugin, as well as seeding the tables with initial data.
 */
class KMD_Migrations {

     /**
     * Run the migrations.
     * 
     * Sets up the required database tables for the plugin.
     */
    public static function run() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Define and execute the SQL for creating the kmd_forms table
        $table_name_forms = $wpdb->prefix . 'kmd_forms';
        $sql_forms = "CREATE TABLE $table_name_forms (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            shortcode varchar(255) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

          // Define and execute the SQL for creating the kmd_submissions table
        $table_name_submissions = $wpdb->prefix . 'kmd_submissions';
        $sql_submissions = "CREATE TABLE $table_name_submissions (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            form_id mediumint(9) NOT NULL,
            full_name varchar(255) NOT NULL,
            address text NOT NULL,
            telephone varchar(50) NOT NULL,
            email varchar(255) NOT NULL,
            message text NOT NULL,
            submitted_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (form_id) REFERENCES $table_name_forms(id)
        ) $charset_collate;";

        // Execute the SQL statements
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_forms);
        dbDelta($sql_submissions);
    }
    
    /**
     * Seed the database tables.
     */
    public static function seed() {
        self::seed_forms();
        self::seed_submissions();        
    }

    /**
     * Seed the kmd_forms table with sample form entries.
     */
    private static function seed_forms() {
        global $wpdb;
        $table_name_forms = $wpdb->prefix . 'kmd_forms';

        // Define sample form entries
        $sample_forms = array(
            array('name' => 'Sample Form 1', 'shortcode' => 'contact-kmd-sample1'),
            array('name' => 'Sample Form 2', 'shortcode' => 'contact-kmd-sample2'),
            array('name' => 'Sample Form 3', 'shortcode' => 'contact-kmd-sample3'),
            array('name' => 'Sample Form 4', 'shortcode' => 'contact-kmd-sample4'),
        );

        // Insert each form into the table only if it doesn't already exist
        foreach ($sample_forms as $form) {
            if (!$wpdb->get_var($wpdb->prepare("SELECT id FROM {$table_name_forms} WHERE name = %s", $form['name']))) {
                $wpdb->insert($table_name_forms, $form, array('%s', '%s'));
            }
        }
    }

    /**
     * Seed the kmd_submissions table with sample submission entries.
     */
    private static function seed_submissions() {
        global $wpdb;
        $table_name_submissions = $wpdb->prefix . 'kmd_submissions';
        $table_name_forms = $wpdb->prefix . 'kmd_forms';

        // Define sample submission entries
        $sample_submissions = array(
            array('full_name' => 'John Doe 1', 'form_name' => 'Sample Form 1', 'message' => 'Message from John Doe 1'),
            array('full_name' => 'John Doe 2', 'form_name' => 'Sample Form 2', 'message' => 'Message from John Doe 2'), 
            array('full_name' => 'John Doe 3', 'form_name' => 'Sample Form 3', 'message' => 'Message from John Doe 3'),            
            array('full_name' => 'John Doe 4', 'form_name' => 'Sample Form 4', 'message' => 'Message from John Doe 4'),    
        );

        // For each submission, find the corresponding form's ID and insert the submission
        foreach ($sample_submissions as $submission) {
            $form_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$table_name_forms} WHERE name = %s", $submission['form_name']));
            
            if ($form_id && !$wpdb->get_var($wpdb->prepare("SELECT id FROM {$table_name_submissions} WHERE email = %s", $submission['full_name']))) {
                // Construct submission data using the form's ID
                $data = array(
                    'form_id' => $form_id,
                    'full_name' => $submission['full_name'],
                    'address' => '123 Sample Street',
                    'telephone' => '123-456-7890',
                    'email' => strtolower(str_replace(' ', '.', $submission['full_name'])) . '@example.com',
                    'message' => $submission['message']
                );

                $wpdb->insert($table_name_submissions, $data, array('%d', '%s', '%s', '%s', '%s', '%s'));
            }
        }
    }
}

