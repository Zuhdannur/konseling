<?php


namespace App\Repositories;


use App\Sekolah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SekolahRepository
{
    private $sekolah;

    /**
     * SekolahRepository constructor.
     * @param $sekolah
     */
    public function __construct(Sekolah $sekolah)
    {
        $this->sekolah = $sekolah;
    }

    public function getDataThisMonth() {
        $data = $this->sekolah
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        return Response::json([
            'total' => $data
        ], 200);
    }

    public function getSekolahCount() {
        $data = $this->sekolah->count();

        return Response::json([
            'total' => $data
        ], 200);
    }

    public function all(Request $request)
    {
        $per_page = $request->per_page;

        $data = $this->sekolah->with('user');

        $data = $data->orderBy('created_at', 'desc');
        $data = $data->paginate($per_page);

        return Response::json($data, 200);
    }

    public function getSekolahThenCheckAdmin(Request $request) {

        /*Tampilkan sekolah yang belum dikelola oleh admin*/
        $data = $this->sekolah->whereHas('user', function ($query) {
           $query->whereNotIn('role',['admin']);
        });

        $notManagingByAdmin = $data->count() == 0;

//        $data = $this->sekolah->with('user')->get();

        $isEmpty = !$this->sekolah->exists();

        if($notManagingByAdmin) {
            $data = $this->sekolah->doesntHave('user')->get();
        } else {
            $data = $data->with('user')->get();
        }

        /*Cek apabila ada sekolah yang sudah dikelola oleh admin*/
        $checkIsAnyManagingByAdmin = $this->sekolah->whereHas('user', function($query) {
            $query->where('role', 'admin');
        })->exists();

//        $condition = '';
//        if($isEmpty) {
//            //Data sekolah masih kosong
//            $condition = 'empty';
//        } else if($checkIsAnyManagingByAdmin && $data->count == 0) {
//            $condition = 'full';
//        } else {
//            $condition = 'fillable';
//        }

        return Response::json([
            'data' => $data
        ], 200);
    }

    public function get($id)
    {
        $data = $this->sekolah->find($id);
        return Response::json($data, 200);
    }

    private function isSekolahExists($namaSekolah)
    {
        $check = $this->sekolah->where('nama_sekolah', $namaSekolah)->first();
        if (!$check) {
            return null;
        }
        return $check;
    }

    public function checkSekolahName($namaSekolah) {
        $check = $this->sekolah->where('nama_sekolah', $namaSekolah)->first();
        if($check) {
            return Response::json(['message' => 'Sekolah telah terdaftar.'], 201);
        }
        return Response::json(['message' => 'Sekolah dapat digunakan.'], 200);
    }

    public function add(Request $request)
    {
        if($this->isSekolahExists($request->nama_sekolah)) {
            return Response::json([
                'message' => 'Gagal, sekolah telah terdaftar di server.'
            ], 201);
        }
        $this->sekolah->nama_sekolah = $request->nama_sekolah;
        $this->sekolah->alamat = $request->alamat;
        $this->sekolah->save();

        return Response::json([
            'message' => 'Berhasil mendaftarkan sekolah.',
            'id' => $this->sekolah->id
        ], 200);
    }

    public function put($id, Request $request)
    {
        $update = $this->sekolah->find($id)->update([
            "nama_sekolah" => $request->nama_sekolah,
            "alamat" => $request->alamat
        ]);
        if ($update) {
            return Response::json([ "message" => "berhasil menyunting." ], 200);
        } else {
            return Response::json([ "message" => "gagal menyunting." ], 201);
        }
    }

    public function remove($id)
    {
        $delete = $this->sekolah->find($id)->delete();
        if ($delete) {
            return Response::json(["message" => 'Sekolah berhasil dihapus.'], 200);
        } else {
            return Response::json(["message" => 'Sekolah gagal dihapus'], 201);
        }
    }


}
