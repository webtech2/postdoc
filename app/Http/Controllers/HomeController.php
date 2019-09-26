<?php

namespace App\Http\Controllers;

use App\Change;
use App\DataHighwayLevel;
use App\DataSource;
use App\MetadataProperty;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sources = DataSource::whereNull('so_deleted')->orderBy('so_created','desc')->take(10)->get();
        $dhlevels = DataHighwayLevel::all();
        $changes = Change::all();
        $mdproperties = MetadataProperty::all();
        return view('home', compact('sources', 'dhlevels', 'changes', 'mdproperties'));
    }
}
