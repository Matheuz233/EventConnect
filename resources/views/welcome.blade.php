@extends('layouts.main')

@section('title', 'HDC Events')

@section('content')


    <h1>Título</h1>
    <img src="/img/banner.jpg" alt="Banner">
    @if (10 > 5)
        <p>A condição é true</p>
    @endif

    <p>{{ $nome }}</p>

    @for ($i = 0; $i < count($array); $i++)
        <p>{{ $array[$i] }} - {{ $i }}</p>
    @endfor

    @foreach ($nomes as $nome)
        <p>{{ $nome }}</p>
    @endforeach

@endsection
