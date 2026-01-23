<?php

namespace App\Http\Controllers;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Role::create($request->all());

        return redirect()->route('roles.index')
                         ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $role->update($request->all());

        return redirect()->route('roles.index')
                         ->with('success', 'Role updated successfully.');
    }
}


