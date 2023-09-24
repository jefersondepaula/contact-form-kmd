<!-- Main Wrapper for the Contact Forms KMD Admin Page -->
<div class="wrap">

    <!-- Main Heading -->
    <h2>Contact Forms KMD</h2>

    <!-- Section for Form Creation -->
    <h3>Create New Form</h3>
    <form method="post">
        
        <!-- Security nonce for form validation & prevention of CSRF attacks -->
        <?php wp_nonce_field('kmd_new_form', 'kmd_new_form_nonce'); ?>

        <!-- Table structure for form inputs -->
        <table class="form-table">
            <tr>
                <th><label for="form_name">Form Name:</label></th>
                <!-- Input field for entering the name of the new form -->
                <td><input type="text" id="form_name" name="form_name" required class="regular-text"></td>
            </tr>
        </table>

        <!-- Submit button for form creation and a button to generate seed data -->
        <p>
            <input type="submit" value="Create Form" class="button button-primary">
            <a href="<?php echo admin_url('admin.php?page=contact-form-kmd&action=seed'); ?>" class="button">Generate Seed</a>
        </p>
    </form>

    <!-- Section to List All Existing Forms -->
    <h3>Existing Forms</h3>
    
    <!-- Table structure to display the existing forms, their shortcodes, and associated actions -->
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col" class="manage-column">Name</th>
                <th scope="col" class="manage-column">Shortcode</th>
                <th scope="col" class="manage-column">Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through each form and display its details -->
            <?php if (!empty($forms)) : ?>
                <?php foreach ($forms as $form) : ?>
                    <tr>
                        <!-- Display form name -->
                        <td><?php echo esc_html($form->name); ?></td>
                        <!-- Display shortcode for embedding the form -->
                        <td><code>[contact-kmd id="<?php echo esc_attr($form->id); ?>"]</code></td>
                        <td>
                            <!-- Form for deleting a specific form -->
                            <form method="post">
                                <?php wp_nonce_field('delete_form', 'delete_form_nonce'); ?>
                                <input type="hidden" name="form_id" value="<?php echo esc_attr($form->id); ?>">
                                <!-- Delete button with a confirmation prompt -->
                                <input type="submit" value="Delete" class="button" onclick="return confirm('Are you sure you want to delete this form? All related submissions will also be permanently deleted.');">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <!-- If no forms exist, display a relevant message -->
            <?php else: ?>
                <tr>
                    <td colspan="3">No contact forms found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


