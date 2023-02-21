<?php
    namespace App\Models;

    use App\Interfaces\DeliveryMethodI;

    class NormalDeliveryMethod implements DeliveryMethodI {

        // @HOOK_TRAITS

        public static function getName($replace = [], $locale = null) {
            return trans('admin/deliveries/delivery.type.normal', $replace, $locale);
        }

        public function init($order = null) {

        }

        public function process($order = null) {

        }

        public static function getOverviewTPLName() {
            return null;
        }


    }
