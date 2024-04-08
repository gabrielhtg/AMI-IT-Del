<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\TipeLaporan;
use App\Models\User;
use App\Services\AllServices;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function getHalamanRoleManagement() {


        $data = [
            'active_sidebar' => [7, 0],
            'roles' => RoleModel::all(),
            'tipe_dokumen' => TipeLaporan::all()
        ];

        return view('role-management', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * Method ini digunakan untuk menambahkan role baru
     */
    public function addRole(Request $request)
    {
        $informableTo = null;
        $accountableTo = null;
        $laporan = null;

        if ($request->informable_to !== null) {
            $informableTo = implode(';', $request->informable_to);
        }

        if ($request->accountable_to !== null) {
            $accountableTo = implode(';', $request->accountable_to);
        }

        if ($request->wajib_melaporkan !== null) {
            $laporan = implode(";", $request->wajib_melaporkan);
        }

        try {
            RoleModel::create([
                'role' => $request->nama_role,
                'atasan_id' => $request->atasan_role,
                'responsible_to' => AllServices::getResponsibleTo($request->atasan_role),
                'informable_to' => $informableTo,
                'status' => true,
                'accountable_to' => $accountableTo,
                'is_admin' => $request->is_admin,
                'required_to_submit_document' => $laporan
            ]);

            if ($request->atasan_role != null) {
                $atasan = RoleModel::find($request->atasan_role);

                if ($atasan->bawahan == null) {
                    $atasan->update([
                        'bawahan' => RoleModel::whereRole($request->nama_role)->first()->id
                    ]);
                } else {
                    $atasan->update([
                        'bawahan' => $atasan->bawahan . ';' . RoleModel::whereRole($request->nama_role)->first()->id
                    ]);
                }
            }
            return back()->with('toastData', ['success' => true, 'text' => 'Role ' . $request->nama_role . ' berhasil ditambahkan!']);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->with('toastData', ['success' => false, 'text' => 'Role ' . $request->nama_role . ' gagal untuk ditambahkan! Role sudah pernah ditambahkan sebelumnya.']);
            }

            return back()->with('toastData', ['success' => false, 'text' => 'Role ' . $request->nama_role . ' gagal untuk ditambahkan!']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * Method ini digunakan untuk menghapus role.
     * Jika role yang akan dihapus masih ada user yang aktif dengan user tersebut, maka user harus dinonaktifkan terlebih dahulu
     */
    public function removeRole(Request $request)
    {
        $localRole = RoleModel::find($request->id);
        $users = User::all();
        $newRole = "";

        foreach ($users as $e) {
            if (AllServices::isUserRole($e, $request->id)) {
                $roles = explode(";", $e->role);
                foreach ($roles as $role) {
                    if ($role == $request->id) {
                        continue;
                    }

                    else {
                        $newRole = $newRole . $role . ';';
                    }
                }

                $e->update([
                    'role' => substr($newRole, 0, -1)
                ]);

                $newRole = '';
            }
        }

        $localRole->delete();
        return back()->with('toastData', ['success' => true, 'text' => 'Berhasil menghapus role!']);
    }

    public function updateStatus (Request $request) {
        $role = RoleModel::find($request->id);
        $users = User::all();
        $allRole = RoleModel::all();

        foreach ($users as $user) {
            if (AllServices::isUserRole($user, $request->id)) {
                return back()->with('toastData', ['success' => false, 'text' => 'Gagal menggati status role. Pastikan tidak ada user active dengan role tersebut!']);
            }
        }

        if ($role->bawahan !== null) {
            if (!AllServices::isAdaBawahanActive($role->bawahan)) {
                return back()->with('toastData', ['success' => false, 'text' => 'Gagal menggati status role. Pastikan tidak ada role aktif yang menjadi anggota dari role ini!']);
            }
        }

        if ($role->atasan_id !== null) {
            if (!RoleModel::find($role->atasan_id)->status) {
                return back()->with('toastData', ['success' => false, 'text' => 'Gagal menggati status role. Pastikan role atasan aktif!']);
            }
        }

        foreach ($allRole as $e) {
            if (AllServices::isThisRoleExistInArray($e->accountable_to, $request->id)) {
                return back()->with('toastData', ['success' => false, 'text' => 'Gagal menggati status role. Pastikan tidak ada role yang accountable to role ini!']);
            }

            if (AllServices::isThisRoleExistInArray($e->informable_to, $request->id)) {
                return back()->with('toastData', ['success' => false, 'text' => 'Gagal menggati status role. Pastikan tidak ada role yang informable to role ini!']);
            }
        }

        $currentRoleStatus = $role->status;

        $role->update([
            'status' => !$currentRoleStatus
        ]);

        return back()->with('toastData', ['success' => true, 'text' => 'Berhasil mengganti status role!']);
    }

    public function editRole (Request $request){
        $role = RoleModel::find($request->id);

        $informableTo = null;
        $accountableTo = null;

        if ($request->informable_to !== null) {
            $informableTo = implode(';', $request->informable_to);
        }

        if ($request->accountable_to !== null) {
            $accountableTo = implode(';', $request->accountable_to);
        }

        if ($request->atasan_role != null) {
            $atasanBaru = RoleModel::find($request->atasan_role);
            $atasanLama = RoleModel::find($role->atasan_id);

            if ($atasanLama != null) {
                $atasanLama->update([
                    'bawahan' => AllServices::removeIdFromArray($atasanLama->bawahan, $role->id)
                ]);
            }

            if ($atasanBaru->bawahan == null) {
                $atasanBaru->update([
                    'bawahan' => $role->id
                ]);
            }

            else {
                $atasanBaru->update([
                    'bawahan' => $atasanBaru->bawahan . ";" . $role->id
                ]);
            }
        }

        $role->update([
            'role' => $request->nama_role,
            'atasan_id'=>$request->atasan_role,
            'responsible_to' => AllServices::getResponsibleTo($request->atasan_role),
            'accountable_to'=> $accountableTo,
            'informable_to' => $informableTo
        ]);


        return back()->with('toastData', ['success' => true, 'text' => 'Berhasil memperbarui role!']);
    }
}
