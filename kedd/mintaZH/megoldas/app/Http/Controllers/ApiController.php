<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task3Request;
use App\Models\Location;
use App\Models\Weather;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function task1() {
        return Location::all();
    }

    public function task2($id) {
        // if (!filter_var($id, FILTER_VALIDATE_INT)) {
        //     return response(status: 422);
        // }

        $validator = Validator::make(['id' => $id], ['id' => 'integer']);

        if($validator->fails()) {
            return response(status: 422);
        }

        return Location::findOrFail($id);
    }

    public function task3(Task3Request $request) {
        // + fealdat: A validációt szervezd ki Form request-be!

        $validated = $request->validated();

        $name = Location::where('name', $validated['name'])->first();
        if($name) {
            return response(status: 409);
        }

        $email = Location::where('email', $validated['email'])->first();
        if($email) {
            return response(status: 409);
        }

        $validated['password'] = Hash::make($validated['password']);

        $location = Location::create($validated);
        $location = Location::find($location->id);
        return response()->json($location, 201);
    }

    public function task5(Request $request) {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $location = Location::where('email', $validated['email'])->first();
        if($location) {
            if(Auth::attempt($validated)) {
                $token = $location->createToken('asdf', []);
                return response()->json(['token' => $token->plainTextToken], 201);
            }
        }
        return response(status: 401);
    }

    public function task6(Request $request) {
        return $request->user()->weather()->orderBy('logged_at')->get();
    }

    public function task7(Request $request) {
        $validated = $request->validate([
            "type" => 'required|string',
            "startTime" => 'required|date',
            "interval" => 'required|integer',
            "temps" => 'required|array|min:1',
            "temps.*" => 'numeric'
        ]);

        $result = [];

        $st = \Carbon\Carbon::parse($validated['startTime']);

        foreach($validated['temps'] as $temp) {
            $weather = Weather::create([
                'type' => $validated['type'],
                'location_id' => $request->user()->id,
                'temp' => $temp,
                'logged_at' => $st->format('Y-m-d H:i:s'),
            ]);
            $result[] = $weather;
            $st->addMinute($validated['interval']);
        }

        return response($result, 201);
    }
}
