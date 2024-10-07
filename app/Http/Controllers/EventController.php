<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();

        return view('welcome', ['events' => $events]);
    }

    public function create()
    {
        return view('events.create');
    }


    public function store(Request $request)
    {
        $extension = $request->file('image')->extension();
        $file_name = md5($request->file('image')->getClientOriginalName() . strtotime("now")) . "." . $extension;

        # This will upload inside the directory
        $request->file('image')->move(public_path('img/events'), $file_name);

        $event = new Event;

        $event->title = $request->title;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->image = $file_name;
        $event->items = $request->items;


        $event->save();

        return redirect('/')->with('msg', 'Evento criado com sucesso 👏🏻!');
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);

        return view('events.show', ['event' => $event]);
    }
}