<?php
/**
 * Plugin Name: Local Mail Logger
 * Description: Captures wp_mail() calls and saves them to a log file for local development.
 *              Remove this in production — use a real SMTP plugin instead.
 */

// Only active on local dev (no real SMTP available)
if ( defined( 'WP_ENVIRONMENT_TYPE' ) && WP_ENVIRONMENT_TYPE !== 'local' ) {
    return;
}

add_filter( 'pre_wp_mail', function ( $null, $atts ) {
    $log_dir  = WP_CONTENT_DIR . '/mail-log';
    if ( ! is_dir( $log_dir ) ) {
        mkdir( $log_dir, 0755, true );
    }

    $timestamp = date( 'Y-m-d_H-i-s' );
    $filename  = $log_dir . '/mail_' . $timestamp . '_' . uniqid() . '.txt';

    $entry  = "=== Email Captured: $timestamp ===\n";
    $entry .= "To:      " . ( is_array( $atts['to'] ) ? implode( ', ', $atts['to'] ) : $atts['to'] ) . "\n";
    $entry .= "Subject: " . $atts['subject'] . "\n";
    $entry .= "Headers: " . ( is_array( $atts['headers'] ) ? implode( "\n         ", $atts['headers'] ) : $atts['headers'] ) . "\n";
    $entry .= "---\n";
    $entry .= $atts['message'] . "\n";
    $entry .= "===\n";

    file_put_contents( $filename, $entry );

    // Return true to tell WordPress the mail was "sent" successfully
    return true;
}, 10, 2 );
