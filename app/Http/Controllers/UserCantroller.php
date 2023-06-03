<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserCantroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $name=auth()->user()->name;
        $users = User::where('name','!=',$name)->get();
        return view('admin.teachers.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'tel' => 'required|string',
            'password' => 'required|string',
            'email' => 'required|email',
        ]);
        if ($request->hasFile('image')){
            $name = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('Photo',$name);
        }

        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> bcrypt($request->name),
            'tel'=> $request->tel,
            'desc'=> $request->desc,
            'image'=> $path ?? null,
        ]);
        return redirect()->route('dashboard.index')->with('success','data created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('admin.teachers.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'tel' => 'required|string',
            'password' => 'required|string',
            'email' => 'required|email',
        ]);

        $user=User::find($id);
        if ($request->hasFile('image')){
            if (isset($user->image)){
                Storage::delete($user->image);
            }
            $name = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('Photo',$name);
        }


        $user->update([
            'name' => $request->name,
            'tel' => $request->tel,
            'password' => bcrypt($request->password),
            'email' => $request->email,
            'image' => $path ?? $user->image,
        ]);
        return redirect()->route('dashboard.index')->with('success','data updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
//        dd('salom');
        if (isset($user->image)) {
            Storage::delete($user->image);
        }
        $user->delete();
        return redirect()->back()->with('success','data deleted');
    }
}
