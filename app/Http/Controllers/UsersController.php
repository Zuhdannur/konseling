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
    const MODEL = "App\User";
    static protected $API_ACCESS_KEY = 'AAAA_vRurwA:APA91bGd7ayeeU2Nlb5D0T1DwRc48CzU-G_ez4SM_qIgdGv-wpQvuUhbJ3xbUFmJZOPtr_EVe_vB2z38O4CUjJPY-WcapZb-Xy_Y1rC3B-v-AFIIQsRxMPJi6pZY8jX1k1eytQSdiXiW';

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
                return Response::json([
                    "message"   => 'success',
                    "api_token" => $apiKey,
                    "role"      => $user->role,
                ], 200);
            } else {
                return Response::json([
                    "message" => 'wrong password',
                ], 201);
            }
        } else {
            return Response::json([
                'message' => 'Username Not Found'
            ], 201);
        }
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

            $insertDetail->id_kelas = $request->id_kelas;
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

    public function get($id)
    {
        // if (Auth::user()->role == 'siswa') {
        //     $data = \App\User::where('id', $id)->with('detail', 'detail.kelas', 'detail.sekolah')->first();
        // } else {
        //     $data = \App\User::where('id', $id)->with('detail', 'detail.sekolah')->first();
        //     // $this->addTopic($data);
        // }

        $client = new Client();
        $client->setApiKey(self::$API_ACCESS_KEY);
        $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

        // $query = \App\User::where('role','guru')->whereHas('detail', function($q) {
        //     $q->with('sekolah')->where('id_sekolah', Auth::user()->detail->id_sekolah);
        // })->get();
        // $query = \App\User::where('role','guru')->whereHas('detail', function ($q){
        //     $q->with('detail');
        //     $q->where('id_sekolah', 1);
        // })->get();
        $query = \App\User::where('role','guru')->withAndWhereHas('detail', function($query) {
            $query->where('id_sekolah', Auth::user()->detail->id_sekolah);
        })->get();
        return Response::json($query, 200);
    }

    private function addTopic($data) {
        $client = new Client();
        $client->setApiKey(self::$API_ACCESS_KEY);
        $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

        $query = \App\User::with('detail', 'detail.sekolah')->where('role','guru')->where('id_sekolah', Auth::user()->detail->id_sekolah)->get();

        // $query = \App\User::where(function ($query){
        //     $query->where('role',"guru");
        //     $query->whereHas('detail',function ($q){
        //         $q->where('id_sekolah', Auth::user()->detail->id_sekolah);
        //     });
        // })->get();
        // foreach ($query as $value){
        //     dd($value->detail->id_sekolah);
        //     // $client->addTopicSubscription($value->detail->id_sekolah, $value['firebase_token']);
        // }


        // $client = new Client();
        // $client->setApiKey(self::$API_ACCSESS_KEY);
        // $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

        // $query = \App\User::where(function ($query) {
        //     $query->where('role',"guru");
        //     $query->whereHas('detail', function ($q){
        //         $q->where('id_sekolah', Auth::user()->detail->id_sekolah);
        //     });
        // })->get();

        // $getSchoolId = \App\School::where('school_name',Auth::user()->detail->school)->first()->id;
        // // $users = [];
        // $pattern = "guru_". Auth::user()->detail->id_sekolah;
        // $client->addTopicSubscription($pattern, $value['firebase_token']);
        // foreach ($query as $value){
        //     $pattern = "guru_". $id;
        //     $client->addTopicSubscription($getSchoolId, $value['firebase_token']);
        // }
        return Response::json([
            "data" => $query
        ], 200);
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

        if(Auth::user()->role == 'siswa') {
            $kelasId = \App\Kelas::where('nama_kelas', $request->nama_kelas)->first()->id;
            if ($update && $kelasId) {
                $update_detail = \App\DetailUser::where('id_user', Auth::user()->id)->update([
                    'address' => $request->address,
                    'phone_number' => $request->phone_number,
                    'id_kelas' => $kelasId,
                    'gender' => $request->gender
                ]);

                if ($update_detail) {
                    return Response::json([
                        "message" => 'profile Updated'
                    ], 200);
                } else {
                    return Response::json([
                        "message" => 'failed to Updated'
                    ], 201);
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

            if($update) return Response::json(["message" => 'Profil berhasil disunting.'], 200);
            else return Response::json(['message' => 'Gagal menyunting profil.']);
        }
        
        
        return $request;
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
