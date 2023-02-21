<?php

use App\Models\DeliveryMethod;
use App\Policies\DeliveryMethodPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

Route::model('chDeliveryMethod', DeliveryMethod::class);
Gate::policy(DeliveryMethod::class, DeliveryMethodPolicy::class);

