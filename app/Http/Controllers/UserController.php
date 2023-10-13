<?php

namespace App\Http\Controllers;


use App\Http\Requests\EditUserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function edit(EditUserRequest $request) {

        $validated = $request->validated();
        
        $target = User::where('email', $request->email)->first();
        if ($target != null) {
            $target->name = $request->name ?: $target->name;
            $target->email = $request->email ?: $target->email;
            $target->address = $request->address ?: $target->address;
            $target->contact = $request->contact ?: $target->contact;
            $target->owner = $request->owner ?: $target->owner;
            $target->business_sector = $request->business_sector ?: $target->business_sector;
            $target->save();
            return response()->json(
                [
                    'api_status' => '200',
                    'message' => 'User Updated',
                ], 200
            );
        } else {
            return response()->json(
                [
                    'api_status' => '404',
                    'message' => 'User Not Found',
                ], 404
            );
        }

    }
}
