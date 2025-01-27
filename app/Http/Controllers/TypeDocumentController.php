<?php

namespace App\Http\Controllers;
use App\Models\DocumentTypeModel;
use App\Models\LaporanTypeModel;
use App\Models\RoleModel;
use App\Models\JenisLaporan;
use App\Models\TipeLaporan;
use App\Models\LogLaporan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\AllServices;
use Illuminate\Support\Facades\Validator;

class TypeDocumentController extends Controller
{
    public function getDocumentType()
    {
        if (Auth::check()) {
            $documentTypes = DocumentTypeModel::all();
        } else {
            $documentTypes = DocumentTypeModel::where('give_access_to', 0)->get();
        }

        $roles = RoleModel::all();

        $data = [
            'roles' => $roles,
            'documenttype' => $documentTypes,
        ];

        return view('document-management', $data);
    }

    public function addDocumentType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_dokumen' => 'required|unique:document_types',
            'singkatan' => 'required|unique:document_types',
        ], [
            'jenis_dokumen.unique' => "Tipe dokumen sudah digunakan.",
            'singkatan.unique' => "Singkatam sudah digunakan.",
        ]);

        if (!AllServices::isLoggedUserHasAdminAccess()) {
            return redirect()->route('documentManagement')->with('toastData', ['success' => false, 'text' => 'You are not authorized to add document types.']);
        }

        if ($validator->fails()) {
            return redirect()->route('documentManagement')->with('toastData', ['success' => false, 'text' => $validator->errors()->first()]);
        }

        DocumentTypeModel::create([
            'jenis_dokumen' => $request->jenis_dokumen,
            'singkatan' => $request->singkatan,
        ]);
        AllServices::addLog(sprintf("Menambahkan Tipe Dokumen %s", $request->jenis_dokumen));

        return redirect()->route('documentManagement')->with('toastData', ['success' => true, 'text' => 'Tipe dokumen berhasil ditambahkan!']);
    }

    public function addLaporanType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_laporan' => 'required|unique:tipe_laporan',
        ], [
            'nama_laporan.unique' => "Tipe dokumen sudah digunakan.",
        ]);

        if (!AllServices::isLoggedUserHasAdminAccess()) {
            return redirect()->route('LaporanManagementAdd')->with('toastData', ['success' => false, 'text' => 'You are not authorized to add document types.']);
        }

        if ($validator->fails()) {
            return redirect()->route('LaporanManagementAdd')->with('toastData', ['success' => false, 'text' => $validator->errors()->first()]);
        }

        LaporanTypeModel::create([
            'nama_laporan' => $request->nama_laporan,

        ]);
        AllServices::addLog(sprintf("Menambahkan Tipe Laporan %s", $request->nama_laporan));

        return redirect()->route('LaporanManagementAdd')->with('toastData', ['success' => true, 'text' => 'Tipe laporan berhasil ditambahkan!']);
    }

    public function editLaporanType(Request $request, $id)
    {
        // Validasi data yang diubah
        $validator = Validator::make($request->all(), [
            'nama_laporan' => 'required|unique:tipe_laporan,nama_laporan,'.$id,

        ], [
            'nama_laporan.unique' => "Tipe dokumen sudah digunakan.",
        ]);

        // Jika validasi gagal, kembalikan dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->route('viewLaporanType', $id)->withErrors($validator)->withInput();
        }

        // Perbarui data tipe laporan
        $laporan = LaporanTypeModel::find($id);
        $laporan->nama_laporan = $request->nama_laporan;
        $laporan->save();

        AllServices::addLog(sprintf("Memperbaharui Tipe Laporan %s", $request->nama_laporan));
        // Redirect kembali ke halaman manajemen tipe laporan dengan pesan sukses
        return redirect()->route('viewLaporanType')->with('toastData', ['success' => true, 'text' => 'Tipe laporan berhasil diperbarui!']);
    }

    public function deleteLaporanType($id)
    {
        // Temukan data laporan berdasarkan ID
        $tipe_laporan = LaporanTypeModel::find($id);

        // Periksa apakah data ditemukan
        if($tipe_laporan) {
            // Jika ditemukan, hapus data
            $tipe_laporan->delete();
            AllServices::addLog(sprintf("Menghapus Tipe Laporan %s", $tipe_laporan->nama_laporan));

            // Redirect kembali dengan pesan sukses
            return redirect()->route('viewLaporanType')->with('toastData', ['success' => true, 'text' => 'Tipe laporan berhasil dihapus!']);
        } else {
            // Jika data tidak ditemukan, redirect kembali dengan pesan error
            return redirect()->route('viewLaporanType')->with('toastData', ['success' => false, 'text' => 'Tipe laporan tidak ditemukan!']);
        }
    }



    public function addLaporanJenis(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            // 'nama' => 'required',
            'year' => 'required',
            'end_date' => 'required|date',
        ], [
            // 'nama.required' => 'Nama laporan harus diisi.',
            'year.required' => 'Tahun harus diisi.',
            'end_date.required' => 'Tanggal selesai harus diisi.',
            'end_date.date' => 'Tanggal selesai harus berupa tanggal.',
        ]);
        
    
        if ($validator->fails()) {
            return redirect()->route('LaporanManagementAdd')->with('toastData', ['success' => false, 'text' => $validator->errors()->first()]);
        }
    
        // Dapatkan nama tipe laporan berdasarkan id_tipelaporan
        $tipe_laporan = TipeLaporan::find($request->id_tipelaporan);
        $nama_tipe = $tipe_laporan->nama_laporan;
        
        // Buat nama laporan dengan menambahkan nama tipe laporan
        // $nama_laporan = $nama_tipe ." ". $request->input('nama') . ' (' . $request->input('year') . ')';
    
        // Tambahkan jenis laporan baru
        $jenis_laporan = JenisLaporan::create([
            'id_tipelaporan' => $request->id_tipelaporan,
            'nama' => $request->nama,
            'year' => $request->year,
            'end_date' => $request->end_date,
        ]);
    
        $id =$jenis_laporan->id;
        
        $roles = RoleModel::where(function ($query) use ($request) {
            $id_tipelaporan = $request->id_tipelaporan;
            $id_tipelaporanArray = explode(';', $id_tipelaporan);
            
            foreach ($id_tipelaporanArray as $id) {
                $query->orWhere('required_to_submit_document', 'LIKE', "%$id%");
            }
        })->get();
       
        // Ambil semua pengguna yang memiliki peran yang sesuai dengan tipe laporan baru
        $users = User::whereIn('role', $roles->pluck('id'))->get();
        // dd($jenis_laporan->id_tipe_laporan);
        // Buat entri log laporan untuk setiap pengguna yang sesuai
        foreach ($users as $user) {
            LogLaporan::create([
                'id_jenis_laporan' => $jenis_laporan->id,
                'id_tipe_laporan'=>$request->id_tipelaporan,
                'upload_by' => $user->id,
                'create_at' => null,
                'approve_at' => null,
                'end_date'=>$jenis_laporan->end_date,
            ]);
        }

        AllServices::addLog(sprintf("Menambahkan Kategori Tipe Laporan %s(%d)", $request->nama,$request->year));
    
        return redirect()->route('LaporanManagementAdd')->with('toastData', ['success' => true, 'text' => 'Tipe laporan berhasil ditambahkan!']);
    }
    


