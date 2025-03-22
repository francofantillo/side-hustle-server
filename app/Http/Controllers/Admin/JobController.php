<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobRequest;
use App\Models\Review;


class JobController extends Controller
{
    public function userJobs(Request $request) {

        $query  = Job::with('images');
        $status = $request->input('status');
        if ($status != '') {
            $query->where('status', $request->status);
        }
        return customDatatableResponse($query, $request);
    }

    public function jobDetail($id) {
        $job = Job::with('user', 'assign_user')->find($id);
        if ($job == null) return abort(404);
        return view('admin.jobs.detail', compact('job'));
    }

    public function jobRequest(Request $request) {
        $jobRequest = JobRequest::with('applier')->where('job_id', $request->id);
        return customDatatableResponse($jobRequest, $request);
    }

    public function jobReviews(Request $request) {
        $jobReviews = Review::with('owner', 'user')->where([
            'model_id' => $request->id,
            'model_name' => 'Job'
        ]);
        return customDatatableResponse($jobReviews, $request);
    }
}