<?php

namespace App\Http\Controllers;

use App\Http\Requests\Finance\StoreRequest;
use App\Http\Requests\Finance\UpdateRequest;
use App\Models\Finance;

class FinanceController extends Controller
{


    public function other()
    {
        return view('user.finance.index', [
            'finances' => Finance::all(),
//            'monthly' => Finance::monthlyPayments()
        ]);
    }

    public function store_other(StoreRequest $request)
    {
        $validatedData = $request->validated();

//        $validatedData['status'] = Finance::STATUS_OTHER;

        Finance::query()->create($validatedData);

        return redirect()->back()->with('success', 'Finance created successfully!');
    }

    public function update_other(UpdateRequest $request, $id)
    {
        $validatedData = $request->validated();
//        $validatedData['status'] = Finance::STATUS_OTHER;

        $finance = Finance::query()->findOrFail($id);
        $finance->update($validatedData);

        return redirect()->back()->with('success', 'Finance updated successfully!');
    }

    public function destroy_other($id)
    {
        $finance = Finance::query()->where('id', $id)->firstOrFail();
        $finance->delete();

        return redirect()->back()->with('success', 'Finance deleted successfully!');
    }
}
