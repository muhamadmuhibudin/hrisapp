<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Role::class);
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $this->authorize('create', Role::class);
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:roles,title',
            'description' => 'nullable|string',
        ]);

        Role::create($validatedData);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $this->authorize('update', $role);
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:roles,title,' . $role->id,
            'description' => 'nullable|string',
        ]);

        $role->update($validatedData);

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}