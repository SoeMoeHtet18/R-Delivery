<?php

namespace App\Http\Controllers;

use App\Models\ItemType;
use App\Repositories\ItemTypeRepository;
use App\Services\ItemTypeService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ItemTypeController extends Controller
{   
    protected $itemTypeRepository;
    protected $itemTypeService;
    public function __construct(ItemTypeRepository $itemTypeRepository, ItemTypeService $itemTypeService)
    {
        $this->itemTypeRepository = $itemTypeRepository;
        $this->itemTypeService    = $itemTypeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->itemTypeRepository->getAllItemTypesQuery();
            return DataTables::of($data)
                
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="' . route("itemtypes.show", $row->id) . '" class="info btn btn-info btn-sm">View</a>
                    <a href="' . route("itemtypes.edit", $row->id) . '" class="edit btn btn-light btn-sm">Edit</a>
                    <form action="'.route("itemtypes.destroy", $row->id) .'" method="post" class="d-inline">
                        <input type="hidden" name="_token" value="'. csrf_token() .'">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                    </form>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->orderColumn('id','-id $1')
                ->make(true);
        }
        return view('admin.itemtype.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.itemtype.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [ 
            'name'                  => 'required|string',
        ];
            
        $customErr = [
            'name.required'         => 'Name field is required.',
        ];
        $validator = Validator::make($request->all(), $rules,$customErr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = $request->all();
            $this->itemTypeService->saveitemTypeData($data);
        }

        return redirect()->route('itemtypes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $itemtype = $this->itemTypeRepository->getByItemTypeID($id);
        return view('admin.itemtype.detail',compact('itemtype'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $itemtype = $this->itemTypeRepository->getByItemTypeID($id);
        return view('admin.itemtype.edit',compact('itemtype'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [ 
            'name'                  => 'required|string',
        ];
            
        $customErr = [
            'name.required'                 => 'Name field is required.',
        ];
        $validator = Validator::make($request->all(), $rules,$customErr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = $request->all();
            $itemtype = $this->itemTypeRepository->getByItemTypeID($id);
            $this->itemTypeService->updateItemTypeByID($data,$itemtype);
        }

        return redirect(route('itemtypes.show', $id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->itemTypeService->deleteItemTypeByID($id);
        return redirect()->route('itemtypes.index');
    }
}
