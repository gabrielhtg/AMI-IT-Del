<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AcceptRegisterMail;
use App\Mail\RejectRegisterMail;
use App\Mail\ResetPasswordMail;
use App\Models\AllowedUserModel;
use App\Models\Employee;
use App\Models\JenisLaporan;
use App\Models\LogLaporan;
use App\Models\NotificationModel;
use App\Models\RegisterInvitationModel;
use App\Models\RoleModel;
use App\Models\User;
use App\Services\AllServices;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Ramsey\Uuid\Uuid;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function getHalamanLogin(): View
    {
        $data = [
            'roles' => RoleModel::all()
        ];

        return view('auth.register', $data);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
        ]);

        if(auth()->check()) {
            $allowedEmail = AllowedUserModel::where('email', $request->email)->first();

            if ($allowedEmail == null) {
                AllowedUserModel::create([
                    'email' => $request->email,
                    'created_by' => auth()->user()->username,
                    'created_at' => now()
                ]);
            }
        }

        $data = AllowedUserModel::where('email', $request->email)->first();
        $user = User::where('username', $request->username)->first();


        if ($data !== null) {
            if (!$user) {
                try {
                    $temp = Uuid::uuid4()->toString();

                    $userId = User::create([
                        'name' => $request->name,
                        'username' => $request->username,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'verified' => true,
                        'status' =>true,
                        'password' => Hash::make($temp),
                        'role' => implode(';', $request->roles)
                    ])->id;

                    Employee::create([
                        'user_id' => $userId,
                        'name' => $request->name,
                        'role' => implode(';', $request->roles)
                    ]);


                    $role_users = $request->roles;

                    // Ambil objek RoleModel berdasarkan ID
                    foreach ($role_users as $role_user) {
                        $role = RoleModel::where('id', $role_user)->first();

                        $Laporan = $role->required_to_submit_document;


                        $tipeLaporan = explode(';', $Laporan);


                        $jenis_laporan = JenisLaporan::whereIn('id_tipelaporan', $tipeLaporan)->get();

                        foreach ($jenis_laporan as $jenis) {
                            LogLaporan::create([
                                'id_jenis_laporan' => $jenis->id,
                                'id_tipe_laporan'=>$jenis->id_tipelaporan,
                                'upload_by' => $userId,
                                'create_at'=>null,
                                'approve_at'=>null,
                                'end_date'=>$jenis->end_date,
                            ]);
                        }
                    }


                    AllServices::addLog(sprintf("Menambahkan user %s", $request->name));

                    Mail::to($request->email)->send(new AcceptRegisterMail("Kamu sudah diregister sebagai " . AllServices::convertRole($request->role) . ". Anda sekarang sudah bisa login dengan password " . $temp));
                    return redirect()->route('user-settings-active')->with('toastData', ['success' => true, 'text' => 'Berhasil menambahkan']);
                }
                catch (QueryException $e) {
                    if ($e->errorInfo[1] == 1062) {
                        return redirect()->route('user-settings-active')->with('toastData', ['success' => false, 'text' => 'Gagal. User sudah terdaftar sebelumnya.']);
                    }
                }

            }

            else {
                return redirect()->route('user-settings-active')->with('toastData', ['success' => false, 'text' => 'Gagal. User sudah terdaftar sebelumnya.']);
            }
        }

        return redirect()->route('user-settings-active')->with('toastData', ['success' => false, 'text' => 'Menambahkan user ' . $request->name . ' tidak diizinkan']);
    }

