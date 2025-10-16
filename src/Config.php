<?php
namespace App;

final class Config
{
    public const DB_HOST = '127.0.0.1';
    public const DB_PORT = 3306;
    public const DB_NAME = 'pms';
    public const DB_USER = 'root';
    public const DB_PASS = '';

    // Simple API token for demo (do NOT use in production)
    public const API_TOKEN = 'replace-with-secure-token';
}
