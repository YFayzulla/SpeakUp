@extends('template.master')
@section('content')

    <input type="hidden" value="{{$sum=0}}">
    <div class="float-left col-lg-12">
        <div class="p-4 m-4 sm:p-8 bg-white shadow sm:rounded-lg ">


            <form action="{{ URL::to('/dept/pdf') }}">
                @role('admin')
                <input type="hidden" name="startDate" value="{{$start_date}}">
                <input type="hidden" name="endDate" value="{{$end_date}}">

                <button class="btn btn-danger float-right m-2" > Report </button>
                @endrole
            </form>

            <div class="container" style="display: flex; justify-content: space-between;">
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <tr>
                            <th>no</th>
                            <th>name</th>
                            <th>paid</th>
                            <th>group</th>
                            <th>type</th>
                            <th>date</th>
                            <th>status</th>
                            <th>action</th>
                        </tr>
                        @forelse($historyPayments as $student)
                            <tr @if($student->is_reversed) style="opacity: 0.6;" @endif>
                                <th>{{$loop->index+1}}</th>
                                <th>{{$student->name}}</th>
                                <th @if($student->payment < 0) style="color: red;" @endif>{{$student->payment}}</th>
                                <th>{{$student->group}}</th>
                                <th>{{$student->type_of_money}}</th>
                                <th>{{$student->date}}</th>
                                <th>
                                    @if($student->is_reversed)
                                        <span class="badge bg-label-danger">Reversed</span>
                                    @elseif($student->payment < 0)
                                        <span class="badge bg-label-warning">Reversal</span>
                                    @else
                                        <span class="badge bg-label-success">Active</span>
                                    @endif
                                </th>
                                <th>
                                    @if(!$student->is_reversed && $student->payment > 0)
                                        <form action="{{ route('payment.reverse', $student->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to reverse this payment?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Reverse this payment">
                                                <i class="bx bx-undo"></i> Reverse
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </th>
                                @php
                                    if (!$student->is_reversed && $student->payment > 0) {
                                        $sum += $student->payment;
                                    }
                                @endphp
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No payment records found for the selected period.</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
            <p>Sum in date: {{$sum}} </p>
        </div>
    </div>
@endsection