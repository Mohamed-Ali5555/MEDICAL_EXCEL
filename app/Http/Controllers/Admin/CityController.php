<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\city;
use App\Model\goverment;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CityController extends Controller
{
    function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $cities = city::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else {
            $cities = city::orderBy('id', 'desc');
        }
        $cities = $cities->paginate(Helpers::pagination_limit())->appends($query_param);
        $goverments = goverment::get();

        return view('admin-views.city.view', compact('cities', 'search','goverments'));
    }


    public function store(Request $request)
    {
     
        $validator = Validator::make($request->all(), [
            'name'                 => 'required',
            'goverment_id'          => 'required',

        ]);

        $city = new city;
        $city->name = $request->name;
        $city->goverment_id = $request->goverment_id;

        $city->save();
        Toastr::success('city added successfully!');
        return back();
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $city = city::find($request->id);
            $city->published = $request->status;
            $city->save();
            $data = $request->status;
            return response()->json($data);
        }
    }

    public function edit($id)
    {
        $city = city::where('id', $id)->first();
        $goverments = goverment::get();

        return view('admin-views.city.edit', compact('city','goverments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'goverment_id'          => 'required',

        ], [
            'name.required' => 'name is required!',
        ]);

        $city = city::find($id);
        $city->name = $request->name;
        $city->goverment_id = $request->goverment_id;

        $city->save();

        Toastr::success('city updated successfully!');
        return back();
    }

    public function delete(Request $request)
    {
        $br = city::find($request->id);
        city::where('id', $request->id)->delete();
        return response()->json();
    }


    public function status_update(Request $request)
    {

        $city = city::where(['id' => $request['id']])->first();
        $success = 1;

        if ($request['status'] == 1) {
            if ($city->added_by == 'seller' && ($city->request_status == 0 || $city->request_status == 2)) {
                $success = 0;
            } else {
                $city->status = $request['status'];
            }
        } else {
            $city->status = $request['status'];
        }
        $city->save();
        return response()->json([
            'success' => $success,
        ], 200);
    }
}
