<?php

/**
 * Vercel PHP Serverless Entry Point
 * 
 * This file forwards requests from Vercel Serverless Functions to Laravel's
 * public/index.php entry point.
 */

// Override storage path for Vercel's read-only filesystem
$_ENV['APP_RUNNING_IN_CONSOLE'] = false;
$storagePath = '/tmp/storage';

if (!is_dir($storagePath)) {
    mkdir($storagePath, 0777, true);
    mkdir($storagePath . '/framework/cache', 0777, true);
    mkdir($storagePath . '/framework/sessions', 0777, true);
    mkdir($storagePath . '/framework/views', 0777, true);
    mkdir($storagePath . '/logs', 0777, true);
}

// Forward to typical Laravel index.php
require __DIR__ . '/../public/index.php';