//    public function sendRegisterInvitationLink(Request $request)
//    {
//        $request->validate([
//            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
//        ]);
//
//        try {
//            $temp = RegisterInvitationModel::where('email', $request->email)->first();
//
//            if ($temp) {
//                $temp->update([
//                    'role' => $request->role
//                ]);
//                Mail::to($request->email)->send(new RegisterInvitationMail($request->pesan, $request->role, $temp->token));
//                return redirect()->route('user-settings')->with('toastData', ['success' => true, 'text' => 'Undangan telah dikirim ulang']);
//            }
//
//            else {
//                $reqToken = Uuid::uuid1()->toString();
//
//                RegisterInvitationModel::create([
//                    'email' => $request->email,
//                    'role' => $request->role,
//                    'token' => $reqToken
//                ]);
//
//                Mail::to($request->email)->send(new RegisterInvitationMail($request->pesan, $request->role, $reqToken));
//
//            }
//
//            return redirect()->route('user-settings')->with('toastData', ['success' => true, 'text' => "Undangan terkirim!"]);
//        }
//        catch (QueryException $e) {
//            return redirect()->route('user-settings')->with('toastData', ['success' => false, 'text' => "Terjadi kesalahan."]);
//        }
//    }

    /**
     * @param Request $request
     * @return RedirectResponse
     *
     * Method ini berfungsi sebagai method yang digunakan untuk menyimpan data saat user register dari halaman register
     */
    public function registerSelfUser(Request $request)
    {
        $data = AllowedUserModel::where('email', $request->email)->first();

        if ($data !== null) {
            try {
                $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                    'username' => ['required', 'string', 'max:20'],
                    'password' => ['required', 'confirmed', Rules\Password::defaults()],
                ]);

                $userId= User::create([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'verified' => false,
                    'status' => false,
                    'password' => Hash::make($request->password),
                    'pending_roles' => implode(";", $request->roles)
                ])->id;

                $role_users = $request->roles;

                // Ambil objek RoleModel berdasarkan ID
                foreach ($role_users as $role_user) {
                    $role = RoleModel::where('id', $role_user)->first();
                    $Laporan = $role->required_to_submit_document;
                    $tipeLaporan = explode(';', $Laporan);
                    $jenis_laporan = JenisLaporan::whereIn('id_tipelaporan', $tipeLaporan)->get();

                    foreach ($jenis_laporan as $jenis) {
                        LogLaporan::create([
                            'id_jenis_laporan' => $jenis->id,
                            'id_tipe_laporan'=>$jenis->id_tipelaporan,
                            'upload_by' => $userId,
                            'create_at'=>null,
                            'approve_at'=>null,
                            'end_date'=>$jenis->end_date,
                        ]);
                    }
                }

                $adminRoles = RoleModel::where('is_admin', true)->get();

                foreach ($adminRoles as $adminRole) {
                    $admins = User::where('role', 'LIKE', '%' . $adminRole->id . '%')->get();

                    foreach ($admins as $admin) {
                        if (in_array($adminRole->id, explode(";", $admin->role))) {
                            NotificationModel::create([
                                'message' => "Permintaan register dari " .  $request->name . ".",
                                'ref_link' => "user-settings-active",
                                'to' => $admin->id,
                                'clicked' => false,
                            ]);
                        }
                    }
                }

                return redirect()->route('login')->with('data', ['failed' => false, 'text' => 'Permintaan Register Terkirim']);
            }
            catch (QueryException $e) {
                if ($e->errorInfo[1] == 1062) {
                    return redirect()->route('login')->with('data', ['failed' => true, 'text' => 'Gagal. User sudah terdaftar sebelumnya.']);
                }
            }
        }

        else {
            return redirect()->route('login')->with('data', ['failed' => true, 'text' => 'Permintaan Pendaftaran Tidak Diizinkan']);
        }
        return redirect()->route('login')->with('data', ['failed' => true, 'text' => 'Permintaan Pendaftaran Tidak Diizinkan']);
    }

    public function deleteInvitation(Request $request) {
        $data = RegisterInvitationModel::find($request->id);

        if ($data) {
            $data->delete();
            return redirect()->route('user-settings')->with('toastData', ['success' => true, 'text' => 'Berhasil menghapus tautan undangan.']);
        }

        else {
            return redirect()->route('user-settings')->with('toastData', ['success' => false, 'text' => 'Gagal menghapus tautan undangan. Tautan undangan tidak ditemukan!']);
        }
    }

    public function clearInvitation () {
        RegisterInvitationModel::truncate();

        return redirect()->route('user-settings')->with('toastData', ['success' => true, 'text' => 'Berhasil menghapus semua data!']);
    }

    public function acceptRegisterRequest(Request $request) {
        $resetObject = User::find($request->id);
        $temp = $resetObject->pending_roles;

        if ($resetObject) {
            $resetObject->update([
                'verified' => true,
                'role' => $temp,
                'status' => true,
                'pending_roles' => null,
                'created_at' => now()
            ]);

            Employee::create([
                'user_id' => $resetObject->id,
                'name' => $resetObject->name,
                'role' => $temp,
            ]);

            $adminRoles = RoleModel::where('is_admin', true)->get();

            foreach ($adminRoles as $adminRole) {
                $admins = User::where('role', 'LIKE', '%' . $adminRole->id . '%')->get();

                foreach ($admins as $admin) {
                    if (in_array($adminRole->id, explode(";", $admin->role))) {
                        NotificationModel::create([
                            'message' => "Permintaan register dari " .  $resetObject->name . " diterima oleh " . auth()->user()->name,  ".",
                            'ref_link' => "user-settings-active",
                            'to' => $admin->id,
                            'clicked' => false,
                        ]);
                    }
                }
            }

            Mail::to($resetObject->email)->send(new AcceptRegisterMail("Permintaan request kamu sebagai " . AllServices::convertRole($resetObject->role) . " sudah diterima. Anda sekarang sudah bisa login."));

            AllServices::addLog(sprintf("Menerima permintaan action dari %s", $resetObject->name));
            return redirect()->route('user-settings-active')->with('toastData', ['success' => true, 'text' => "Berhasil menerima permintaan!"]);
        }
        else {
            return redirect()->route('user-settings-active')->with('toastData', ['success' => false, 'text' => "Gagal menerima permintaan! Email tidak ditemukan!"]);
        }
    }

    public function deleteRegisterRequest(Request $request) {
        $data = User::find($request->id);

        if ($data && $data->status == false) {
            Mail::to($data->email)->send(new RejectRegisterMail("Permintaan request kamu sebagai " . AllServices::convertRole($data->role) . " ditolak. Pastikan data yang anda masukkan sudah tepat atau anda memang sudah diizinkan untuk mendaftar!"));
            $data->delete();

            $adminRoles = RoleModel::where('is_admin', true)->get();

            foreach ($adminRoles as $adminRole) {
                $admins = User::where('role', 'LIKE', '%' . $adminRole->id . '%')->get();

                foreach ($admins as $admin) {
                    if (in_array($adminRole->id, explode(";", $admin->role))) {
                        NotificationModel::create([
                            'message' => "Permintaan ganti role dari " .  $request->name . "ditolak oleh " . auth()->user()->name . ".",
                            'ref_link' => "user-settings-active",
                            'to' => $admin->id,
                            'clicked' => false,
                        ]);
                    }
                }
            }

            Mail::to($data->email)->send(new RejectRegisterMail("Permintaan request kamu sebagai " . AllServices::convertRole($data->role) . " ditolak. Pastikan data yang anda masukkan sudah tepat atau anda memang sudah diizinkan untuk mendaftar!"));

            AllServices::addLog(sprintf("Menolak permintaan pendaftaran dari %s", $data->name));
            return redirect()->route('user-settings-active')->with('toastData', ['success' => true, 'text' => 'Berhasil menghapus!']);
        }

        else if ($data && $data->status != null) {
            $data->update([
                'verified' => true,
                'pending_roles' => null
            ]);

            $adminRoles = RoleModel::where('is_admin', true)->get();

            foreach ($adminRoles as $adminRole) {
                $admins = User::where('role', 'LIKE', '%' . $adminRole->id . '%')->get();

                foreach ($admins as $admin) {
                    if (in_array($adminRole->id, explode(";", $admin->role))) {
                        NotificationModel::create([
                            'message' => "Permintaan ganti role dari " .  $request->name . "ditolak oleh " . auth()->user()->name . ".",
                            'ref_link' => "user-settings-active",
                            'to' => $admin->id,
                            'clicked' => false,
                        ]);
                    }
                }
            }
            AllServices::addLog(sprintf("Menolak permintaan pergantian role dari %s", $data->name));
            return redirect()->route('user-settings-active')->with('toastData', ['success' => true, 'text' => 'Berhasil menghapus!']);
        }

        else {
            return redirect()->route('user-settings-active')->with('toastData', ['success' => false, 'text' => 'Gagal menghapus!']);
        }
    }
}
