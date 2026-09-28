<?php
// Copy this file to config/mail.php and fill in your credentials.
// config/mail.php is gitignored — never commit real credentials.
return [
    'host'       => 'smtp.gmail.com',
    'port'       => 587,
    'encryption' => 'tls',
    'username'   => 'your@gmail.com',
    'password'   => 'your-app-password',   // Google: myaccount.google.com → Security → App passwords
    'from_email' => 'your@gmail.com',
    'from_name'  => '[pantunes.dev] — Contact form',
    'to_email'   => 'your@gmail.com',
    'to_name'    => 'Paulo Antunes',
];
