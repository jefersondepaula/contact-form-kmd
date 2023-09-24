<?php 

/**
 * The ShortcodeManager class is responsible for managing the shortcodes
 * to render and process the contact form.
 */
class ShortcodeManager {

    /**
     * @var string $submission_message Holds the message after form submission.
     */
    private $submission_message = '';

    /**
     * Registers the shortcodes for use.
     */
    public function register_shortcodes() {
        add_shortcode('contact-kmd', array($this, 'render_form'));
    }

    /**
     * Renders the form based on the attributes provided in the shortcode.
     * 
     * @param array  $atts    Shortcode attributes.
     * @param string $content Optional content within the shortcode (not used).
     * 
     * @return string Rendered HTML of the form or an error message.
     */
    public function render_form($atts, $content = null) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'kmd_forms';
        
        // Extract the ID from the shortcode attributes
        $atts = shortcode_atts(array('id' => 0), $atts, 'contact-kmd');
        $id = intval($atts['id']);

        // If it's a POST request, handle the form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_id']) && intval($_POST['form_id']) === $id) {
            $this->process_submission($_POST);
        }
        
        // Check if the ID exists in the database
        $form = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
        
        // If the form exists, render it, otherwise return an error or empty string
        if ($form) {
            ob_start();
            
            // Display the submission message, if any
            if (!empty($this->submission_message)) {
                echo '<div class="kmd-message">' . esc_html($this->submission_message) . '</div>';
            }
            
            // The HTML for the contact form
            ?>
                <form action="" method="post" class="kmd-contact-form">                
                    <input type="hidden" name="form_id" value="<?php echo esc_attr($id); ?>">

                    <div class="form-group">
                        <label for="full_name">Full Name:</label>
                        <input type="text" name="full_name" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" name="address" required>
                    </div>

                    <div class="form-group">
                        <label for="telephone">Telephone Number:</label>
                        <input type="tel" name="telephone" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="form-group message-group">
                        <label for="message">Message/Note:</label>
                        <textarea name="message" required></textarea>
                    </div>

                    <input type="submit" value="Submit">
                </form>
            <?php
            return ob_get_clean();
        } else {
            return 'Contact form not found, please use the correct shortcode';  
        }
    }    

    /**
     * Processes the form submission and saves the data.
     * 
     * @param array $data POST data from the form submission.
     */
    protected function process_submission($data) {
        $submission_handler = new KMD_Submission_Handler();
        $result = $submission_handler->save_submission($data);      

        // Set the submission message based on the result
        if ($result) {
            $this->submission_message = 'Thank you for your submission!';
        } else {
            $this->submission_message = 'There was an error processing your submission. Please try again.';
        }        
    }
}









