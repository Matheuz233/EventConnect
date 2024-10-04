@extends('layouts.main')

@section('title', 'EventConnect')

@section('content')

    @foreach ($events as $event)
        <p>{{ $event->title }} -- {{ $event->desription }} </p>
    @endforeach
@endsection
