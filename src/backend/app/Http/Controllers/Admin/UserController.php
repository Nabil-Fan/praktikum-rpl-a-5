<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Daftar semua user (role = user), bisa search nama/email.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $filter = $request->query('filter', 'active'); // active | deleted | all

        $query = User::where('role', 'user');

        // Filter status
        match ($filter) {
            'deleted' => $query->whereNotNull('deleted_at'),
            'all'     => $query->withTrashed(),
            default   => $query->whereNull('deleted_at'),
        };

        // Search nama atau email
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

    /**
     * Soft-delete user (nonaktifkan akun).
     */
    public function destroy(User $user)
    {
        // Cegah admin menghapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['deleted_at' => now()]);

        return back()->with('success', "Akun \"{$user->name}\" berhasil dinonaktifkan.");
    }

    /**
     * Restore user yang di-soft-delete.
     */
    public function restore(int $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->update(['deleted_at' => null]);

        return back()->with('success', "Akun \"{$user->name}\" berhasil dipulihkan.");
    }
}