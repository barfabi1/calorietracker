<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);


//     <VirtualHost *:80>
//     DocumentRoot "C:/xampp/htdocs/calorietracker/public"
//     ServerName calorietracker.com
//     <Directory "C:/xampp/htdocs/calorietracker/public">
//         AllowOverride All
//         Order Allow,Deny
//         Allow from All
//     </Directory>
// </VirtualHost>

};
