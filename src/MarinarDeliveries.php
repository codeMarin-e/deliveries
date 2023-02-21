<?php
    namespace Marinar\Deliveries;

    use Marinar\Deliveries\Database\Seeders\MarinarDeliveriesInstallSeeder;

    class MarinarDeliveries {

        public static function getPackageMainDir() {
            return __DIR__;
        }

        public static function injects() {
            return MarinarDeliveriesInstallSeeder::class;
        }
    }
