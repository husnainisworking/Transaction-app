<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a list of users
     */
    public function index()
    {
     $users = User::all();

     return response()->json([
        'users' => $users
     ]);
     //instead of returning hardcoded arrays, we fetch all users from the database.
        //User::all() calls laravel's query builder, laravel generates SQL: SELECT *
        // FROM users
        // DB returns rows, Laravel converts them to User model instances, json
        // response automatically serialize the models
    }

    /**
     * Display a single user
     */
    public function show($id)
    {
        //we query the DB for a specific user by ID instead of checking a hardcoded array
        $user = User::find($id);

        if(!$user) {
            return response()->json(['error'=> 'User not found'], 404);
        }

        return response()->json($user);
        //under the hood: User::find($id) generates SQL: SELECT * FROM users WHERE
        // id = ? LIMIT 1
    }

    /**
     * Create a new user
     */
    public function store(Request $request)
    {
     $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8'
     ]);

     $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password'])
     ]);

     return response()->json([
        'message' => 'User created',
        'user' => $user
     ], 201);

     //why?
        //we validate incoming data(security!), we save to the DB, we hash the password,
        //under the hood: $request->validate() check rules, returns validated data or throws
        //error(422 response),
        //User::create() generates SQL: INSERT INTO users(name, email, password) VALUES(?,?,?)
        //Hash::make() uses bcrypt to hash password (one-way encryption)
        //DB returns the new user with auto-generated ID

    }
    /**
     *  Update an existing user
     */
    public function update(Request $request, $id)
    {
        //find the user
        $user = User::find($id);

        if(!$user) {
            return response()->json([
               'error' => 'User not found'
            ], 404);
        }

        //validate incoming data
        $validated = $request->validate([
           'name' => 'sometimes|string|max:255',
           'email' => 'sometimes|email|unique:users, email,' . $id,
            //sometimes means only validate if this field is present in the request
            'password' => 'sometimes|string|min:8'
        ]);

        // Update user
        if(isset($validated['name'])) {
            $user->name = $validated['name'];
        }
        if(isset($validated['email'])) {
            $user->email = $validated['email'];
        }
        if(isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
           'message' => 'User updated',
           'user' => $user
        ]);
    }

    /**
     * Delete a user
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if(!$user) {
            return response()->json([
               'error' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
           'message' => 'User deleted successfully'
        ], 200);
    }

}
