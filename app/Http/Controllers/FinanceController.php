<?php

namespace App\Http\Controllers;

use App\Http\Requests\Finance\StoreRequest;
use App\Http\Requests\Finance\UpdateRequest;
use App\Models\Finance;

class FinanceController extends Controller
{
    public function teachers()
    {
        return view('user.finance.teacher.index', [
            'finances' => Finance::query()->where('status', Finance::STATUS_TEACHER)->get(),
        ]);
    }

    public function store_teacher(StoreRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['status'] = Finance::STATUS_TEACHER;

        Finance::query()->create($validatedData);

        return redirect()->back()->with('success', 'Finance created successfully!');
    }

    public function update_teacher(UpdateRequest $request, $id)
    {
        $validatedData = $request->validated();
        $validatedData['status'] = Finance::STATUS_TEACHER;

        $finance = Finance::query()->findOrFail($id);
        $finance->update($validatedData);

        return redirect()->back()->with('success', 'Finance updated successfully!');
    }

    public function destroy_teacher($id)
    {
        $finance = Finance::query()->where('id', $id)->where('status', Finance::STATUS_TEACHER)->firstOrFail();
        $finance->delete();

        return redirect()->back()->with('success', 'Finance deleted successfully!');
    }

    public function other()
    {
        return view('user.finance.other.index', [
            'finances' => Finance::query()->where('status', Finance::STATUS_OTHER)->get(),
        ]);
    }

    public function store_other(StoreRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['status'] = Finance::STATUS_OTHER;

        Finance::query()->create($validatedData);

        return redirect()->back()->with('success', 'Finance created successfully!');
    }

    public function update_other(UpdateRequest $request, $id)
    {
        $validatedData = $request->validated();
        $validatedData['status'] = Finance::STATUS_OTHER;

        $finance = Finance::query()->findOrFail($id);
        $finance->update($validatedData);

        return redirect()->back()->with('success', 'Finance updated successfully!');
    }

    public function destroy_other($id)
    {
        $finance = Finance::query()->where('id', $id)->where('status', Finance::STATUS_OTHER)->firstOrFail();
        $finance->delete();

        return redirect()->back()->with('success', 'Finance deleted successfully!');
    }

    public function income()
    {
        return view('user.finance.income.index', [
            'finances' => Finance::query()->where('status', Finance::STATUS_INCOME)->get(),
        ]);
    }

    public function store_income(StoreRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['status'] = Finance::STATUS_INCOME;

        Finance::query()->create($validatedData);

        return redirect()->back()->with('success', 'Finance created successfully!');
    }

    public function update_income(UpdateRequest $request, $id)
    {
        $validatedData = $request->validated();
        $validatedData['status'] = Finance::STATUS_INCOME;

        $finance = Finance::query()->findOrFail($id);
        $finance->update($validatedData);

        return redirect()->back()->with('success', 'Finance updated successfully!');
    }

    public function destroy_income($id)
    {
        $finance = Finance::query()->where('id', $id)->where('status', Finance::STATUS_INCOME)->firstOrFail();
        $finance->delete();

        return redirect()->back()->with('success', 'Finance deleted successfully!');
    }

}
