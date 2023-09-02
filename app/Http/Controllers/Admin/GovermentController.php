<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\goverment;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class GovermentController extends Controller
{
    function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $goverments = goverment::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else {
            $goverments = goverment::orderBy('id', 'desc');
        }
        $goverments = $goverments->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.goverment.view', compact('goverments', 'search'));
    }


    public function store(Request $request)
    {
     
        $validator = Validator::make($request->all(), [
            'name'                 => 'required',
        ]);

        $goverment = new goverment;
        $goverment->name = $request->name;
        $goverment->save();
        Toastr::success('goverment added successfully!');
        return back();
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $goverment = goverment::find($request->id);
            $goverment->published = $request->status;
            $goverment->save();
            $data = $request->status;
            return response()->json($data);
        }
    }

    public function edit($id)
    {
        $goverment = goverment::where('id', $id)->first();
        return view('admin-views.goverment.edit', compact('goverment'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'url is required!',
        ]);

        $goverment = goverment::find($id);
        $goverment->name = $request->name;
    
        $goverment->save();

        Toastr::success('goverment updated successfully!');
        return back();
    }

    public function delete(Request $request)
    {
        $br = goverment::find($request->id);
        goverment::where('id', $request->id)->delete();
        return response()->json();
    }


    public function status_update(Request $request)
    {

        $goverment = goverment::where(['id' => $request['id']])->first();
        $success = 1;

        if ($request['status'] == 1) {
            if ($goverment->added_by == 'seller' && ($goverment->request_status == 0 || $goverment->request_status == 2)) {
                $success = 0;
            } else {
                $goverment->status = $request['status'];
            }
        } else {
            $goverment->status = $request['status'];
        }
        $goverment->save();
        return response()->json([
            'success' => $success,
        ], 200);
    }
}
