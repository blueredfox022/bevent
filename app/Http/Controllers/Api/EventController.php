<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return response()->json(
            Event::latest()->get()
        );
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'quota' => 'required|integer|min:0',
            'use_certificate' => 'required|boolean',
            'banner' => 'nullable|image|max:2048',
        ]);


        $bannerPath = null;

        if ($request->hasFile('banner')) {
            $bannerPath = $request
                ->file('banner')
                ->store('events', 'public');
        }


        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'quota' => $request->quota,
            'use_certificate' => $request->use_certificate,
            'banner' => $bannerPath,
        ]);


        return response()->json([
            'message' => 'Event berhasil dibuat',
            'data' => $event
        ], 201);
    }


    public function show($id)
    {
        return Event::findOrFail($id);
    }


    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);


        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'quota' => 'required|integer|min:0',
            'use_certificate' => 'required|boolean',
            'banner' => 'nullable|image|max:2048',
        ]);


        $bannerPath = $event->banner;


        if ($request->hasFile('banner')) {
            $bannerPath = $request
                ->file('banner')
                ->store('events', 'public');
        }


        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'quota' => $request->quota,
            'use_certificate' => $request->use_certificate,
            'banner' => $bannerPath,
        ]);


        return response()->json([
            'message' => 'Event berhasil diupdate',
            'data' => $event
        ]);
    }


    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        $event->delete();

        return response()->json([
            'message' => 'Event berhasil dihapus'
        ]);
    }


    public function participants($id)
    {
        $event = Event::with(
            'participants'
        )->findOrFail($id);

        return response()->json(
            $event->participants
        );
    }
}
