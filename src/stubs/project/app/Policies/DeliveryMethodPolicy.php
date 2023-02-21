<?php

namespace App\Policies;

use App\Models\DeliveryMethod;
use App\Models\User;

class DeliveryMethodPolicy
{
    public function before(User $user, $ability) {
        // @HOOK_POLICY_BEFORE
        if($user->hasRole('Super Admin', 'admin') )
            return true;
    }

    public function view(User $user) {
        // @HOOK_POLICY_VIEW
        return $user->hasPermissionTo('deliveries.view', request()->whereIam());
    }

    public function system(User $user) {
        // @HOOK_POLICY_SYSTEM
        return $user->hasPermissionTo('delivery.system', request()->whereIam());
    }

    public function create(User $user) {
        // @HOOK_POLICY_CREATE
        return $user->hasPermissionTo('delivery.create', request()->whereIam());
    }

    public function update(User $user, DeliveryMethod $chDeliveryMethod) {
        // @HOOK_POLICY_UPDATE
        if( !$user->hasPermissionTo('delivery.update', request()->whereIam()) )
            return false;
        return true;
    }

    public function delete(User $user, DeliveryMethod $chDeliveryMethod) {
        // @HOOK_POLICY_DELETE
        if( !$user->hasPermissionTo('delivery.delete', request()->whereIam()) )
            return false;
        return true;
    }

    // @HOOK_POLICY_END


}
