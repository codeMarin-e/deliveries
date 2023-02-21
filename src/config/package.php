<?php
	return [
		'install' => [
            'php artisan db:seed --class="\Marinar\Deliveries\Database\Seeders\MarinarDeliveriesInstallSeeder"',
		],
		'remove' => [
            'php artisan db:seed --class="\Marinar\Deliveries\Database\Seeders\MarinarDeliveriesRemoveSeeder"',
        ]
	];
