<?php

namespace App\Http\Controllers;

use App\Models\ActiveStudent;
use App\Http\Requests\StoreActiveStudentRequest;
use App\Http\Requests\UpdateActiveStudentRequest;

class ActiveStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreActiveStudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreActiveStudentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ActiveStudent  $activeStudent
     * @return \Illuminate\Http\Response
     */
    public function show(ActiveStudent $activeStudent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ActiveStudent  $activeStudent
     * @return \Illuminate\Http\Response
     */
    public function edit(ActiveStudent $activeStudent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateActiveStudentRequest  $request
     * @param  \App\Models\ActiveStudent  $activeStudent
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateActiveStudentRequest $request, ActiveStudent $activeStudent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ActiveStudent  $activeStudent
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActiveStudent $activeStudent)
    {
        //
    }
}
