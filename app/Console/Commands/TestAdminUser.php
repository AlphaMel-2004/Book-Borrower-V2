<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestAdminUser extends Command
{
    protected $signature = 'test:admin-user';
    protected $description = 'Test admin user functionality';

    public function handle()
    {
        $admin = User::where('email', 'admin@example.com')->first();
        
        if (!$admin) {
            $this->error('Admin user not found!');
            return 1;
        }

        $this->info("Admin User Test Results:");
        $this->info("Email: {$admin->email}");
        $this->info("Name: {$admin->name}");
        
        // Test different ways to access admin status
        $this->info("Raw is_admin attribute: " . ($admin->getAttributes()['is_admin'] ? 'true' : 'false'));
        $this->info("isAdmin accessor: " . ($admin->isAdmin ? 'true' : 'false'));
        $this->info("isAdmin() method: " . ($admin->isAdmin() ? 'true' : 'false'));
        
        // Test casting
        $this->info("Casted value: " . (($admin->is_admin === true) ? 'true' : 'false'));
        
        if ($admin->isAdmin) {
            $this->info("✓ Admin user is working correctly!");
            return 0;
        } else {
            $this->error("✗ Admin user is not working correctly!");
            return 1;
        }
    }
}
