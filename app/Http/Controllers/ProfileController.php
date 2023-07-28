<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\MonthlyPayment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('success', 'profile-updated');
    }

    public function save(Request $request)
    {
        $request->validate([
            'tel' => 'required|string',
        ]);
//        $user=User::find($id);
        if ($request->hasFile('image')) {
            if (isset(auth()->user()->image)) {
                Storage::delete(auth()->user()->image);
            }
            $name = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('Photo', $name);
        }

        $image=$request->user()->image;

        if (isset($image)) {
            $request->user()->update([
                'tel' => $request->tel,
                'desc' => $request->desc,
                'image' => $path ?? null,
            ]);
        } else {
            $request->user()->update([
                'tel' => $request->tel,
                'desc' => $request->desc,
                'image' => $path ?? null,
            ]);
        }
        return Redirect::route('profile.edit')->with('success', 'profile-updated');

    }

    /**
     * Delete the user's account.
     */

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function money(Request $request){
        $sum=MonthlyPayment::find(1);
        $sum['sum']=$request->sum;
        $sum->update();
        return Redirect::route('profile.edit')->with('success', 'payment updated');
    }
}