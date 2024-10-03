<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $nome = "Matheus";

        $array = [10, 20, 30, 40, 50];

        $nomes = ["Matheus", "Maria", "Jaoo", "Pedro"];

        return view('welcome', ['nome' => $nome, "array" => $array, "nomes" => $nomes]);
    }

    public function create()
    {
        return view('events.create');
    }
}