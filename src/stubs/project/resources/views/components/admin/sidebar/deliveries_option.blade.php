@if($authUser->can('view', \App\Models\DeliveryMethod::class))
    {{--   PAYMENT METHODS --}}
    <li class="nav-item @if(request()->route()->named("{$whereIam}.deliveries.*")) active @endif">
        <a class="nav-link " href="{{route("{$whereIam}.deliveries.index")}}">
            <i class="fa fa-fw fa-truck mr-1"></i>
            <span>@lang("admin/deliveries/deliveries.sidebar")</span>
        </a>
    </li>
@endif
