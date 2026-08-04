<?php

declare(strict_types=1);

return [
    '/' => ['App\\Controllers\\HomeController', 'index'],
    '/login' => ['App\\Controllers\\Auth\\LoginController', 'index'],
    '/register' => ['App\\Controllers\\Auth\\RegisterController', 'index'],
    '/logout' => ['App\\Controllers\\Auth\\LogoutController', 'logout'],
    '/forgot-password' => ['App\\Controllers\\Auth\\ForgotPasswordController', 'index'],
    '/reset-password' => ['App\\Controllers\\Auth\\ResetPasswordController', 'index'],
    '/verify-email' => ['App\\Controllers\\Auth\\VerificationController', 'verifyEmail'],
    '/dashboard' => ['App\\Controllers\\DashboardController', 'index'],
    '/health' => ['App\\Controllers\\HealthController', 'index'],
    '/ai-assistant' => ['App\\Controllers\\AssistantController', 'index'],
    '/profile' => ['App\\Controllers\\User\\ProfileController', 'index'],
    '/profile/save' => ['App\\Controllers\\User\\ProfileController', 'save'],
    '/profile/upload-photo' => ['App\\Controllers\\User\\ProfileController', 'uploadPhoto'],
    '/404' => ['App\\Controllers\\ErrorController', 'notFound'],
];
