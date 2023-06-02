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
        $users = User::all();
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
        $data = new User();
        if ($request->hasfile('image')) {
            Storage::allFiles();
            if (isset($user->image)) {
                Storage::delete($user->image);
            }
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('Photo'), $filename);
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['password'] = $request->password;
            $data['tel'] = $request->tel;
            $data['desc'] = $request->desc;
            $data['image'] = $filename;
        }
        $data->save();
        return redirect()->route('dashboard.index')->with('success');
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
        $data = User::find($id);

        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('Photo'), $filename);
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['password'] = $request->password;
            $data['tel'] = $request->tel;
            $data['desc'] = $request->desc;
            $data['image'] = $filename;
        }
        $data->save();
        return redirect()->route('dashboard.index')->with('success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
//        dd(Storage::allFiles());
        if (isset($user->image)) {
            Storage::delete($user->image);
        }
        $user->delete();
        return redirect()->back()->with('success');
    }
}
