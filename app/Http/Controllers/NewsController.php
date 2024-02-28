<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function index(){
            // mengambil data dari table pegawai
        $news = DB::table('news')->paginate(10);
        
            // mengirim data pegawai ke view index
        // return view('full-page-news',['news' => $news]);
        return view('full-page-news', [
            'news' => $news,
            // 'fileSizeInKB' => $fileSizeInKB,
            // 'fileSizeInMB' => $fileSizeInMB,
            // 'loggedInUserName' => $loggedInUserName,
        ]);
    }

    public function getNews()
    {

        $news = News::all()->sortByDesc('id');

        $data = [
            'news' => $news
        ];

        return view('news', $data);
    }

    public function getnewspage()
    {
        // if ($request->has('search')) {
        //     $guestNews = News::where('judul', 'LIKE', '%' . $request->search . '%')->get();
        // } else {
        //     $guestNews = News::all()->sortByDesc('id');
        // }
    
        // $data = [
        //     'guestNews' => $guestNews  // Perbaikan nama variabel disini
        // ];
        $news = News::all()->sortByDesc('id');

        $data = [
            'news' => $news
        ];

        // return view('news', $data);  
    
        return view('full-page-news', $data);
    }

    public function carinews(Request $request){
        $carinews = $request->carinews;

        $news =  DB::table('news')
        ->where('judul', 'like', "%" . $carinews . "%")
        ->paginate();

        return view('full-page-news',['news' => $news]);
    }
    

    public function guestNews()
    {
        $news = News::all()->sortByDesc('id'); // Mengambil semua berita dari model News

        return view('dashboard', compact('news'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isinews' => 'required',
            'gambar' => 'nullable|file|mimes:jpeg,png,jpg,pdf,docx|max:5120',
        ]);

        // Mendapatkan nama file asli

        if ($request->hasFile('gambar')) {
            // Mendapatkan nama file asli
            $gambarnews = $request->file('gambar')->getClientOriginalName();
    
            // Pindahkan file ke folder tujuan dengan nama file asli
            $request->file('gambar')->move(public_path('src/gambarnews'), $gambarnews);

            // Pindahkan file ke folder tujuan dengan nama file asli
            // $request->file('gambar')->move(public_path('src/gambarnews'), $gambarnews);  

            // Simpan data pengumuman ke dalam database
            News::create([
                'judul' => $request->judul,
                'isinews' => $request->isinews,
                'gambar' => $gambarnews,
            ]);

            return redirect('news');
        }
        else{
            return redirect('news')->with('error', 'Gagal menambahkan, Gambar harus diisi.');
        }

        
    }

    public function getDetail($id)
    {
        
        $newsdetail = News::find($id);
        // $users = auth()->user()->name;

        // Periksa apakah pengumuman ditemukan
        if (!$newsdetail) {
            // Jika tidak ditemukan, kembalikan respons dengan pesan kesalahan atau redirect ke halaman lain
            return redirect()->route('news')->with('error', 'Pengumuman tidak ditemukan.');
        }

        // Mendapatkan ukuran file
        $filePath = public_path('src/gambarnews') . '/' . $newsdetail->gambar;

        $fileSize = filesize($filePath); // Ukuran file dalam byte
        $fileSizeInKB = $fileSize / 1024; // Konversi ke kilobyte
        $fileSizeInMB = $fileSizeInKB / 1024; // Konversi ke megabyte

        // Membatasi jumlah desimal menjadi 2
        $fileSizeInKB = number_format($fileSizeInKB, 2);
        $fileSizeInMB = number_format($fileSizeInMB, 2);

        $loggedInUserName = auth()->user()->name;

        // Mengirimkan data pengumuman beserta ukuran file ke tampilan
        return view('news-detail', [
            'newsDetail' =>  $newsdetail,
            'fileSizeInKB' => $fileSizeInKB,
            'fileSizeInMB' => $fileSizeInMB,
            'loggedInUserName' => $loggedInUserName,
        ]);
    }

    public function updatenews(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'judul' => 'required',
            'isinews' => 'required',
            'gambar' => 'nullable|file|mimes:jpeg,png,jpg,pdf,docx|max:5120',
        ]);

        // Cari data pengumuman yang akan diupdate
        $data = News::find($id);

        if (!$data) {
            return redirect()->route('news')->with('error', 'Pengumuman tidak ditemukan.');
        }

        // Jika ada file baru yang diunggah, lakukan proses penghapusan dan pembaruan file
        if ($request->hasFile('gambar')) {
            $fileAncPath = public_path('src/gambarnews/') . $data->gambar;

            // Hapus file lama jika ada
            if (File::exists($fileAncPath)) {
                File::delete($fileAncPath);
            }

            // Simpan file yang baru diunggah
            $gambarnews = $request->file('gambar')->getClientOriginalName();
            $request->file('gambar')->move(public_path('src/gambarnews'), $gambarnews);

            // Update data dengan file yang baru
            $data->gambar = $gambarnews;
        }

        // Update data pengumuman dengan informasi yang baru
        $data->judul = $request->judul;
        $data->isinews = $request->isinews;
        $data->save();

        return redirect()->route('news')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function deletenews($id)
    {
        $news = News::find($id);

        $fileAncPath = public_path('src/gambarnews/') . $news->file;

        if (File::exists($fileAncPath)) {
            File::delete($fileAncPath);
        }
        $news->delete();

        return redirect('news');
    }
}
