<x-admin.main>
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route("{$route_namespace}.home")}}"><i class="fa fa-home"></i></a></li>
            <li class="breadcrumb-item active">@lang('admin/deliveries/deliveries.deliveries')</li>
        </ol>

        @can('create', App\Models\DeliveryMethod::class)
            <a href="{{ route("{$route_namespace}.deliveries.create") }}"
               class="btn btn-sm btn-primary h5"
               title="create">
                <i class="fa fa-plus mr-1"></i>@lang('admin/deliveries/deliveries.create')
            </a>
        @endcan

        {{-- @HOOK_AFTER_CREATE --}}

        <x-admin.box_messages />

        <div class="table-responsive rounded ">
            <table class="table table-sm">
                <thead class="thead-light">
                <tr class="">
                    <th scope="col" class="text-center">@lang('admin/deliveries/deliveries.id')</th>
                    {{-- @HOOK_AFTER_ID_TH --}}

                    <th scope="col" class="w-75">@lang('admin/deliveries/deliveries.name')</th>
                    {{-- @HOOK_AFTER_NAME_TH --}}

                    <th scope="col" class="text-center">@lang('admin/deliveries/deliveries.edit')</th>
                    {{-- @HOOK_AFTER_EDIT_TH --}}

                    <th colspan="2" scope="col" class="text-center">@lang('admin/deliveries/deliveries.move_th')</th>
                    {{-- @HOOK_AFTER_MOVE_TH --}}

                    <th scope="col" class="text-center">@lang('admin/deliveries/deliveries.remove')</th>
                    {{-- @HOOK_AFTER_REMOVE_TH --}}
                </tr>
                </thead>
                <tbody>
                @forelse($deliveryMethods as $deliveryMethod)
                    @php
                        $deliveryEditUri = route("{$route_namespace}.deliveries.edit", $deliveryMethod->id);
                        $canUpdate = $authUser->can('update', $deliveryMethod);
                    @endphp
                    @if($loop->first)
                        @php $prevDelivery = $deliveryMethod->getPrevious(); @endphp
                    @endif
                    @if($loop->last)
                        @php $nextDelivery = $deliveryMethod->getNext(); @endphp
                    @endif
                    <tr data-id="{{$deliveryMethod->id}}"
                        data-parent="{{$deliveryMethod->parent_id}}"
                        data-show="1">
                        <td scope="row" class="text-center align-middle"><a href="{{ $deliveryEditUri }}"
                                                                            title="@lang('admin/deliveries/deliveries.edit')"
                            >{{ $deliveryMethod->id }}</a></td>
                        {{-- @HOOK_AFTER_ID --}}

                        {{--    REAL NAME    --}}
                        <td class="w-75 align-middle">
                            <a href="{{ $deliveryEditUri }}"
                               title="@lang('admin/deliveries/deliveries.edit')"
                               class="@if($deliveryMethod->active) @else text-danger @endif"
                            >{{ \Illuminate\Support\Str::words($deliveryMethod->aVar('name'), 12,'....') }}</a>
                            @if($deliveryMethod->default)<span class="badge badge-success">@lang('admin/deliveries/deliveries.default')</span>@endif
                            @if($deliveryMethod->test_mode)<span class="badge badge-warning">@lang('admin/deliveries/deliveries.test_mode')</span>@endif
                        </td>
                        {{-- @HOOK_AFTER_NAME --}}

                        {{--    EDIT    --}}
                        <td class="text-center">
                            <a class="btn btn-link text-success"
                               href="{{ $deliveryEditUri }}"
                               title="@lang('admin/deliveries/deliveries.edit')"><i class="fa fa-edit"></i></a></td>
                        {{-- @HOOK_AFTER_EDIT--}}

                        {{--    MOVE DOWN    --}}
                        <td class="text-center">
                            @if($canUpdate && (!$loop->last || $nextDelivery))
                                <a class="btn btn-link"
                                   href="{{route("{$route_namespace}.deliveries.move", [$deliveryMethod, 'down'])}}"
                                   title="@lang('admin/deliveries/deliveries.move_down')"><i class="fa fa-arrow-down"></i></a>
                            @endif
                        </td>

                        {{--    MOVE UP   --}}
                        <td class="text-center">
                            @if($canUpdate && (!$loop->first || $prevDelivery))
                                <a class="btn btn-link"
                                   href="{{route("{$route_namespace}.deliveries.move", [$deliveryMethod,'up'])}}"
                                   title="@lang('admin/deliveries/deliveries.move_up')"><i class="fa fa-arrow-up"></i></a>
                            @endif
                        </td>
                        {{-- @HOOK_AFTER_MOVE--}}

                        {{--    DELETE    --}}
                        <td class="text-center">
                            @can('delete', $deliveryMethod)
                                <form action="{{ route("{$route_namespace}.deliveries.destroy", $deliveryMethod->id) }}"
                                      method="POST"
                                      id="delete[{{$deliveryMethod->id}}]">
                                    @csrf
                                    @method('DELETE')
                                    @php
                                        $redirectTo = (!$deliveryMethods->onFirstPage() && $deliveryMethods->count() == 1)?
                                                $deliveryMethods->previousPageUrl() :
                                                url()->full();
                                    @endphp
                                    <button class="btn btn-link text-danger"
                                            title="@lang('admin/deliveries/deliveries.remove')"
                                            onclick="if(confirm('@lang("admin/deliveries/deliveries.remove_ask")')) document.querySelector( '#delete\\[{{$deliveryMethod->id}}\\] ').submit() "
                                            type="button"><i class="fa fa-trash"></i></button>
                                </form>
                            @endcan
                        </td>
                        {{-- @HOOK_AFTER_REMOVE --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%">@lang('admin/deliveries/deliveries.no_deliveries')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{$deliveryMethods->links('admin.paging')}}

        </div>
    </div>
</x-admin.main>
