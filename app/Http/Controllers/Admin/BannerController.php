<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use DataTables;
class BannerController extends Controller
{

    public function index()
    {
        try {
            if (request()->ajax()) {
                return datatables()->of($banners = Banner::orderByDesc('id')->get())
                    ->addColumn('image', function ($data) {
                        return '<img src="'.asset($data->image).'" alt="" style="height:100px; width:120px; border-radius:50%">';
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="Edit" href="banners/' . $data->id . '/edit" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['image', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.banners.index');

    }

    public function create()
    {
        return view('admin.banners.create');
        
    }

    public function store(Request $request)
    {

        try {

            $image = "";
            if ($request->has('image')) {
    
                $dir      = "uploads/banner/";
                $file     = $request->file('image');
                $fileName = time().$file->getClientOriginalExtension();
                $file->move($dir, $fileName);
    
                $image = $dir.$fileName;
            }
            $banner = Banner::create([
                "image"       => $image,
                "name"        => $request->name,
                "description" => $request->description,
                "zip_code"    => $request->zip_code
             ]);
    
            return redirect('admin/banners')->with('success', "Banner created");
        } catch (\Exception $e) {
            //throw $th;
            return redirect('admin/banners')->with('error', $e->getMessage());

        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $banner = Banner::find($id);
        return view('admin.banners.edit', compact('banner'));

    }

    public function update(Request $request, string $id)
    {
        $banner = Banner::find($id);
        $banner->name = $request->name;
        $banner->description  = $request->description;
        if ($request->has('image')) {
    
            $dir      = "uploads/banner/";
            $file     = $request->file('image');
            $fileName = time().$file->getClientOriginalExtension();
            $file->move($dir, $fileName);
            $banner->image = $dir.$fileName;
        }
        $banner->zip_code = $request->zip_code;
        $banner->save();

        return redirect('admin/banners')->with('success', 'Banner updated');
  
    }

    public function destroy(string $id)
    {
        $banner = Banner::find($id);
        $banner->delete();
        return 1;
    }
}
