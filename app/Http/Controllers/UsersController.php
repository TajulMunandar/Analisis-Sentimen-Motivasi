<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('dashboard.pages.users')->with(compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'username' => ['required', 'max:16', 'unique:users'],
                'password' => 'required|max:255',
                'role' => 'required',
            ]);

            $validatedData['password'] = Hash::make($validatedData['password']);

            User::create($validatedData);

            return redirect('/dashboard/users')->with('success', 'User baru berhasil dibuat!');
        } catch (ValidationException $e) {
            return redirect('/dashboard/users')->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect('/dashboard/users')->with('error', 'Terjadi kesalahan saat membuat user.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'username' => ['required', 'max:16', 'unique:users,username,' . $user->id],
            ]);

            $user->update($validatedData);

            return redirect('/dashboard/users')->with('success', 'Data user berhasil diperbarui!');
        } catch (ValidationException $e) {
            return redirect('/dashboard/users')
                ->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect('/dashboard/users')->with('error', 'Terjadi kesalahan saat memperbarui data user.' . $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return redirect('/dashboard/users')->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect('/dashboard/users')->with('error', 'Terjadi kesalahan saat menghapus user.');
        }
    }

    public function reset(Request $request)
    {
        try {
            $rules = [
                'password' => 'required|max:255',
            ];
            if ($request->password == $request->password2) {
                $validatedData = $request->validate($rules);
                $validatedData['password'] = Hash::make($validatedData['password']);

                User::where('id', $request->id)->update($validatedData);

                return redirect('/dashboard/users')->with('success', 'Password berhasil direset!');
            } else {
                return back()->with('failed', 'Konfirmasi password tidak sesuai');
            }
        } catch (\Exception $e) {
            return redirect('/dashboard/users')->with('error', 'Terjadi kesalahan saat mereset password: ' . $e->getMessage());
        }
    }
}
