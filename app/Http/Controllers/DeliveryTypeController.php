<?php

namespace App\Http\Controllers;

use App\Models\DeliveryType;
use App\Http\Controllers\Controller;
use App\Repositories\DeliveryTypesRepository;
use App\Services\DeliveryTypeService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DeliveryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $deliveryTypeRepository;
    protected $deliveryTypeService;

    public function __construct(DeliveryTypesRepository $deliveryTypeRepository, DeliveryTypeService $deliveryTypeService)
    {
        $this->deliveryTypeRepository = $deliveryTypeRepository;
        $this->deliveryTypeService = $deliveryTypeService;
    }

    public function index()
    {
        $deliveryTypes = $this->deliveryTypeRepository->getAllDeliveryTypes();
        return view('admin.delivery_types.index', compact('deliveryTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.delivery_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $this->deliveryTypeService->saveDeliveryTypes($data);

        return redirect()->route('delivery-types.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $deliveryType = $this->deliveryTypeRepository->getDeliveryTypesByID($id);
        return view('admin.delivery_types.details', compact('deliveryType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $deliveryType = $this->deliveryTypeRepository->getByDeliveryTypeID($id);
        return view('admin.delivery_types.edit',compact('deliveryType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $deliveryType = $this->deliveryTypeRepository->getByDeliveryTypeID($id);
        $this->deliveryTypeService->updateCityByID($data, $deliveryType);
        
        return redirect(route('delivery-types.show', $id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->deliveryTypeService->deleteDeliveryTypeByID($id);
        return redirect()->route('delivery-types.index');
    }

    public function getDeliveryTypes(Request $request)
    {
        $search = $request->search;
        $name = $request->name;
        $data = $this->deliveryTypeRepository->getAllDeliveryTypesQuery();
        if($search) {
            $data = $data->where('delivery_types.name','like', '%' . $search . '%');
        }
        if($name) {
            $data = $data->where('delivery_types.id', $name);
        }
        return DataTables::of($data)
            ->addColumn('action', function($row){
                $actionBtn = '<a href="' . route("delivery-types.show", $row->id) . '" class="info btn btn-info btn-sm">View</a>
                <a href="' . route("delivery-types.edit", $row->id) . '" class="edit btn btn-light btn-sm">Edit</a>
                <form action="'.route("delivery-types.destroy", $row->id) .'" method="post" class="d-inline" onclick="return confirm(`Are you sure you want to Delete this city?`);">
                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                </form>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->orderColumn('id', '-id $1')
            ->make(true);
    }
}
