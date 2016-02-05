<?php

namespace syntaxthemes\restaurant;

/**
 * Description of class-email-notification
 *
 * @author Ryan Haworth
 */
class syn_email {

    protected $_config;
    protected $_to;
    protected $_cc;
    protected $_subject;
    protected $_message;
    protected $_headers;
    protected $_attachments;
    protected $_string_attachments;

    public function __construct() {

        global $syn_restaurant_config;

        $this->_config = $syn_restaurant_config;
        $this->_cc = array();

        add_action('phpmailer_init', array($this, 'phpmailer_ext'));
    }
    
    public function email_setup($to, $subject){
        
        $this->_to = $to;
        $this->_subject = $subject;
    }

    public function add_from($name, $email) {

        $this->_headers[] = "From: {$name} <{$email}>";
    }

    public function add_cc($email) {

        $this->_cc[] = $email;
    }

    private function create_cc($cc) {

        if (!empty($cc) && is_array($cc)) {
            foreach ($cc as $email) {
                $this->add_cc($email);
            }
        } else {
            if (!empty($cc)) {
                $cc = preg_replace('/\s+/', '', $cc);
                $cc = explode(';', $cc);

                if (!empty($cc) && is_array($cc)) {
                    foreach ($cc as $email) {
                        $this->add_cc($email);
                    }
                }
            }
        }
    }

    public function add_attachment($filepath) {

        $this->_attachments[] = $filepath;
    }

    public function add_name_attachment($filepath, $name) {

        $this->_attachments[$name] = $filepath;
    }

    public function add_string_name_attachment($content, $name, $encoding, $type) {

        $this->_string_attachments[$name] = array(
            'name' => $name,
            'content' => $content,
            'encoding' => $encoding,
            'type' => $type
        );
    }

    public function send_mail() {

        return wp_mail($this->_to, $this->_subject, $this->_message, $this->_headers);
    }

    public function phpmailer_ext($phpmailer) {

        if (!empty($this->_cc)) {
            foreach ($this->_cc as $cc) {
                $phpmailer->AddCC($cc);
            }
        }

        if (!empty($this->_attachments)) {
            foreach ($this->_attachments as $attachment) {
                if (!empty($this->_attachments) && is_array($this->_attachments)) {

                    // check if $attachments is associative array or not 
                    $is_assoc_array = array_keys($this->_attachments) !== range(0, count($this->_attachments) - 1);

                    foreach ($this->_attachments as $name => $attachment) {
                        try {
                            ( $is_assoc_array ) ? $phpmailer->AddAttachment($attachment, $name) : $phpmailer->AddAttachment($attachment);
                        } catch (phpmailerException $e) {
                            continue;
                        }
                    }
                }
            }
        }

        if (!empty($this->_string_attachments)) {
            foreach ($this->_string_attachments as $attachment) {
                if (!empty($this->_string_attachments) && is_array($this->_string_attachments)) {

                    // check if $attachments is associative array or not 
                    $is_assoc_array = array_keys($this->_string_attachments) !== range(0, count($this->_string_attachments) - 1);

                    foreach ($this->_string_attachments as $name => $attachment) {
                        try {
                            $content = $attachment['content'];
                            $encoding = $attachment['encoding'];
                            $type = $attachment['type'];
                            $phpmailer->AddStringAttachment($content, $name);
                        } catch (phpmailerException $e) {
                            continue;
                        }
                    }
                }
            }
        }
    }

}
