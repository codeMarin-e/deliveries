<?php
    namespace Marinar\Deliveries\Database\Seeders;

    use Illuminate\Database\Seeder;
    use Marinar\Deliveries\MarinarDeliveries;

    class MarinarDeliveriesInstallSeeder extends Seeder {

        use \Marinar\Marinar\Traits\MarinarSeedersTrait;

        public static function configure() {
            static::$packageName = 'marinar_deliveries';
            static::$packageDir = MarinarDeliveries::getPackageMainDir();
        }

        public function run() {
            if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;

            $this->autoInstall();

            $this->refComponents->info("Done!");
        }

    }
