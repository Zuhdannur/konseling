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

    public function all()
    {
        $data = $this->sekolah->all()->with('detailUser');
        return Response::json($data, 200);
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
            return Response::json(["message" => '.'], 201);
        }
    }


}
