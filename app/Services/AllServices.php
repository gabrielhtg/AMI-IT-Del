<?php

namespace App\Services;

use App\Models\DocumentModel;
use App\Models\RoleModel;
use Illuminate\Support\Carbon;


class AllServices
{
    /**
     * @param $role
     * @return string
     *
     * Method ini berfungsi untuk mengonversikan role ke dalam string berdasarkan id role tersebut.
     * Method ini juga dapat digunakan untuk mengonversikan role ke dalam string apabila id role
     * tersebut berada dalam format id;id
     */
    static public function convertRole ($role): string
    {
        if ($role) {
            $roles = explode(";", $role);

            $output = '';

            $len = count($roles);
            $i = 0;

            foreach ($roles as $e) {
                $output = $output . trim(RoleModel::find($e)->role);

                if ($i != $len - 1) {
                    $output = $output . ', ';
                }

                $i++;
            }

            return $output;
        }

        else {
            return "Not Defined Yet";
        }
    }

    /**
     * @param $time
     * @return string
     *
     * Method ini berfungsi untuk mengonversikan waktu ke format
     * seperti berikut ini Fri, 08 Mar 2024
     */
    static public function convertTime ($time): string
    {
        $carbonObject = Carbon::createFromFormat('Y-m-d H:i:s', $time);

        return $carbonObject->format('D, d M Y');
    }

    /**
     * @param $time
     * @return string
     *
     * Method ini berfungsi untuk menampilkan sudah berapa lama si user ini login.
     * Cara kerjanya adalah dengan megurangkan waktu sekarang dengan last login at
     */
    static public function getLastLogin ($time) : string {
        $carbonObject = Carbon::createFromFormat('Y-m-d H:i:s', $time);

        $diffInMinutes = $carbonObject->diffInMinutes(Carbon::now());
        $diffInHours = $carbonObject->diffInHours(Carbon::now());
        $diffInDays = $carbonObject->diffInDays(Carbon::now());

        if ($diffInMinutes < 60) {
            return "$diffInMinutes mnts ago";
        } elseif ($diffInHours < 24) {
            $diffInMinutes = $diffInMinutes % 60;
            return "$diffInHours hrs, $diffInMinutes mnts ago";
        } else {
            $diffInHours = $diffInHours % 24;
            return "$diffInDays days, $diffInHours mnts ago";
        }
    }

    /**
     * @param $status
     * @return string
     *
     * Method ini berfungsi untuk mengonversikan status user apakah dia masih aktif
     * atau tidak aktif lagi dan akan mengembalikan string.
     */
    static public function convertStatus($status) {
        if ($status !== null) {
            if ($status == true) {
                return "Active";
            }

            else {
                return "Inactive";
            }
        }

        return "Inactive";
    }

    /**
     * @return bool
     *
     * Ini adalah fungsi yang memiliki parameter role untuk
     * memeriksa apakah role user yang saat ini login adalah user
     * yang memiliki role sesuai dengan apa yang dimasukkan pada
     * parameter.
     */
    static public function isCurrentRole ($role): bool
    {
        $roles = explode(";", auth()->user()->role);

        foreach ($roles as $e) {
           try {
               if (strtolower(RoleModel::find($e)->role) == strtolower($role)) {
                   return true;
               }
           } catch (\ErrorException $e) {
               return false;
           }
        }

        return false;
    }

    static public function isRoleExist($role) : bool {
        $rolemodel = RoleModel::whereRaw('LOWER(role) = ?', strtolower($role))->first();

        if ($rolemodel != null) {
            return true;
        }

        return false;
    }

    static public function isUserRole ($user, $expectedRole): bool
    {
        $roles = explode(";", $user->role);
        $expectedRoles = explode(";", $expectedRole);

        foreach ($expectedRoles as $e) {
            if (in_array($e, $roles)) {
                return true;
            }
        }

        return false;
    }

    static public function isAdmin (): bool
    {
        if (RoleModel::find(auth()->user()->role)->role == "Admin") {
            return true;
        }

        return false;

        // dump(RoleModel::find(auth()->user()->role)->role == "Admin");
        // sleep(10);
    }

    static public function dokumenchange($id) {
        if ($id) {
            $document = DocumentModel::find($id); // Mengambil dokumen berdasarkan ID
            if ($document) {
                return $document->nama_dokumen; // Mengembalikan nama dokumen jika ditemukan
            } else {
                return "Tidak Menggantikan Dokumen Apapun"; // Mengembalikan pesan jika dokumen tidak ditemukan
            }
        } else {
            return "Tidak Menggantikan Dokumen Apapun"; // Mengembalikan pesan jika ID dokumen kosong atau null
        }
    }
    
    

    static public function isAllView ($id) : bool
    {

        if (DocumentModel::find($id)->give_access_to == 0) {
            return true;
        }
        return false;
    }

    static public function getResponsibleTo ($idAtasan) : ?string {
        if($idAtasan != null) {
            $nextRole = $idAtasan;
            $responsibleTo = strval($idAtasan);

            while (true) {
                $visitedRole = RoleModel::find($nextRole);
                $nextRole = $visitedRole->atasan_id;

                if ($nextRole == null) {
                    break;
                }

                $responsibleTo = $responsibleTo . ';' . $nextRole;
            }
            return $responsibleTo;
        }

        return null;
    }

}
