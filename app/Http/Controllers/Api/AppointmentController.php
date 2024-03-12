<?php

namespace App\Http\Controllers\Api;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return Appointment::query()
        ->with('users')
        ->when(request('status'), function ($query) {
            return $query->where('status', AppointmentStatus::from(request('status')));
        })
        ->latest()->paginate(10)
        ->through(fn ($appointment)=>[
            'id'=> $appointment->id,
            'title'=> $appointment->title,
            'start_time' => $appointment->start_time,
            'end_time' => $appointment->end_time,
            'status'=> [
                'name' => $appointment->status->name,
                'color' => $appointment->status->color(),
            ],
            'user'=> $appointment->users->name
        ]);
    }

    function usersFetch(){
        $users = User::select('id','name')->orderBy('name', 'asc')->get();
        return response()->json($users);
    }


    public function store()
    {
        $validated = request()->validate([
            'user_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'status' => 'required',
        ], [
            'user_id.required' => 'The USer name field is required.',
        ]);

        Appointment::create([
            'title' => $validated['title'],
            'user_id' => $validated['user_id'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        return response()->json(['success'=> true, 'message'=> 'Appointment added'], 200);
    }

    public function show(Appointment $appointment)
    {
        return response()->json($appointment);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = request()->validate([
            'user_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'status'=>'required'
        ], [
            'user_id.required' => 'The User name field is required.',
        ]);

        $appointment->update($validated);

        return response()->json(['success' => true, 'message'=> 'Appointment Update'], 200);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(['success'=> true, 'message'=> 'Appointment Deleted'], 200);
    }

    function getStatusWithCount(){

        $cases = AppointmentStatus::cases();

        return collect($cases)->map(function ($status) {
            return [
                'name' => $status->name,
                'value' => $status->value,
                'count' => Appointment::where('status', $status->value)->count(),
                'color' => AppointmentStatus::from($status->value)->color(),
            ];
        });
    }
}
