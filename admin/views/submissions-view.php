<!-- This form structure displays the submissions in a tabular format. -->
<form method="post" action="">
    <!-- Check if there are any submissions to display -->
    <?php if ($submissions) :?>
        <!-- Table structure to display the submissions -->
        <table class="widefat fixed">
            <!-- Define table header -->
            <thead>
                <tr>
                    <!-- Column headers for each field in the submission -->
                    <th>Form Name</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Telephone</th>
                    <th>Message</th>
                    <th>Submission Date</th>
                    <th>Action</th> <!-- This column provides action buttons, e.g., delete -->
                </tr>
            </thead>
            <tbody>
            <!-- Loop through each submission and display its details -->
            <?php foreach ($submissions as $submission): ?>
                <tr>
                    <!-- Escape output to prevent XSS attacks. It's vital for security. -->
                    <td><?php echo esc_html($submission->form_name); ?></td>
                    <td><?php echo esc_html($submission->full_name); ?></td>
                    <td><?php echo esc_html($submission->email); ?></td>
                    <td><?php echo esc_html($submission->address); ?></td>
                    <td><?php echo esc_html($submission->telephone); ?></td>
                    <td><?php echo esc_html($submission->message); ?></td>
                    <td><?php echo esc_html($submission->submitted_at); ?></td>
                    <td>
                        <!-- Inline form for deleting a specific submission -->
                        <form method="post" action="">
                            <!-- Hidden input to hold the ID of the submission -->
                            <input type="hidden" name="delete_submission" value="<?php echo esc_attr($submission->id); ?>">
                            <!-- Delete button with a confirmation prompt for added user clarity and safety -->
                            <input type="submit" value="Delete" class="button" onclick="return confirm('Are you sure you want to delete this submission?');">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <!-- Display a message if no submissions are found -->
        <p>No submissions found.</p>
    <?php endif; ?>
</form>
