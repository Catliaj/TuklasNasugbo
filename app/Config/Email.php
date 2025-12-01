<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    // Gmail SMTP configuration (override via .env)
    public string $fromEmail  = 'tuklasnasugbu@gmail.com';
    public string $fromName   = 'Tuklas Nasugbu';
    public string $recipients = '';

    /**
     * The "user agent"
     */
    public string $userAgent = 'CodeIgniter';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     */
    public string $protocol = 'smtp';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Hostname
     */
    public string $SMTPHost = 'smtp.gmail.com';

    /**
     * SMTP Username
     */
    public string $SMTPUser = '';// set in .env email.SMTPUser

    /**
     * SMTP Password
     */
    public string $SMTPPass = '';// set in .env email.SMTPPass (App Password)

    /**
     * SMTP Port
     */
    public int $SMTPPort = 587; // TLS port

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 10;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     *
     * @var string '', 'tls' or 'ssl'. 'tls' will issue a STARTTLS command
     *             to the server. 'ssl' means implicit SSL. Connection on port
     *             465 should set this to ''.
     */
    public string $SMTPCrypto = 'tls';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     */
    public string $mailType = 'html';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = true;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;

    public function __construct()
    {
        parent::__construct();

        // Load from environment with sensible defaults
        $this->fromEmail   = env('email.fromEmail', $this->fromEmail);
        $this->fromName    = env('email.fromName', $this->fromName);

        $this->protocol    = env('email.protocol', $this->protocol);
        $this->SMTPHost    = env('email.SMTPHost', $this->SMTPHost);
        $this->SMTPUser    = env('email.SMTPUser', $this->SMTPUser);
        $this->SMTPPass    = env('email.SMTPPass', $this->SMTPPass);
        $this->SMTPPort    = (int) env('email.SMTPPort', (string) $this->SMTPPort);
        $this->SMTPCrypto  = env('email.SMTPCrypto', $this->SMTPCrypto);

        $this->mailType    = env('email.mailType', $this->mailType);
        $this->validate    = (bool) env('email.validate', $this->validate ? '1' : '0');
    }
}
