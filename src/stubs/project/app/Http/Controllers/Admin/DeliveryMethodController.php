<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\DeliveryMethodRequest;
use App\Models\DeliveryMethod;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DeliveryMethodController extends Controller {
    public function __construct() {
        if(!request()->route()) return;

        $this->db_table = DeliveryMethod::getModel()->getTable();
        $this->routeNamespace = Str::before(request()->route()->getName(), '.deliveries');
        View::composer('admin/deliveries/*', function($view)  {
            $viewData = [
                'route_namespace' => $this->routeNamespace,
            ];
            // @HOOK_VIEW_COMPOSERS
            $view->with($viewData);
        });
        // @HOOK_CONSTRUCT
    }

    public function index() {
        $viewData = [];
        $viewData['deliveryMethods'] = DeliveryMethod::where("{$this->db_table}.site_id", app()->make('Site')->id)->orderBy("{$this->db_table}.ord", 'ASC');

        // @HOOK_INDEX_END

        $viewData['deliveryMethods'] = $viewData['deliveryMethods']->paginate(20)->appends( request()->query() );

        return view('admin/deliveries/deliveries', $viewData);
    }

    public function create() {
        $viewData = [];
        $viewData['pms'] = PaymentMethod::where('site_id', app()->make('Site')->id)
            ->orderBy('ord');

        // @HOOK_CREATE

        $viewData['pms'] = $viewData['pms']->get();
        return view('admin/deliveries/delivery', $viewData);
    }

    public function edit(DeliveryMethod $chDeliveryMethod) {
        $viewData = [];
        $viewData['chDeliveryMethod'] = $chDeliveryMethod;
        $viewData['pms'] = PaymentMethod::where('site_id', app()->make('Site')->id)
            ->orderBy('ord');

        // @HOOK_EDIT
        $viewData['pms'] = $viewData['pms']->get();

        return view('admin/deliveries/delivery', $viewData);
    }

    public function store(DeliveryMethodRequest $request) {
        $validatedData = $request->validated();

        // @HOOK_STORE_VALIDATE

        $chDeliveryMethod = DeliveryMethod::create( array_merge([
            'site_id' => app()->make('Site')->id,
        ], $validatedData));

        // @HOOK_STORE_INSTANCE

        $chDeliveryMethod->setAVars($validatedData['add']);
        $chDeliveryMethod->setDefault($validatedData['default2']);
        $chDeliveryMethod->payments()->sync( $validatedData['pm'] );

        // @HOOK_STORE_END
        event( 'delivery.submited', [$chDeliveryMethod, $validatedData] );

        return redirect()->route($this->routeNamespace.'.deliveries.edit', $chDeliveryMethod)
            ->with('message_success', trans('admin/deliveries/delivery.created'));
    }

    public function update(DeliveryMethod $chDeliveryMethod, DeliveryMethodRequest $request) {
        $validatedData = $request->validated();

        // @HOOK_UPDATE_VALIDATE
        $chDeliveryMethod->update( $validatedData );
        $chDeliveryMethod->setAVars($validatedData['add']);
        $chDeliveryMethod->setDefault($validatedData['default2']);
        $chDeliveryMethod->payments()->sync( $validatedData['pm'] );

        // @HOOK_UPDATE_END

        event( 'delivery.submited', [$chDeliveryMethod, $validatedData] );
        if($request->has('action')) {
            return redirect()->route($this->routeNamespace.'.deliveries.index')
                ->with('message_success', trans('admin/deliveries/delivery.updated'));
        }
        return back()->with('message_success', trans('admin/deliveries/delivery.updated'));
    }

    public function move(DeliveryMethod $chDeliveryMethod, $direction) {
        // @HOOK_MOVE

        $chDeliveryMethod->orderMove($direction);

        // @HOOK_MOVE_END

        return back();
    }

    public function destroy(DeliveryMethod $chDeliveryMethod, Request $request) {
        // @HOOK_DESTROY

        $chDeliveryMethod->delete();

        // @HOOK_DESTROY_END

        if($request->redirect_to)
            return redirect()->to($request->redirect_to)
                ->with('message_danger', trans('admin/deliveries/delivery.deleted'));

        return redirect()->route($this->routeNamespace.'.deliveries.index')
            ->with('message_danger', trans('admin/deliveries/delivery.deleted'));
    }
}
