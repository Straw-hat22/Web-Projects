<?php

namespace App\Http\Controllers;

use App\Models\Dojo;
use App\Models\Ninja;
use Illuminate\Http\Request;
use PhpParser\Builder\Function_;
use PhpParser\Node\Expr\FuncCall;

class NinjaController extends Controller
{
    public function index(){
        //route --> /ninjas/
        // fetch the ninjas from the database with descending order
        $ninjas = Ninja::with('dojo')->orderBy('created_at', 'desc')->paginate(10);

        return view('ninjas.index', ['ninjas' => $ninjas]);
    }
    public function show(Ninja $ninja){
        // route --> /ninjas/{id}
        $ninja->load('dojo');
        // fetch record with id
        return view('ninjas.show', ['ninja' => $ninja]);
    }
    public function create(){
        //route --> /ninjas/create
        $dojos = Dojo::all();

        return view('ninjas.create', ['dojos' => $dojos]);
    }
    public function store(Request $request){
        // --> /ninjas/ (post)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'skill' => 'required|integer|min:0|max:100',
            'bio' => 'required|string|min:20|max:1000',
            'dojo_id' => 'required|exists:dojos,id',
        ]);
        Ninja::create($validated);
        return redirect()->route('ninjas.index')->with('success', 'Ninja Created');
    }
    public function destroy(Ninja $ninja){
        // -->/ninjas/{id} (delete)
        $ninja->delete();
        return redirect()->route('ninjas.index')->with('success', 'Ninja Deleted');
    }
}
