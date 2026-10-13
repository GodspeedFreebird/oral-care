<?php
return [
    'host'       => getenv('SMTP_HOST') ?: 'smtp.example.com',
    'port'       => getenv('SMTP_PORT') ? (int)getenv('SMTP_PORT') : 587,
    'username'   => getenv('SMTP_USER') ?: 'placeholder@example.com',
    'password'   => getenv('SMTP_PASS') ?: 'placeholder_password',
    'encryption' => getenv('SMTP_ENCRYPTION') ?: 'tls', // 'tls' or 'ssl'
    'from_email' => getenv('SMTP_FROM_EMAIL') ?: 'dental@oauthc.edu.ng',
    'from_name'  => getenv('SMTP_FROM_NAME') ?: 'OAUTHC Dental Centre'
];
