<?php

return array(

    // Enable test mode (not require HTTPS)
    'test-mode'       => false,

    // Secret Key from Stripe.com Dashboard
    'secret-key'      => 'sk_test_Odk0oiVqpCqNtoactDrJpAEj',

    // Publishable Key from Stripe.com Dashboard
    'publishable-key' => 'pk_test_yM4AauPeQGQLXAJkdlB8Ypmu',

    'email-password' => 'shashank@007',

    // Where to send upon successful donation (must include http://)
    'thank-you'       => 'https://legacy-club.herokuapp.com/thankyou.html',

    // Who the email will be from.
    'email-from'      => 'info@legacyclub.ca',

    // Who should be BCC'd on this email. Probably an administrative email.
    'email-bcc'       => 'hsanvaria@ccistudios.com',

    // Subject of email receipt
    'email-subject'   => 'Thank you for your donation!',

    // Email message. %name% is the donor's name. %amount% is the donation amount
    'email-message'   => "Dear %name%,\n\nThank you for your donation of %amount%. We rely on the financial support from people like you to keep our cause alive. Below is your donation receipt to keep for your records."

);
