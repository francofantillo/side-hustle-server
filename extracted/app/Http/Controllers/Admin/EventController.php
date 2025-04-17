<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\InterestedUser;
use Illuminate\Http\Request;


class EventController extends Controller
{
    public function events(Request $request) {
        $query = Event::with('event_owner', 'event_images');
        $status = $request->input('status');
        if ($status != '') {
            $query->where('status', $request->status);
        }
        return customDatatableResponse($query, $request);
    }

    public function eventDetail($id) {
        $event = Event::with('event_owner')->find($id);
        if ($event == null) return abort(404);
        return view('admin.events.detail', compact('event'));
    }

    public function eventUsers(Request $request) {
        $users = InterestedUser::with('user')->where('event_id', $request->id);
        $status = $request->status;
        if ($status != '') {
            $users->where('status', $status);
        }
        return customDatatableResponse($users, $request);
    }
}