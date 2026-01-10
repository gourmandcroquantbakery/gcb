use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $visitor_email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $cakeFlavor = filter_var($_POST['cakeFlavor'], FILTER_SANITIZE_STRING);
    $groupSize = filter_var($_POST['groupSize'], FILTER_SANITIZE_STRING);
    $cakeDesign = filter_var($_POST['cakeDesign'], FILTER_SANITIZE_STRING);
    $specialRequest = filter_var($_POST['specialRequest'], FILTER_SANITIZE_STRING);
    if (!$visitor_email) {
        die("Invalid email address.");
    }
    if (isset($_FILES['cakeDesign']) && $_FILES['cakeDesign']['error'] == 0) {
        $fileTmp = $_FILES['cakeDesign']['tmp_name'];
        $fileName = $_FILES['cakeDesign']['name'];
        $mail->addAttachment($fileTmp, $fileName);
    }

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'nesrinallalen@gmail.com';      // Your SMTP username
        $mail->Password = '135790nes';      // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use 'tls' or 'ssl'
        $mail->Port = 587;                           // e.g., 587 for TLS, 465 for SSL

        // Recipients
        $mail->setFrom('nesrinallalen@gmail.com', 'Gourmand Croquant Orders'); // Must be a valid email on your SMTP server
        $mail->addAddress('nesrinallalen@gmail.com', 'Gourmand Croquant Bakery'); // Your email address to receive the forms
        $mail->addReplyTo($visitor_email, $name); // Set reply-to to the visitor's email

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = "New Form Submission: $name";
        $mail->Body    = "
            <h2>Contact Form Details</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $visitor_email</p>
            <p><strong>Cake Flavor:</strong> $cakeFlavor</p>
            <p><strong>Group Size:</strong><br>$groupSize</p>
            <p><strong>CakeDesign:</strong><br>$cakeDesign</p>
            <p><strong>Special Request:</strong><br>$specialRequest</p>
        ";
        $mail->AltBody =
        "Name: $name
        Email: $visitor_email
        Cake Flavor: $cakeFlavor
        Group Size: $groupSize
        Special Request: $specialRequest"; 

        $mail->send();
        echo 'Message has been sent successfully!';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    // Redirect if accessed directly
    header("Location: order.html");
    exit();
}