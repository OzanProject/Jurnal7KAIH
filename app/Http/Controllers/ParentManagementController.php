<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ParentManagementController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $search = $request->query('search');

        // Fetch Users directly to include those without parent linkage
        $query = User::where('role', 'orang_tua')
                    ->where('school_id', $schoolId)
                    ->with(['parent.student.classRoom']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('parent.student', function($subQ) use ($search) {
                      $subQ->where('nama', 'like', "%{$search}%")
                           ->orWhere('nis', 'like', "%{$search}%");
                  });
            });
        }

        $parents = $query->latest()->paginate(10)->appends(['search' => $search]);

        return view('backend.admin.parents.index', compact('parents'));
    }

    public function create()
    {
        $schoolId = Auth::user()->school_id;
        $students = Student::whereHas('user', function($q) use ($schoolId) {
                        $q->where('school_id', $schoolId);
                    })->with('classRoom')->get();

        return view('backend.admin.parents.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'student_id' => 'required|exists:students,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'orang_tua',
            'school_id' => Auth::user()->school_id,
        ]);

        ParentModel::create([
            'user_id' => $user->id,
            'student_id' => $request->student_id,
        ]);

        return redirect()->route('admin.parents.index')->with('success', 'Data Orang Tua berhasil ditambahkan.');
    }
    
    // $id is now User ID
    public function edit($id)
    {
        $parentUser = User::where('role', 'orang_tua')->with('parent')->findOrFail($id);
        
        $schoolId = Auth::user()->school_id;
        $students = Student::whereHas('user', function($q) use ($schoolId) {
                        $q->where('school_id', $schoolId);
                    })->with('classRoom')->get();

        return view('backend.admin.parents.edit', compact('parentUser', 'students'));
    }

    // $id is now User ID
    public function update(Request $request, $id)
    {
        $user = User::where('role', 'orang_tua')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'student_id' => 'required|exists:students,id',
            'password' => 'nullable|min:8',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Update or Create Parent Link
        ParentModel::updateOrCreate(
            ['user_id' => $user->id],
            ['student_id' => $request->student_id]
        );

        return redirect()->route('admin.parents.index')->with('success', 'Data Orang Tua berhasil diperbarui.');
    }

    /**
     * Reset parent password to default.
     */
    public function resetPassword($id)
    {
        $user = User::where('role', 'orang_tua')->findOrFail($id);
        
        if ($user->school_id !== Auth::user()->school_id) {
            abort(403);
        }

        $user->update([
            'password' => Hash::make('12345678')
        ]);

        return redirect()->back()->with('success', 'Password orang tua atas nama ' . $user->name . ' berhasil direset menjadi 12345678');
    }

    public function destroy($id)
    {
        $user = User::where('role', 'orang_tua')->findOrFail($id);
        
        if ($user->parent) {
            $user->parent->delete();
        }
        $user->delete();

        return redirect()->route('admin.parents.index')->with('success', 'Data Orang Tua berhasil dihapus.');
    }
}
