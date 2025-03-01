<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestApiEndpoints extends Command
{
    protected $signature = 'test:api-endpoints';
    protected $description = 'Test API endpoints for checking email and ID existence';

    public function handle()
    {
        $this->info('Testing API endpoints...');

        // Test check-email endpoint
        $emailResponse = Http::post(config('app.url') . '/api/check-email', [
            'email' => 'test@example.com',
            'userType' => 'student', // or 'instructor'
        ]);

        if ($emailResponse->successful()) {
            $this->info('check-email endpoint is working: ' . $emailResponse);
        } else {
            $this->error('check-email endpoint failed with status: ' . $emailResponse);
        }

        // Test check-id endpoint
        $idResponse = Http::post(config('app.url') . '/api/check-id', [
            'id' => '12345',
            'userType' => 'student', // or 'instructor'
        ]);

        if ($idResponse->successful()) {
            $this->info('check-id endpoint is working: ' . $idResponse->body());
        } else {
            $this->error('check-id endpoint failed with status: ' . $idResponse->status());
        }
    }
} 