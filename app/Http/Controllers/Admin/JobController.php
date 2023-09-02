<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\job;
use App\Model\goverment;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class JobController extends Controller
{
    function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $jobs = job::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else {
            $jobs = job::orderBy('id', 'desc');
        }
        $jobs = $jobs->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.job.view', compact('jobs', 'search'));
    }


    public function store(Request $request)
    {
     
        $validator = Validator::make($request->all(), [
            'name'                 => 'required',

        ]);

        $job = new job;
        $job->name = $request->name;

        $job->save();
        Toastr::success('job added successfully!');
        return back();
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $job = job::find($request->id);
            $job->published = $request->status;
            $job->save();
            $data = $request->status;
            return response()->json($data);
        }
    }

    public function edit($id)
    {
        $job = job::where('id', $id)->first();

        return view('admin-views.job.edit', compact('job'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',

        ], [
            'name.required' => 'name is required!',
        ]);

        $job = job::find($id);
        $job->name = $request->name;

        $job->save();

        Toastr::success('job updated successfully!');
        return back();
    }

    public function delete(Request $request)
    {
        $br = job::find($request->id);
        job::where('id', $request->id)->delete();
        return response()->json();
    }


    public function status_update(Request $request)
    {

        $job = job::where(['id' => $request['id']])->first();
        $success = 1;

        if ($request['status'] == 1) {
            if ($job->added_by == 'seller' && ($job->request_status == 0 || $job->request_status == 2)) {
                $success = 0;
            } else {
                $job->status = $request['status'];
            }
        } else {
            $job->status = $request['status'];
        }
        $job->save();
        return response()->json([
            'success' => $success,
        ], 200);
    }
}
