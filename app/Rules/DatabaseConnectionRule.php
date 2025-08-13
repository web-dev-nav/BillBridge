<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;

class DatabaseConnectionRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            // Get all database form data from request
            $request = request();
            
            $host = $request->input('database.host');
            $port = $request->input('database.port');
            $database = $request->input('database.name');
            $username = $request->input('database.username');
            $password = $request->input('database.password');

            // Only validate when we have all required fields
            if (empty($host) || empty($port) || empty($database) || empty($username)) {
                return; // Let required validation handle missing fields
            }

            Log::info('Testing database connection', [
                'host' => $host,
                'port' => $port,
                'database' => $database,
                'username' => $username
            ]);

            // Test connection with PDO directly
            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
            
            try {
                $pdo = new PDO($dsn, $username, $password, [
                    PDO::ATTR_TIMEOUT => 10,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);

                // Test if we can execute a simple query
                $stmt = $pdo->query('SELECT 1');
                if (!$stmt) {
                    throw new \Exception('Unable to execute test query');
                }

                Log::info('Database connection test successful');
                
            } catch (\PDOException $e) {
                Log::error('Database connection failed', [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode()
                ]);
                
                // Provide user-friendly error messages
                if (str_contains($e->getMessage(), 'Connection refused') || str_contains($e->getMessage(), 'No route to host')) {
                    $fail('Cannot connect to database server. Please check host and port.');
                } elseif (str_contains($e->getMessage(), 'Access denied')) {
                    $fail('Database authentication failed. Please check username and password.');
                } elseif (str_contains($e->getMessage(), 'Unknown database')) {
                    $fail('Database does not exist. Please create the database first.');
                } else {
                    $fail('Database connection failed: ' . $e->getMessage());
                }
                return;
            }

        } catch (\Exception $e) {
            Log::error('Database connection validation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $fail('Database connection test failed. Please check your database settings.');
        }
    }
}