<?php
    namespace Marinar\Deliveries\Database\Seeders;

    use App\Models\DeliveryMethod;
    use Illuminate\Database\Seeder;
    use Marinar\Deliveries\MarinarDeliveries;
    use Spatie\Permission\Models\Permission;

    class MarinarDeliveriesRemoveSeeder extends Seeder {

        use \Marinar\Marinar\Traits\MarinarSeedersTrait;

        public static function configure() {
            static::$packageName = 'marinar_deliveries';
            static::$packageDir = MarinarDeliveries::getPackageMainDir();
        }

        public function run() {
            if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;

            $this->autoRemove();

            $this->refComponents->info("Done!");
        }

        public function clearMe() {
            $this->refComponents->task("Clear DB", function() {
                foreach(DeliveryMethod::get() as $deliveryMethod) {
                    $deliveryMethod->delete();
                }
                Permission::whereIn('name', [
                    'deliveries.view',
                    'delivery.system',
                    'delivery.create',
                    'delivery.update',
                    'delivery.delete',
                ])
                ->where('guard_name', 'admin')
                ->delete();
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
                return true;
            });
        }
    }
