<?php
// Include the PHP_Email_Form library if it's not autoloaded
// require 'path/to/PHP_Email_Form.php';

// Define the receiving email address
$receiving_email_address = 'your-email@example.com';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    // Ensure all fields are filled out
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        // Validate email format
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Create a new email form instance
            $contact = new PHP_Email_Form;
            $contact->ajax = true;
            $contact->to = $receiving_email_address;
            $contact->from_name = $name;
            $contact->from_email = $email;
            $contact->subject = $subject;

            // Uncomment and configure SMTP settings if needed
            /*
            $contact->smtp = array(
                'host' => 'example.com',
                'username' => 'example',
                'password' => 'pass',
                'port' => '587'
            );
            */

            // Add messages
            $contact->add_message($name, 'From');
            $contact->add_message($email, 'Email');
            $contact->add_message($message, 'Message', 10);

            // Send email and output result
            if ($contact->send()) {
                echo json_encode(['status' => 'success', 'message' => 'Your message has been sent. Thank you!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to send your message. Please try again later.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

  ?>
  
