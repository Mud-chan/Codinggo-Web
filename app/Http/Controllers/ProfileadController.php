<?php

// app/Http/Controllers/RegisterController.php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Tutors;
// use App\Models\User;
use App\Models\Content;
use App\Models\Comments;
use App\Models\Playlist;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash; // Import class Hash

class ProfileadController extends Controller
{
    public function profileadmin()
    {
        $tutorsId = Cookie::get('tutor_id'); // Ambil ID pengguna dari cookie
        $tutors = Tutors::find($tutorsId); // Temukan pengguna berdasarkan ID
        $userName = $tutors->name; // Ambil nama pengguna
        $userImage = $tutors->image;
        $userProfesi = $tutors->profession; // Ambil URL gambar profil pengguna
        $totalPlaylists = Playlist::where('tutor_id', $tutorsId)->count();

        // Hitung total konten yang dimiliki oleh tutor
        $totalContents = Content::where('tutor_id', $tutorsId)->count();

        // Hitung total komentar yang dimiliki oleh tutor
        $totalComments = Comments::whereIn('content_id', function ($query) use ($tutorsId) {
            $query->select('id')->from('content')->where('tutor_id', $tutorsId);
        })->count();

        // Hitung total pengguna yang memiliki tutor dengan ID yang sesuai
        $totalUsers = User::where('tutor_id', $tutorsId)->count();
        return view('profileadmin', [
            "title" => "Profile Admin",
            "userName" => $userName, // Teruskan nama pengguna ke tampilan
            "userImage" => $userImage,
            "userProfesi" => $userProfesi,
            "tutorsId" => $tutorsId,
            "totalUsers" => $totalUsers,
            "totalComments" => $totalComments,
            "totalContents" => $totalContents,
            "totalPlaylists" => $totalPlaylists
        ]);
    }

    // public function store(Request $request)
    // {
    //     if ($request->isMethod('post')) {
    //         $request->validate([
    //             'name' => 'required|string|max:50',
    //             'email' => 'required|email|unique:users',
    //             'password' => 'required|string|min:6|confirmed',
    //             'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
    //         ]);

    //         $id = substr(Str::uuid(), 0, 20);
    //         $image = $request->file('image');
    //         $imageName = time().'.'.$image->extension();
    //         $image->move(public_path('uploaded_files'), $imageName);

    //         $user = User::create([
    //             'id' => $id,
    //             'name' => $request->input('name'),
    //             'email' => $request->input('email'),
    //             'password' => Hash::make($request->input('password')), // Gunakan Hash::make() untuk meng-hash password
    //             'image' => $imageName
    //         ]);

    //         if ($user) {
    //             return redirect('/logreg')->with('success', 'Akun berhasil dibuat. Silakan login.');
    //         } else {
    //             return redirect()->back()->with('error', 'Gagal membuat akun pengguna.');
    //         }
    //     }

    //     // return view('auth.logreg');
    // }


    public function edit()
    {
        // Ambil ID tutor dari cookie
        $tutorsId = Cookie::get('tutor_id');

        // Temukan data tutor berdasarkan ID
        $tutor = Tutors::find($tutorsId);

        // Jika data tidak ditemukan, berikan pesan atau tindakan yang sesuai

        // Kirim data ke tampilan editpasien.blade.php
        $userImage = $tutor->image;
        $userName = $tutor->name;
        $userProfesi = $tutor->profession;

        // Mengirim variabel $tutorId ke view
        return view('updateprofilea', [
            "title" => "Profile User",
            "userName" => $userName,
            "userImage" => $userImage,
            "tutorId" => $tutorsId,
            "userProfesi" => $userProfesi,
            "tutor" => $tutor // Memasukkan variabel $tutor ke dalam array untuk digunakan di dalam view
        ]);
    }






    public function update(Request $request)
    {
        // Ambil ID tutor dari cookie
        $tutorsId = $request->cookie('tutor_id');

        // Temukan data tutor berdasarkan ID
        $tutor = Tutors::find($tutorsId);

        // Validasi input
        $request->validate([
            'name' => 'nullable|string',
            'profession' => 'nullable|string',
            'email' => 'nullable|email|unique:tutors,email,' . $tutorsId,
            'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'old_pass' => 'nullable|string',
            'new_pass' => 'nullable|string',
            'cpass' => 'nullable|string|same:new_pass',
        ]);

        // Proses update data tutor
        if ($request->filled('name')) {
            $tutor->name = $request->name;
        }

        if ($request->filled('profession')) {
            $tutor->profession = $request->profession;
        }

        if ($request->filled('email')) {
            $tutor->email = $request->email;
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploaded_files'), $imageName);
            // Hapus gambar sebelumnya jika ada
            if (!empty($tutor->image)) {
                File::delete(public_path('uploaded_files/' . $tutor->image));
            }
            $tutor->image = $imageName;
        }

        if ($request->filled('old_pass') && $request->filled('new_pass')) {
            if (Hash::check($request->old_pass, $tutor->password)) {
                $tutor->password = Hash::make($request->new_pass);
            } else {
                return redirect()->back()->with('error', 'Incorrect old password.');
            }
        }

        // Simpan perubahan
        $tutor->save();

        return redirect()->route('pages.profileadmin')->with('sucesup', 'Profil Berhasil Di Perbaharui');
    }
}


