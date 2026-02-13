<?php namespace ProcessWire;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$phpMailerAvailable = false;
try{
  require_once $config->paths->assets . "PHPMailer/src/Exception.php";
  require_once $config->paths->assets . "PHPMailer/src/PHPMailer.php";
  require_once $config->paths->assets . "PHPMailer/src/SMTP.php";
  $phpMailerAvailable = class_exists('PHPMailer\\PHPMailer\\PHPMailer');
}catch(\Throwable $e){}
$statusMsg = '';
$errors = [];
if(($input->post->contact_submit || $input->post->submit) && $page->show_contact_form){
  $name = trim($sanitizer->text($input->post->name));
  $email = $sanitizer->email($input->post->email);
  $phone = trim($sanitizer->text($input->post->phone));
  $message = trim($sanitizer->textarea($input->post->message));
  if(!$name) $errors[] = 'Please enter your name.';
  if(!$email) $errors[] = 'Please enter a valid email.';
  if(!$phone) $errors[] = 'Please enter your phone.';
  if(!$message) $errors[] = 'Please enter your message.';
  if(!$errors){
    $parent = $pages->get('/messages/');
    if(!$parent || !$parent->id) $parent = $pages->get('/admin/messages/');
    $tmpl = $templates->get('contact_message');
    if($tmpl && $parent && $parent->id){
      $p = new Page();
      $p->template = $tmpl;
      $p->parent = $parent;
      $p->title = $name . ' - ' . date('Y-m-d H:i');
      $p->set('customer_name', $name);
      $p->set('customer_email', $email);
      $p->set('customer_phone', $phone);
      $p->set('customer_message', $message);
      $p->set('created_at', time());
      $p->save();
    }
    $adminTo = 'mugdha.chavan@zerovaega.in';
    if($phpMailerAvailable && !empty($config->smtpHost)){
      try{
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $config->smtpHost;
        $mail->SMTPAuth = true;
        $mail->Username = $config->smtpUser;
        $mail->Password = $config->smtpPass;
        $mail->SMTPSecure = $config->smtpSecure ?: 'tls';
        $mail->Port = $config->smtpPort ?: 587;
        $mail->setFrom($config->smtpUser, 'Sweet Delight Team');
        $mail->addAddress($adminTo);
        if($email) $mail->addReplyTo($email, $name);
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Message Received';
        $mail->Body = "<b>Name:</b> ".htmlentities($name)."<br><b>Email:</b> ".htmlentities($email)."<br><b>Phone:</b> ".htmlentities($phone)."<br><br><b>Message:</b><br>".nl2br(htmlentities($message));
        $mail->send();
        if($email){
          $mail->clearAddresses();
          $mail->addAddress($email, $name);
          $mail->Subject = 'Thank You for Your Order';
          $mail->Body = "Dear ".htmlentities($name).",<br><br>Thank you! Your order has been received successfully.<br><br>We will review your request and reach you soon.<br><br>Best Regards,<br>Sweet Delight Team";
          $mail->send();
        }
      }catch(\Throwable $e){
        $wm = wireMail();
        $wm->to($adminTo);
        if($email) $wm->from($email);
        $wm->subject('New Contact Message Received');
        $wm->body("Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message");
        $wm->send();
      }
    }else{
      $mail = wireMail();
      $mail->to($adminTo);
      if($email) $mail->from($email);
      $mail->subject('New Contact Message Received');
      $mail->body("Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message");
      $mail->send();
      if($email){
        $mail2 = wireMail();
        $mail2->to($email);
        $mail2->from($adminTo);
        $mail2->subject('Thank You for Your Order');
        $mail2->body("Dear $name,\n\nThank you! Your order has been received successfully !!.\n\nWe will review your request and reach you soon.\n\nBest Regards,\nSweet Delight Team");
        $mail2->send();
      }
    }
    $statusMsg = 'Thank you! Your message has been sent. We will reach you soon.';
  }
}
?>

<div id="content">
  <section class="section section--gradient contact-page">
    <div class="container">
      <div class="section-header" data-animate>
        <?php if($statusMsg): ?>
          <div class="alert alert-success" role="alert"><?= $statusMsg ?></div>
        <?php endif; ?>
        <?php if(count($errors)): ?>
          <div class="alert alert-danger" role="alert"><?= implode(' ', $errors) ?></div>
        <?php endif; ?>
        <h2><?= $page->title ?: 'Contact Us' ?></h2>
      </div>
      <div class="split">
        <div>
          <div class="card" data-animate>
            <?php if($page->contact_address): ?>
              <h3>Address</h3>
              <p><?= nl2br($page->contact_address) ?></p>
            <?php endif; ?>
            <?php if($page->contact_phone): ?>
              <h3>Phone</h3>
              <p><a href="tel:<?= preg_replace('/\s+/', '', $page->contact_phone) ?>"><?= $page->contact_phone ?></a></p>
            <?php endif; ?>
            <?php if($page->contact_email): ?>
              <h3>Email</h3>
              <p><a href="mailto:<?= $page->contact_email ?>"><?= $page->contact_email ?></a></p>
            <?php endif; ?>
            <?php if($page->working_hours): ?>
              <h3>Working Hours</h3>
              <p><?= nl2br($page->working_hours) ?></p>
            <?php endif; ?>
          </div>
        </div>
        <?php if($page->show_contact_form): ?>
        <div>
          <div class="card" data-animate>
            <form method="post" class="contact-form">
              <div class="form-row">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?= $sanitizer->entities($input->post->name) ?>">
              </div>
              <div class="form-row">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= $sanitizer->entities($input->post->email) ?>">
              </div>
              <div class="form-row">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?= $sanitizer->entities($input->post->phone) ?>">
              </div>
              <div class="form-row">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="4"><?= $sanitizer->entities($input->post->message) ?></textarea>
              </div>
              <button type="submit" class="btn" name="submit" value="1">Send Message</button>
            </form>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
</div>
