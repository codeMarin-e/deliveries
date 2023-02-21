<?php
namespace Database\Seeders\Packages\Deliveries;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MarinarDeliveriesSeeder extends Seeder {

    public function run() {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::upsert([
            ['guard_name' => 'admin', 'name' => 'deliveries.view'],
            ['guard_name' => 'admin', 'name' => 'delivery.system'],
            ['guard_name' => 'admin', 'name' => 'delivery.create'],
            ['guard_name' => 'admin', 'name' => 'delivery.update'],
            ['guard_name' => 'admin', 'name' => 'delivery.delete'],
        ], ['guard_name','name']);
    }
}
