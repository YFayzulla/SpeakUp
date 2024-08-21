@extends('template.master')
@section('content')
    <div class="p-4 m-4 sm:p-8 bg-white shadow sm:rounded-lg ">
        <div class="d-flex">
            <table class="table">
                @foreach($groups as $group)
                    <tr onclick="window.location='{{ route('attendance.check', $group->group_id) }}';" style="cursor:pointer;">
                        <td class="mt-2">
                            <b>{{ $group->group->name }}</b>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
