<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.users', [
            'header' => 'User Information',
            'users'=> User::all()
        ]);
    }

    public function form()
    {
        return view('users.form',[
            'header' => 'Add User'
        ])
        ;
    }

    public function store(Request $request)
    {
        // For validation
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        //For storing
        User:: create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $request->session()->flash('status', 'User added successfully!');

        //redirect to list of users
        return redirect('/users');

    }

    public function show($id)
    {
        $user = User::find($id);

        return view('users.form', [
                'header'    => 'Update User',
                'user'      => $user
            ]); 
    }

    public function update(Request $request, $id)
    {
         // For Validation
         $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255']
        ]);

        $user = User::find($id);

        $user->update($request->all());

        session()->flash('status', 'Updated User Successfully!');

        return redirect('/users/update/' . $user->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::delete('delete from users where id = ?', [$id]);
        
        return redirect('/users');
    }
}
