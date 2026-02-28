<?php
/**
 * Template Name: Contact
 */
get_header();

$sent    = false;
$errors  = [];

if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['tb_contact_nonce'] ) ) {
    if ( ! wp_verify_nonce( $_POST['tb_contact_nonce'], 'tb_contact_form' ) ) {
        $errors[] = 'Security check failed. Please try again.';
    } else {
        $name    = sanitize_text_field( $_POST['tb_name'] ?? '' );
        $email   = sanitize_email( $_POST['tb_email'] ?? '' );
        $subject = sanitize_text_field( $_POST['tb_subject'] ?? '' );
        $message = sanitize_textarea_field( $_POST['tb_message'] ?? '' );

        if ( ! $name )    $errors[] = 'Name is required.';
        if ( ! $email )   $errors[] = 'A valid email is required.';
        if ( ! $message ) $errors[] = 'Message is required.';

        if ( empty( $errors ) ) {
            $to      = 'basicfirstname123@gmail.com';
            $subj    = '[Trending Brain] ' . ( $subject ? $subject : 'Contact Form' );
            $body    = "Name: $name\nEmail: $email\n\n$message";
            $headers = [
                'From: Trending Brain <noreply@trendingbrain.com>',
                'Reply-To: ' . $name . ' <' . $email . '>',
                'Content-Type: text/plain; charset=UTF-8',
            ];

            $sent = wp_mail( $to, $subj, $body, $headers );

            if ( ! $sent ) {
                $errors[] = 'Failed to send. Please try again later.';
            }
        }
    }
}
?>

<article class="static-page">
    <header class="page-header">
        <h1>Contact</h1>
        <p class="page-subtitle">Have a question, story idea, or just want to say hello? We'd love to hear from you.</p>
    </header>

    <div class="page-content">

        <?php if ( $sent ) : ?>
        <div class="contact-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h2>Message sent</h2>
            <p>Thanks for reaching out. We'll get back to you soon.</p>
        </div>

        <?php else : ?>

        <?php if ( $errors ) : ?>
        <div class="contact-errors">
            <?php foreach ( $errors as $err ) : ?>
                <p><?php echo esc_html( $err ); ?></p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form class="contact-form" method="post">
            <?php wp_nonce_field( 'tb_contact_form', 'tb_contact_nonce' ); ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="tb_name">Name</label>
                    <input type="text" id="tb_name" name="tb_name" placeholder="Your name" required
                           value="<?php echo esc_attr( $_POST['tb_name'] ?? '' ); ?>" />
                </div>
                <div class="form-group">
                    <label for="tb_email">Email</label>
                    <input type="email" id="tb_email" name="tb_email" placeholder="you@example.com" required
                           value="<?php echo esc_attr( $_POST['tb_email'] ?? '' ); ?>" />
                </div>
            </div>

            <div class="form-group">
                <label for="tb_subject">Subject <span class="optional">(optional)</span></label>
                <input type="text" id="tb_subject" name="tb_subject" placeholder="What's this about?"
                       value="<?php echo esc_attr( $_POST['tb_subject'] ?? '' ); ?>" />
            </div>

            <div class="form-group">
                <label for="tb_message">Message</label>
                <textarea id="tb_message" name="tb_message" rows="6" placeholder="Your message..." required><?php echo esc_textarea( $_POST['tb_message'] ?? '' ); ?></textarea>
            </div>

            <button type="submit" class="form-submit">
                Send Message
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </button>
        </form>
        <?php endif; ?>

    </div>
</article>

<?php get_footer(); ?>
