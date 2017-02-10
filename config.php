<?php

require("vendor/autoload.php");

//optionally load env vars from a .env file for local dev
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

return array(
  'STRIPE_SECRET_KEY' => getenv("STRIPE_SECRET_KEY"),
  'STRIPE_PUBLISHABLE_KEY' => getenv("STRIPE_PUBLISHABLE_KEY"),
  'SENDGRID_API_KEY' => getenv("SENDGRID_API_KEY"),
  'MAILCHIMP_API_KEY' => getenv("MAILCHIMP_API_KEY"),
  'MAILCHIMP_LIST_ID' => 'a9e6859009',
  'FROM_EMAIL_ADDRESS' => 'info@legacyclub.ca',
  'FROM_EMAIL_NAME' => 'Legacy Club',
  'ADMIN_EMAIL' => 'info@legacyclub.ca'
);
