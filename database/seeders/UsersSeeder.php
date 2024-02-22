<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserSetting;

class UsersSeeder extends Seeder
{
    protected $users = [
        [
            "id" =>  100,
            "name" =>  "vincent admin",
            "email" =>  "vmbcarabbacan@gmail.com",
            "password" =>  "M0a3r1k5",
            "setting" =>  [
                "role_id" =>  1,
                "team_leader_id" =>  0,
                "underwriter_id" =>  0,
                "is_underwriter" =>  0,
                "is_round_robin" =>  0,
                "agent_type" =>  0,
                "renewal_deals" =>  0,
                "insurance_type" =>  1,
                "status" =>  1
            ]
        ],
        [
            "id" =>  2,
            "name" =>  "Martin underwriter",
            "email" =>  "martin@yopmail.com",
            "password" =>  "M0a3r1k5",
            "setting" =>  [
                "role_id" =>  3,
                "team_leader_id" =>  0,
                "underwriter_id" =>  0,
                "is_underwriter" =>  1,
                "is_round_robin" =>  1,
                "agent_type" =>  0,
                "renewal_deals" =>  0,
                "insurance_type" =>  1,
                "status" =>  1
            ]
        ],
        [
            "id" =>  3,
            "name" =>  "Joseph leader",
            "email" =>  "joseph@yopmail.com",
            "password" =>  "M0a3r1k5",
            "setting" =>  [
                "role_id" =>  8,
                "team_leader_id" =>  0,
                "underwriter_id" =>  0,
                "is_underwriter" =>  2,
                "is_round_robin" =>  1,
                "agent_type" =>  1,
                "renewal_deals" =>  1,
                "insurance_type" =>  1,
                "status" =>  1
            ]
        ],
        [
            "id" =>  4,
            "name" =>  "Mikael",
            "email" =>  "mikael@yopmail.com",
            "password" =>  "M0a3r1k5",
            "setting" =>  [
                "role_id" =>  2,
                "team_leader_id" =>  3,
                "underwriter_id" =>  2,
                "is_underwriter" =>  0,
                "is_round_robin" =>  1,
                "agent_type" =>  1,
                "renewal_deals" =>  1,
                "insurance_type" =>  1,
                "status" =>  1
            ]
        ],
        [
            "id" =>  5,
            "name" =>  "Ronny",
            "email" =>  "ronny@yopmail.com",
            "password" =>  "M0a3r1k5",
            "setting" =>  [
                "role_id" =>  2,
                "team_leader_id" =>  3,
                "underwriter_id" =>  2,
                "is_underwriter" =>  0,
                "is_round_robin" =>  1,
                "agent_type" =>  1,
                "renewal_deals" =>  1,
                "insurance_type" =>  1,
                "status" =>  1
            ]
        ],
        [
            "id" =>  6,
            "name" =>  "leah",
            "email" =>  "leah@yopmail.com",
            "password" =>  "M0a3r1k5",
            "setting" =>  [
                "role_id" =>  8,
                "team_leader_id" =>  0,
                "underwriter_id" =>  0,
                "is_underwriter" =>  2,
                "is_round_robin" =>  1,
                "agent_type" =>  1,
                "renewal_deals" =>  1,
                "insurance_type" =>  1,
                "status" =>  1
            ]
        ],
        [
            "id" =>  7,
            "name" =>  "Tony",
            "email" =>  "tony@yopmail.com",
            "password" =>  "M0a3r1k5",
            "setting" =>  [
                "role_id" =>  2,
                "team_leader_id" =>  6,
                "underwriter_id" =>  2,
                "is_underwriter" =>  0,
                "is_round_robin" =>  1,
                "agent_type" =>  1,
                "renewal_deals" =>  1,
                "insurance_type" =>  1,
                "status" =>  1
            ]
        ],
        [
            "id" =>  8,
            "name" =>  "Tim",
            "email" =>  "tim@yopmail.com",
            "password" =>  "M0a3r1k5",
            "setting" =>  [
                "role_id" =>  2,
                "team_leader_id" =>  6,
                "underwriter_id" =>  2,
                "is_underwriter" =>  0,
                "is_round_robin" =>  1,
                "agent_type" =>  1,
                "renewal_deals" =>  1,
                "insurance_type" =>  1,
                "status" =>  1
            ]
        ],
        [
            "id" =>  9,
            "name" =>  "Zack",
            "email" =>  "zack@yopmail.com",
            "password" =>  "M0a3r1k5",
            "setting" =>  [
                "role_id" =>  6,
                "team_leader_id" =>  0,
                "underwriter_id" =>  2,
                "is_underwriter" =>  0,
                "is_round_robin" =>  1,
                "agent_type" =>  1,
                "renewal_deals" =>  1,
                "insurance_type" =>  1,
                "status" =>  1
            ]
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();
        UserSetting::truncate();

        foreach($this->users as $user)
            $this->insert($user);
    }

    protected function insert($data) {
        $setting = $data['setting'];
        unset($data['setting']);

        $user = new User();
        $user->fill($data);
        $user->save();
        $user->userSetting()->create($setting);
    }

    
}
