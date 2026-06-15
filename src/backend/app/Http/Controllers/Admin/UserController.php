<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $filter = $request->query('filter', 'active');

        $query = User::where('role', 'user');

        if ($filter === 'deleted') {
            $query->whereNotNull('deleted_at');
        } elseif ($filter === 'active') {
            $query->whereNull('deleted_at');
        }
        // filter 'all' = tidak ada kondisi tambahan

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $counts = [
            'active'  => User::where('role', 'user')->whereNull('deleted_at')->count(),
            'deleted' => User::where('role', 'user')->whereNotNull('deleted_at')->count(),
        ];

        return view('admin.users.index', compact('users', 'search', 'filter', 'counts'));
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['deleted_at' => now()]);

        return back()->with('success', "Akun \"{$user->name}\" berhasil dinonaktifkan.");
    }

    public function restore(int $id)
    {
        $user = User::whereNotNull('deleted_at')->findOrFail($id);
        $user->update(['deleted_at' => null]);

        return back()->with('success', "Akun \"{$user->name}\" berhasil dipulihkan.");
    }
}