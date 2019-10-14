<?php namespace App\Http\Controllers;

use App\Classes\Kraken;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Notification;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Topic;

class UsersController extends Controller
{
    protected static $API_ACCESS_KEY = 'AAAA_vRurwA:APA91bFvUdoT1ruL0WZC3rkvQWoK76WFOgUSAFuc3aUpN0_kjiP22y3Pf_o1TthpfN6_o_0HnHJeMGZMp8MqHzm1zTCk8zuTY4UzAByzknPDlcBlNFvz60oN6fx9Kq3gkfR373aboRy0';

    public function login(Request $request)
    {
        $user = $this->checking($request->username);
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $apiKey = base64_encode(str_random(40));
                \App\User::where('username', $request->username)->update([
                    'api_token' => $apiKey,
                    'firebase_token' => $request->firebase_token
                ]);

                if ($user->role == 'siswa') {
                    $data = \App\User::where('api_token', $apiKey)->with('detail', 'detail.sekolah')->first();
                } else {
                    $data = \App\User::where('api_token', $apiKey)->with('detail', 'detail.sekolah')->first();
                    $this->addTopic($data);
                }


                return Response::json([
                    "message"   => 'success',
                    "api_token" => $apiKey,
                    "role"      => $user->role,
                    "data"      => $data
                ], 200);
            } else {
                return Response::json([
                    "message" => 'Username atau kata sandi salah.',
                ], 201);
            }
        } else {
            return Response::json([
                'message' => 'Akun tidak ditemukan.'
            ], 201);
        }
    }

    public function hasRole($role, $userid)
    {
        return \App\User::where('role', $role)->where('id', $userid)->first();
    }

    public function register(Request $request)
    {
        if (!$this->checking($request->username)) {
            $insert = new \App\User;
//             if ($request->file('photo') != null) {
//                 $image = $request->file('photo');
//                 $realpath = $request->file('photo')->getRealPath();
//                 $filename = time() . '.' . $image->getClientOriginalExtension();
//                 $path = base_path() . '\\public\\image\\';
            // //                $path = public_path('images/'.$filename);
//                 $image->move($path, $filename);
//                 $insert->avatar = $filename;
//             } else {
//                 $filename = 'default.png';
//                 $insert->avatar = $filename;
//             }

//            $kraken = new Kraken("612e57b58501cfdfcaa2493248e99f6d","1c58fdd9be2d5f87f0896197749989883d3ed324");
//
//            $params = array(
//                "file" => "C:\Users\Zuhdan Nur\Pictures\download.png",
//                "wait" => true
//            );
//            $data = $kraken->upload($params);

            $insert->name = $request->name;
            $insert->username = $request->username;
            $insert->password = Hash::make($request->password);
            $insert->role = $request->role;
            $insert->avatar = $request->avatar;
            $insert->save();

            $insertDetail = new \App\DetailUser;
            $insertDetail->id_user = $this->getLastID()->id;
            $insertDetail->gender = $request->gender;
            $insertDetail->address = $request->address;
            $insertDetail->phone_number = $request->phone;

            $insertDetail->kelas = $request->kelas;
            $insertDetail->id_sekolah = $request->id_sekolah;
            
            $insertDetail->save();

            if ($insertDetail) {
                return Response::json([
                    'message' => 'register successfully',
                    'user_id' => $insertDetail->id_user
                ], 200);
            } else {
                return Response::json([
                    'message' => 'register failed'
                ], 400);
            }
        } else {
            return Response::json([
                'message' => 'Duplicate Username'
            ], 201);
        }
    }

    public function getLastID()
    {
        return \App\User::orderBy('id', 'desc')->first();
    }

    public function checking($username)
    {
        $check = \App\User::where('username', $username)->first();
        if ($check) {
            return $check;
        } else {
            return null;
        }
    }

    public function checkRole($role)
    {
        $check = \App\User::where('username', $role)->first();
        if ($check) {
            return $check;
        }
    }

    public function get($id)
    {
        if (Auth::user()->role == 'siswa') {
            $data = \App\User::where('api_token', $id)->with('detail', 'detail.kelas', 'detail.sekolah')->first();
        } else {
            $data = \App\User::where('api_token', $id)->with('detail', 'detail.sekolah')->first();
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
    
    public function all()
    {
        $data = \App\User::with('detail', 'detail.kelas', 'detail.sekolah')->get();
        return Response::json($data, 200);
    }
    
    public function remove($id)
    {
        $data = \App\User::find($id)->delete();
        $detail = \App\DetailUser::find($id)->delete();
        return Response::json([
            "message" => "success",
        ], 200);
    }

    public function put(Request $request)
    {
        $update = \App\User::find(Auth::user()->id)->update([
            'name' => $request->name
        ]);

        if (Auth::user()->role == 'siswa') {
            if ($update) {
                $update_detail = \App\DetailUser::where('id_user', Auth::user()->id)->update([
                    'address' => $request->address,
                    'phone_number' => $request->phone_number,
                    'kelas' => $request->kelas,
                    'gender' => $request->gender
                ]);

                if ($update_detail) {
                    return Response::json(["message" => 'Profil berhasil disunting.'], 200);
                } else {
                    return Response::json(['message' => 'Gagal menyunting profil.']);
                }
            } else {
                return Response::json([
                    "message" => 'nama siswa atau nama kelas tidak ditemukan'
                ], 201);
            }
        } else {
            //Nama, Jenkel, Alamat, No HP
            $update = \App\DetailUser::where('id_user', Auth::user()->id)->update([
                'gender' => $request->gender,
                'address' => $request->address,
                'phone_number' => $request->phone_number
            ]);

            if ($update) {
                return Response::json(["message" => 'Profil berhasil disunting.'], 200);
            } else {
                return Response::json(['message' => 'Gagal menyunting profil.']);
            }
        }
        
        
        return $request;
    }

    public function getStudentInfo(Request $request)
    {
        $data = \App\User::where('api_token', $request->apiKey)->with('detail', 'detail.sekolah')->first();
        return Response::json($data, 200);
    }

    public function getStudentInfoWithId(Request $request)
    {
        $data = \App\User::where('id', $request->id)->with('detail', 'detail.sekolah')->first();
        return Response::json($data, 200);
    }

    public function updateImageProfile(Request $request)
    {
        $image = \App\User::find(Auth::user()->id);

        // Make sure you've got the Page model
        if ($image) {
            $image->avatar = $request->avatar;
            $image->save();

            return Response::json([
                "message" => "Success to update"
            ], 200);
        } else {
            return Response::json([
                "message" => "Failed to update"
            ], 201);
        }
    }
}