public function updateLaporanJenis(Request $request, $id)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'nama' => 'required',
        'year' => 'required',
        'end_date' => 'required|date',
    ], [
        'nama.required' => 'Nama laporan harus diisi.',
        'year.required' => 'Tahun mulai harus diisi.',  
        'end_date.required' => 'Tanggal selesai harus diisi.',
        'end_date.date' => 'Tanggal selesai harus berupa tanggal.',
       
    ]);

    // Jika validasi gagal
    if ($validator->fails()) {
        return redirect()->route('viewLaporanJenis')->with('toastData', ['success' => false, 'text' => $validator->errors()->first()]);
    }

    // Ambil data jenis laporan berdasarkan ID
    $jenisLaporan = JenisLaporan::find($id);

    // Jika data jenis laporan tidak ditemukan
    if (!$jenisLaporan) {
        return redirect()->route('viewLaporanJenis')->with('toastData', ['success' => false, 'text' => 'Jenis laporan tidak ditemukan.']);
    }

    // Dapatkan nama tipe laporan berdasarkan id_tipelaporan
    $tipe_laporan = TipeLaporan::find($request->id_tipelaporan);
    $nama_tipe = $tipe_laporan->nama_laporan;

    // Gabungkan nama tipe laporan dengan nama jenis laporan dari form
    $nama_laporan = $request->nama;

    // Update data jenis laporan
    $jenisLaporan->update([
        'id_tipelaporan' => $request->id_tipelaporan,
        'nama' => $nama_laporan,
        'year' => $request->year,
        'end_date' => $request->end_date,
    ]);

    LogLaporan::where('id_jenis_laporan', $jenisLaporan->id)
    ->update([
        'end_date' => $request->end_date,
    ]);

    AllServices::addLog(sprintf("Memperbaharui Kategori Tipe Laporan %s(%d)", $nama_laporan,$request->year));
    return redirect()->route('viewLaporanJenis')->with('toastData', ['success' => true, 'text' => 'Jenis laporan berhasil diupdate!']);
}




}
