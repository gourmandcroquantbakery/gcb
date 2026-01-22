use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Sanitize inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $visitor_email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $cakeFlavor = htmlspecialchars(trim($_POST['cakeFlavor']));
    $groupSize = htmlspecialchars(trim($_POST['groupSize']));
    $cakeDesign = htmlspecialchars(trim($_POST['cakeDesign']));
    $specialRequest = htmlspecialchars(trim($_POST['specialRequest']));

    if (!$visitor_email) {
        die("Invalid email address.");
    }

    // Create mail object FIRST
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'nesrinallalen@gmail.com';
        $mail->Password   = getenv('GMAIL_APP_PASSWORD'); // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('nesrinallalen@gmail.com', 'Gourmand Croquant Orders');
        $mail->addAddress('nesrinallalen@gmail.com');
        $mail->addReplyTo($visitor_email, $name);

        // Attachment (renamed input)
        if (isset($_FILES['cakeDesignFile']) && $_FILES['cakeDesignFile']['error'] === 0) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($_FILES['cakeDesignFile']['type'], $allowedTypes)) {
                $mail->addAttachment(
                    $_FILES['cakeDesignFile']['tmp_name'],
                    $_FILES['cakeDesignFile']['name']
                );
            }
        }

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "New Cake Order from $name";
        $mail->Body = "
            <h2>Order Details</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $visitor_email</p>
            <p><strong>Cake Flavor:</strong> $cakeFlavor</p>
            <p><strong>Group Size:</strong> $groupSize</p>
            <p><strong>Cake Design Notes:</strong> $cakeDesign</p>
            <p><strong>Special Request:</strong> $specialRequest</p>
        ";

        $mail->AltBody = "
Name: $name
Email: $visitor_email
Cake Flavor: $cakeFlavor
Group Size: $groupSize
Cake Design: $cakeDesign
Special Request: $specialRequest
        ";

        $mail->send();
        echo "Message sent successfully! 🎂";

    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }

} else {
    header("Location: order.html");
    exit();
}
