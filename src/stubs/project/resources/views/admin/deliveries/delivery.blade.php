@php $inputBag = 'delivery'; @endphp
@pushonce('above_css')
<!-- JQUERY UI -->
<link href="{{ asset('admin/vendor/jquery-ui-1.12.1/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>
@endpushonce

@pushonce('below_js')
<script language="javascript"
        type="text/javascript"
        src="{{ asset('admin/vendor/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
@endpushonce

{{-- JUST FOR EXAMPLE --}}
{{--@pushonce('below_templates')--}}
{{--<div id="js_cod_template">--}}
{{--    <div>Ohaaaa</div>--}}
{{--</div>--}}
{{--@endpushonce--}}

{{-- @HOOK_TYPE_TEMPLATES --}}

@pushonceOnReady('below_js_on_ready')
<script>
    var $js_pm_table = $('#js_pm_table');
    $js_pm_table.sortable({
        opacity: 0.6,
        cursor: 'move',
        containment: "parent",
        items: '.js_pm',
        cancel: 'input, select, textarea, label, span',
    });

    var $typeSelect = $('#{{$inputBag}}\\[type\\]');
    $(document).on('change', '#{{$inputBag}}\\[type\\]', function() {
        var $selected = $typeSelect.find("option:selected").first();

        @can('system', \App\Models\DeliveryMethod::class)
            var $overviewInput = $('#{{$inputBag}}\\[overview\\]');
            //OVERVIEW
            if($overviewInput.length) {
                $overviewInput.val( $selected.attr('data-overview') );
            }
        @endcan

        $(document).trigger('type_template');
    })

    var $typeTemplateCon = $('#js_type_template_con');
    $(document).on('type_template', function() {
        var $typeTemplate = $('#js_' + $.escapeSelector( $typeSelect.val() )  + '_template');
        if($typeTemplate.length) {
            $typeTemplateCon.html( $typeTemplate.html() );
        }
    });
    @isset($chDeliveryMethod)
        $(document).trigger('type_template');
    @else
        $('#{{$inputBag}}\\[type\\]').trigger('change');
    @endisset

</script>
@endpushonceOnReady

@pushonce('below_templates')
@if(isset($chDeliveryMethod) && $authUser->can('delete', $chDeliveryMethod))
    <form action="{{ route("{$route_namespace}.deliveries.destroy", $chDeliveryMethod->id) }}"
          method="POST"
          id="delete[{{$chDeliveryMethod->id}}]">
        @csrf
        @method('DELETE')
    </form>
@endif
@endpushonce

