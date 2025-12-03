<?php

namespace Database\Seeders;

use App\Models\Authorization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolerSeeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permessions = [];
        $permissionsConfig = config('authorization.permissions');
        if (is_array($permissionsConfig)) {
            foreach ($permissionsConfig as $permession => $value) {
                $permessions[] = $permession;
            }
        }
        Authorization::create([
            'role' => 'Manager',
            'permissions' => json_encode($permessions),
        ]);

    }
}
