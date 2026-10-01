<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(UserStoreRequest $request): RedirectResponse
    {
        User::query()->create($request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created.');
    }

    public function edit(User $user): View|RedirectResponse
    {
        if ($user->is_admin) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Admin users cannot be updated.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        if ($user->is_admin) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Admin users cannot be updated.');
        }

        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Admins are managed outside this CRUD; never promote via update either
        // if you want promotion, do it only on create — keep is_admin from form for clients only.
        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is_admin) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Admin users cannot be deleted.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted.');
    }
}
