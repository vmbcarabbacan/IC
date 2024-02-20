<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppConfigurationSeeder extends Seeder
{
    protected $configurations = [
        [ 'key' => 'api_url', 'value' => 'localhost:8080', 'description' => 'URI of the application' ],
        [ 'key' => 'login_attempts', 'value' => '5', 'description' => 'User failed attempts' ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->dbTable()->truncate();
        $this->dbTable()->insert($this->configurations);
    }

    protected function dbTable() {
        return DB::table('app_configurations');
    }

    
}
