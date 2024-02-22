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
        [ 'key' => 'last_car_agent_assigned', 'value' => '0', 'description' => 'Agent id last assigned in roundrobin for car' ],
        [ 'key' => 'last_health_agent_assigned', 'value' => '0', 'description' => 'Agent id last assigned in roundrobin for health' ],
        [ 'key' => 'last_travel_agent_assigned', 'value' => '0', 'description' => 'Agent id last assigned in roundrobin for travel' ],
        [ 'key' => 'last_home_agent_assigned', 'value' => '0', 'description' => 'Agent id last assigned in roundrobin for home' ],
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
