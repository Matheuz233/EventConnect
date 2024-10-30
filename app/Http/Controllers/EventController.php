<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $search = request('search');

        if ($search) {
            $events = Event::where([['title', 'like', '%' . $search . '%']])->get();
        } else {
            $events = Event::all();
        }

        return view('welcome', ['events' => $events, 'search' => $search]);
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
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->image = $file_name;
        $event->items = $request->items;

        $user = auth()->user();
        $event->user_id = $user->id;

        $event->save();

        return redirect('/')->with('msg', 'Evento criado com sucesso 👏🏻!');
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);

        $user = auth()->user();
        $hasUserJoined = false;

        if($user) {
            $userEvents = $user->eventsAsParticipant->toArray();

            foreach ($userEvents as $userEvent) {
                if($userEvent['id'] == $id){
                    $hasUserJoined = true;
                }
            }
        }

        $eventOwner = User::where('id', $event->user_id)->first()->toArray();

        return view('events.show', ['event' => $event, 'eventOwner' => $eventOwner, 'hasUserJoined' => $hasUserJoined]);
    }

    public function dashboard()
    {
        $user = auth()->user();

        $events = Event::where('user_id', $user->id)->get();

        $eventsAsParticipant = $user->eventsAsParticipant;

        return view('events.dashboard', ['events' => $events, 'eventsasparticipant' => $eventsAsParticipant]);
    }

    public function destroy($id)
    {
        Event::findOrFail($id)->delete();

        return redirect('/dashboard')->with('msg', 'Evento excluído com sucesso!');
    }

    public function edit($id)
    {
        $user = auth()->user();

        $event = Event::findOrFail($id);

        if ($user->id != $event->$user->id()) {
            return redirect('/dashboard');
        }

        return view('events.edit', ['event' => $event]);
    }

    public function update(Request $request)
    {
        Event::findOrFail($request->id)->update($request->all());

        return redirect('/dashboard')->with('msg', 'Evento editado com sucesso!');
    }

    public function joinEvent($id)
    {
        $user = auth()->user();

        $user->eventsAsParticipant()->attach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Sua presença está confirmada!');
    }

    public function leaveEvent($id)
    {
        $user = auth()->user();

        $user->eventsAsParticipant()->detach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Você saiu com sucesso do evento: ' . $event->title);
    }
}
