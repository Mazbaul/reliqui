<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Invitation;
use Illuminate\Http\Request;
use App\Models\Doctor\Doctor;
use App\Models\Setting\Staff\Staff;

class InvitationController extends Controller
{
    public function accept($token)
    {
        if (! $invite = Invitation::where('token', $token)->first()) {
            abort(404);
        }

        return view('auth.invitation', compact('invite'));
    }

    public function join($token)
    {
        $this->validate(request(), [
            'name'     => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (! $invite = Invitation::where('token', $token)->first()) {
            abort(500);
        }

        $user = User::create([
            'name'     => request('name'),
            'email'    => $invite->email,
            'password' => bcrypt(request('password')),
        ]);

        if ($invite->role === 'doctor') {
            $user->doctor = Doctor::create([
                'user_id'  => $user->id,
                'group_id' => $invite->group_id,
            ]);
        }

        if ($invite->role === 'admin-group' || $invite->role === 'admin-counter') {
            $user->staff = Staff::create([
                'user_id'  => $user->id,
                'group_id' => $invite->group_id,
            ]);
        }
        $user->assignRole($invite->role);
        $invite->delete();

        flash('Successful! Invitation Accepted. Now you can login with your credentials.')->success();

        return redirect('/login');
    }
}
