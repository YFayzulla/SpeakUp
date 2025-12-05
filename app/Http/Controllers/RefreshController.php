<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RefreshController extends Controller
{
    /**
     * Foydalanuvchi statusini tiklaydi.
     *
     * @param int $id Foydalanuvchi IDsi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 0]);

        return redirect()->back()->with('success', 'Ma\'lumotlar muvaffaqiyatli yangilandi.');
    }
}