<x-admin.main>
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route("{$route_namespace}.home")}}"><i class="fa fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a
                    href="{{ route("{$route_namespace}.deliveries.index") }}">@lang('admin/deliveries/deliveries.deliveries')</a>
            </li>
            <li class="breadcrumb-item active">@isset($chDeliveryMethod){{ $chDeliveryMethod->aVar('name') }}@else @lang('admin/deliveries/delivery.create') @endisset</li>
        </ol>

        <div class="card">
            <div class="card-body">
                <form
                    action="@isset($chDeliveryMethod){{ route("{$route_namespace}.deliveries.update", [ $chDeliveryMethod->id ]) }}@else{{ route("{$route_namespace}.deliveries.store") }}@endisset"
                    method="POST"
                    autocomplete="off"
                    enctype="multipart/form-data">
                    @csrf
                    @isset($chDeliveryMethod)@method('PATCH')@endisset

                    <x-admin.box_messages />

                    <x-admin.box_errors :inputBag="$inputBag" />
                    {{-- @HOOK_BEGINING --}}

                    @php
                        $sType = old("{$inputBag}.type", (isset($chDeliveryMethod)? $chDeliveryMethod->type : array_key_first(\App\Models\DeliveryMethod::$types)));
                    @endphp
                    <div class="form-group row">
                        <label for="{{$inputBag}}[type]"
                               class="col-lg-1 col-form-label">@lang('admin/deliveries/delivery.types'):</label>
                        <div class="col-lg-4">
                            <select class="form-control @if($errors->$inputBag->has('type')) is-invalid @endif"
                                    id="{{$inputBag}}[type]"
                                    name="{{$inputBag}}[type]">
                                @foreach(\App\Models\DeliveryMethod::$types as $deliveryType => $deliveryClass)
                                    <option
                                        @if($sType == $deliveryType)selected='selected'@endif
                                        data-overview="{{$deliveryClass::getOverviewTPLName()}}"
                                        value="{{$deliveryType}}">{{call_user_func(array($deliveryClass, 'getName'), [])}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- @HOOK_AFTER_TYPE --}}

                    <div class="form-group row">
                        <label for="{{$inputBag}}[name]"
                               class="col-lg-2 col-form-label"
                        >@lang('admin/deliveries/delivery.name'):</label>
                        <div class="col-lg-10">
                            <input type="text"
                                   name="{{$inputBag}}[add][name]"
                                   id="{{$inputBag}}[add][name]"
                                   value="{{ old("{$inputBag}.add.name", (isset($chDeliveryMethod)? $chDeliveryMethod->aVar('name') : '')) }}"
                                   class="form-control @if($errors->$inputBag->has('add.name')) is-invalid @endif"
                            />
                        </div>
                    </div>
                    {{-- @HOOK_AFTER_NAME --}}

                    <div class="form-group row">
                        <label for="{{$inputBag}}[tax]"
                               class="col-lg-2 col-form-label"
                        >@lang('admin/deliveries/delivery.tax'):</label>
                        <div class="col-lg-2">
                            <div class="input-group">
                                <input type="text"
                                       name="{{$inputBag}}[tax]"
                                       id="{{$inputBag}}[tax]"
                                       value="{{ old("{$inputBag}.tax", (isset($chDeliveryMethod)? $chDeliveryMethod->tax : '')) }}"
                                       class="form-control @if($errors->$inputBag->has('tax')) is-invalid @endif"
                                />
                                <div class="input-group-append">
                                    <span class="input-group-text">{{$siteCurrency}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @HOOK_AFTER_TAX --}}

                    @can('system', \App\Models\DeliveryMethod::class)
                        {{-- OVERVIEW --}}
                        <div class="form-group row">
                            <label for="{{$inputBag}}[overview]"
                                   class="col-lg-2 col-form-label"
                            >@lang('admin/deliveries/delivery.overview'):</label>
                            <div class="col-lg-3">
                                <input type="text"
                                       name="{{$inputBag}}[overview]"
                                       id="{{$inputBag}}[overview]"
                                       value="{{ old("{$inputBag}.overview", (isset($chDeliveryMethod)? $chDeliveryMethod->overview : '')) }}"
                                       class="form-control @if($errors->$inputBag->has('overview')) is-invalid @endif"
                                />
                            </div>
                        </div>
                        {{-- @HOOK_AFTER_OVERVIEW --}}
                    @endcan

                    <div class="form-group row">
                        <label for="{{$inputBag}}[add][description]"
                               class="col-lg-2 col-form-label @if($errors->$inputBag->has('add.description')) text-danger @endif"
                        >@lang('admin/deliveries/delivery.description'):</label>
                        <div class="col-lg-10">
                            <x-admin.editor
                                :inputName="$inputBag.'[add][description]'"
                                :otherClasses="[ 'form-controll', ]"
                            >{{old("{$inputBag}.add.description", (isset($chDeliveryMethod)? $chDeliveryMethod->aVar('description') : ''))}}</x-admin.editor>
                        </div>
                    </div>
                    {{-- @HOOK_AFTER_DESCRIPTION --}}

                    <div class="row col-lg-3">
                        <div class="table-responsive rounded ">
                            <table class="table table-sm " id="js_pm_table">
                                <thead class="thead-light">
                                <tr>
                                    <th class="text-center w-10">@lang('admin/deliveries/delivery.pm.use')</th>
                                    <th class="text-center">@lang('admin/deliveries/delivery.pm.name')</th>
                                </tr>
                                </thead>
                                @php
                                    $chDeliveryMethodPMs = [];
                                    if(isset($chDeliveryMethod)) {
                                        $chDeliveryMethodPMs = $chDeliveryMethod->payments()->orderBy('ord')->get()->keyBy('id');
                                    }
                                @endphp
                                @forelse($pms as $pm)
                                    <tr class="js_pm">
                                        <td class="text-center align-middle w-10">
                                            <input type="checkbox"
                                                   value="{{$pm->id}}"
                                                   id="{{$inputBag}}[pm][{{$pm->id}}]"
                                                   name="{{$inputBag}}[pm][{{$pm->id}}]"
                                                   class="form-check @if($errors->$inputBag->has("pm.{$pm->id}"))is-invalid @endif"
                                                   @if(old("{$inputBag}.pm.{$pm->id}") || (is_null(old("{$inputBag}.pm.{$pm->id}")) && isset($chDeliveryMethodPMs[$pm->id]))) checked="checked" @endif
                                            />
                                        </td>
                                        <td class="text-center align-middle @if(!$pm->active) text-danger @endif">{{$pm->aVar('name')}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">@lang('admin/deliveries/delivery.pm.no_payments')</td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                    {{-- @HOOK_AFTER_PM --}}

                    <div id="js_type_template_con"></div>

                    {{-- @HOOK_AFTER_END --}}

                    <div class="form-group row form-check">
                        <div class="col-lg-6">
                            <input type="checkbox"
                                   value="1"
                                   id="{{$inputBag}}[default2]"
                                   name="{{$inputBag}}[default2]"
                                   class="form-check-input @if($errors->$inputBag->has('default2'))is-invalid @endif"
                                   @if(old("{$inputBag}.default2") || (is_null(old("{$inputBag}.default2")) && isset($chDeliveryMethod) && $chDeliveryMethod->default ))checked="checked"@endif
                            />
                            <label class="form-check-label"
                                   for="{{$inputBag}}[default2]">@lang('admin/deliveries/delivery.default')</label>
                        </div>
                    </div>
                    {{-- @HOOK_AFTER_DEFAULT --}}

                    <div class="form-group row form-check">
                        <div class="col-lg-6">
                            <input type="checkbox"
                                   value="1"
                                   id="{{$inputBag}}[test_mode]"
                                   name="{{$inputBag}}[test_mode]"
                                   class="form-check-input @if($errors->$inputBag->has('test_mode'))is-invalid @endif"
                                   @if(old("{$inputBag}.test_mode") || (is_null(old("{$inputBag}.test_mode")) && isset($chDeliveryMethod) && $chDeliveryMethod->test_mode ))checked="checked"@endif
                            />
                            <label class="form-check-label"
                                   for="{{$inputBag}}[test_mode]">@lang('admin/deliveries/delivery.test_mode')</label>
                        </div>
                    </div>
                    {{-- @HOOK_AFTER_TEST_MODE --}}

                    <div class="form-group row form-check">
                        <div class="col-lg-6">
                            <input type="checkbox"
                                   value="1"
                                   id="{{$inputBag}}[active]"
                                   name="{{$inputBag}}[active]"
                                   class="form-check-input @if($errors->$inputBag->has('active'))is-invalid @endif"
                                   @if(old("{$inputBag}.active") || (is_null(old("{$inputBag}.active")) && isset($chDeliveryMethod) && $chDeliveryMethod->active ))checked="checked"@endif
                            />
                            <label class="form-check-label"
                                   for="{{$inputBag}}[active]">@lang('admin/deliveries/delivery.active')</label>
                        </div>
                    </div>
                    {{-- @HOOK_AFTER_ACTIVE --}}

                    <div class="form-group row">
                        @isset($chDeliveryMethod)
                            @can('update', $chDeliveryMethod)
                                <button class='btn btn-success mr-2'
                                        type='submit'
                                        name='action'>@lang('admin/deliveries/delivery.save')
                                </button>

                                <button class='btn btn-primary mr-2'
                                        type='submit'
                                        name='update'>@lang('admin/deliveries/delivery.update')</button>
                            @endcan

                            @can('delete', $chDeliveryMethod)
                                <button class='btn btn-danger mr-2'
                                        type='button'
                                        onclick="if(confirm('@lang("admin/deliveries/delivery.delete_ask")')) document.querySelector( '#delete\\[{{$chDeliveryMethod->id}}\\] ').submit() "
                                        name='delete'>@lang('admin/deliveries/delivery.delete')</button>
                            @endcan
                        @else
                            @can('create', App\Models\DeliveryMethod::class)
                                <button class='btn btn-success mr-2'
                                        type='submit'
                                        name='create'>@lang('admin/deliveries/delivery.create')</button>
                            @endcan
                        @endisset

                        {{-- @HOOK_AFTER_BUTTONS --}}

                        <a class='btn btn-warning'
                           href="{{ route("{$route_namespace}.deliveries.index") }}"
                        >@lang('admin/deliveries/delivery.cancel')</a>
                    </div>

                    {{-- @HOOK_ADDON_BUTTONS --}}
                </form>
            </div>
        </div>
    </div>
</x-admin.main>
