<?php


namespace App\Repositories;


use App\DetailUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class UsersRepository
{

    private $user, $detailUser;

    /**
     * UsersRepository constructor.
     * @param $user
     * @param $detailUser
     */
    public function __construct(User $user, DetailUser $detailUser)
    {
        $this->user = $user;
        $this->detailUser = $detailUser;
    }


    public function register(Request $request)
    {
        if ($this->isUsernameExists($request->username)) {
            return Response::json([
                'message' => 'Duplicate Username'
            ], 201);
        }

        $insert = $this->user;
        $insert->name = $request->name;
        $insert->username = $request->username;
        $insert->password = Hash::make($request->password);
        $insert->role = $request->role;
        $insert->avatar = $request->avatar;
        $insert->save();

        if ($request->id_sekolah != null) {
            $insertDetail = $this->detailUser;
            $insertDetail->id_user = $this->user->id;
            $insertDetail->jenkel = $request->jenkel;
            $insertDetail->alamat = $request->alamat;
            $insertDetail->nomor_hp = $request->nomor_hp;
            $insertDetail->kelas = $request->kelas;
            $insertDetail->id_sekolah = $request->id_sekolah;
            $insertDetail->kota = $request->kota;
            $insertDetail->tanggal_lahir = $request->tanggal_lahir;
            $insertDetail->kota_lahir = $request->kota_lahir;
            $insertDetail->save();
        }

        return Response::json([
            'message' => 'register successfully'
        ], 200);
    }

    public function getTotalAccountBySchool(Request $request) {
        $idSekolah = $request->id_sekolah;

        $data = $this->user->where('role', '!=','admin')
            ->withAndWhereHas('detail', function($data) use ($idSekolah) {
                $data->where('id_sekolah', $idSekolah);
            });

        if($request->has('role')) {
            $data = $data->where('role', $request->role);
        }

        $data = $data->count();

//        $query = \App\User::where('role', 'guru')->withAndWhereHas('detail', function ($query) {
//            //     $query->where('id_sekolah', Auth::user()->detail->id_sekolah);
//            // })->get();



        return Response::json([
            'total' => $data
        ], 200);
    }

    private function getLastID()
    {
        return $this->user->orderBy('id', 'desc')->first();
    }

    public function login(Request $request)
    {
        $user = $this->isUsernameExists($request->username);

        if (!$user) {
            return Response::json([
                'message' => 'Akun tidak ditemukan.'
            ], 201);
        }

        if (!Hash::check($request->password, $user->password)) {
            return Response::json([
                "message" => 'Username atau kata sandi salah.',
            ], 201);
        }

        $apiKey = base64_encode(str_random(40));
        $this->user->where('username', $request->username)->update([
            'api_token' => $apiKey,
            'firebase_token' => $request->firebase_token
        ]);

        if ($user->role == 'siswa') {
            $data = $this->user->where('api_token', $apiKey)->with('detail', 'detail.sekolah')->first();
        } else {
            $data = $this->user->where('api_token', $apiKey)->with('detail', 'detail.sekolah')->first();
            $this->addTopic($data);
        }

        return Response::json([
            "message" => 'success',
            "api_token" => $apiKey,
            "role" => $user->role,
            "data" => $data
        ], 200);
    }


    public function changePassword(Request $request)
    {
        $user = $this->user->find(Auth::user()->id);

        if (!Hash::check($request->oldPassword, $user->password)) {
            return Response::json(
                ["message" => "Kata sandi saat ini salah."],
                201);
        }

        if (Hash::check($request->newPassword, $user->password)) {
            return Response::json(
                ["message" => "Kata sandi baru tidak boleh sama dengan kata sandi saat ini."],
                201);
        }

        $user->password = Hash::make($request->newPassword);
        $save = $user->save();

        $updateHasEver = $user->update([
            'hasEverChangePassword' => 1
        ]);

        if (!$save || !$updateHasEver) {
            return Response::json(
                ["message" => "Gagal mengganti kata sandi."],
                201);
        }

        return Response::json(["message" => "Kata sandi berhasil diubah."], 200);
    }

    public function get($id)
    {
        if (Auth::user()->role == 'siswa') {
            $data = $this->user->where('api_token', $id)->with('detail', 'detail.kelas', 'detail.sekolah')->first();
        } else {
            $data = $this->user->where('api_token', $id)->with('detail', 'detail.sekolah')->first();
            $this->addTopic($data);
        }

        return Response::json($data, 200);
    }

    private function addTopic($data)
    {
        // $client = new Client();
        // $client->setApiKey(self::$API_ACCESS_KEY);
        // $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

        // $query = \App\User::where('role', 'guru')->withAndWhereHas('detail', function ($query) {
        //     $query->where('id_sekolah', Auth::user()->detail->id_sekolah);
        // })->get();

        // $pattern = "guru";

        // foreach ($query as $value) {
        //     $client->addTopicSubscription($pattern.$value['detail']['id_sekolah']."pengajuan", $value['firebase_token']);
        // }
    }

    public function getTotalAccount(Request $request)
    {
        $data = $this->user
            ->where('role', $request->role)
            ->count();

        return Response::json([
            'total' => $data
        ], 200);
    }

    public function checkUsername($username)
    {
        $check = $this->user->where('username', $username)->first();
        if ($check) {
            return Response::json(['message' => 'Username telah terdaftar.'], 201);
        }
        return Response::json(['message' => 'Username dapat digunakan.'], 200);
    }


    private function isUsernameExists($username)
    {
        $check = $this->user->where('username', $username)->first();
        if (!$check) {
            return null;
        }
        return $check;
    }

    public function all()
    {
        $data = $this->user->with('detail', 'detail.kelas', 'detail.sekolah')->get();
        return Response::json($data, 200);
    }

    public function remove($id)
    {
        $data = $this->user->find($id)->delete();
        $detail = $this->detailUser->find($id)->delete();
        return Response::json([
            "message" => "success",
        ], 200);
    }

    public function put(Request $request)
    {

        $update = $this->user->where('id', Auth::user()->id)->first();
        $updateDetailUser = $this->detailUser->where('id_user', Auth::user()->id)->first();

        $update = $update->fill($request->input())->save();
        $updateDetailUser = $updateDetailUser->fill($request->input())->save();

        if (!$update && !$updateDetailUser) {
            return Response::json(['message' => 'Gagal menyunting profils.']);
        }

        return Response::json(["message" => 'Profil berhasil disunting.'], 200);

//        $update = $this->user->find(Auth::user()->id)->update([
//            'name' => $request->name
//        ]);
//
//        if (Auth::user()->role == 'siswa') {
//            if(!$update) {
//                return Response::json([
//                    "message" => 'nama siswa atau nama kelas tidak ditemukan'
//                ], 201);
//            }
//
//            $update_detail = $this->detailUser->where('id_user', Auth::user()->id)->update([
//                'alamat' => $request->alamat,
//                'nomor_hp' => $request->nomor_hp,
//                'kelas' => $request->kelas,
//                'jenkel' => $request->jenkel
//            ]);
//
//            if (!$update_detail) {
//                return Response::json(['message' => 'Gagal menyunting profil.']);
//            }
//
//            return Response::json(["message" => 'Profil berhasil disunting.'], 200);
//        } else {
//            //Nama, Jenkel, Alamat, No HP
//            $update = $this->detailUser->where('id_user', Auth::user()->id)->update([
//                'jenkel' => $request->jenkel,
//                'alamat' => $request->alamat,
//                'nomor_hp' => $request->nomor_hp
//            ]);
//
//            if (!$update) {
//                return Response::json(['message' => 'Gagal menyunting profil.']);
//            }
//            return Response::json(["message" => 'Profil berhasil disunting.'], 200);
//        }
//
//        return $request;
    }

    public function getStudentInfo($id)
    {
        $data = $this->user->where('id', $id)->with('detail', 'detail.sekolah')->first();
        return Response::json($data, 200);
    }

    public function updateImageProfile(Request $request)
    {
        $image = $this->user->find(Auth::user()->id);

        if (!$image) {
            return Response::json([
                "message" => "Failed to update"
            ], 201);
        }

        $image->avatar = $request->avatar;
        $image->save();

        return Response::json([
            "message" => "Success to update"
        ], 200);
    }

}
