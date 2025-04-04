<?php

require("vendor/autoload.php");

//optionally load env vars from a .env file for local dev
if (!getenv("STRIPE_SECRET_KEY")) {
  $dotenv = new Dotenv\Dotenv(__DIR__);
  $dotenv->load();
}

return array(
  'STRIPE_SECRET_KEY' => getenv("STRIPE_SECRET_KEY"),
  'STRIPE_PUBLISHABLE_KEY' => getenv("STRIPE_PUBLISHABLE_KEY"),
  'SENDGRID_API_KEY' => getenv("SENDGRID_API_KEY"),
  'MAILCHIMP_API_KEY' => getenv("MAILCHIMP_API_KEY"),
  'MAILCHIMP_LIST_ID' => 'a9e6859009',
  'FROM_EMAIL_ADDRESS' => 'info@legacyclub.ca',
  'FROM_EMAIL_NAME' => 'Fighting Irish Legacy Club',
  'ADMIN_EMAIL' => getenv("ADMIN_EMAIL"),
  'ADMIN_CC_EMAIL' => getenv("ADMIN_CC_EMAIL"),
  'DB_HOST' => getenv("DB_HOST"),
  'DB_NAME' => getenv("DB_NAME"),
  'DB_USER' => getenv("DB_USER"),
  'DB_PASS' => getenv("DB_PASS")
);
