<?php namespace App\Http\Controllers;

use App\Classes\Kraken;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{

    public function login(Request $request)
    {
        $user = $this->checking($request->username);
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $apiKey = base64_encode(str_random(40));
                \App\User::where('username', $request->username)->update([
                    'api_token' => $apiKey
                ]);
                return Response::json([
                    "message"   => 'success',
                    "api_token" => $apiKey,
                    "role"      => $user->role,
                ],200);
            } else {
				return Response::json([
                    "message" => 'wrong password',
                ],201);
			}
        } else {
            return Response::json([
                'message' => 'Username Not Found'
            ],201);
        }
    }

    public function register(Request $request)
    {

        if (!$this->checking($request->username)) {
            $insert = new \App\User;
            if ($request->file('photo') != null) {
                $image = $request->file('photo');
                $realpath = $request->file('photo')->getRealPath();
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $path = base_path() . '\\public\\image\\';
//                $path = public_path('images/'.$filename);
                $image->move($path, $filename);
                $insert->avatar = $filename;
            } else {
                $filename = 'default.png';
                $insert->avatar = $filename;
            }

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
            $insert->save();

            $insertDetail = new \App\DetailUser;
            $insertDetail->id_user = $this->getLastID()->id;
            $insertDetail->gender = $request->gender;
            $insertDetail->address = $request->address;
            $insertDetail->phone_number = $request->phone;
            $insertDetail->kelas = $request->kelas;
            $insertDetail->school = $request->school;
            $insertDetail->save();

            if ($insertDetail) {
                return Response::json([
                    'message' => 'register successfully',
                ],200);
            } else {
                return Response::json([
                    'message' => 'register failed'
                ],400);
            }
        } else {
            return Response::json([
                'message' => 'Duplicate Username'
            ],201);
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
        } else return null;
    }

    public function getMyProfile()
    {
        $data = \App\User::where('id', Auth::user()->id)->with('detail');
        $data['avatar'] = base_path() . '\\public\\image\\' . $data->avatar;
        return Response::json([
            "message" => "success",
            "result" => $data
        ],200);
    }
	
	public function getAllUser()
	{
		$data = \App\User::all();
        return Response::json([
            "message" => "success",
            "result" => $data
        ],200);
	}
	
	 public function destroy($id)
    {
        $data = \App\User::where('id', $id)->delete();
		$detail = \App\DetailUser::where('id_user', $id)->delete();
        return Response::json([
            "message" => "success",
        ],200);
    }

    public function updateProfile(Request $request)
    {
        $update = \App\User::find(Auth::user()->id)->update([
            'name' => $request->name
        ]);
        if ($update) {
            if ($request->kelas == null || $request->school == null) {
                $kelas = '';
                $school = '';
            } else {
                $kelas = $request->kelas;
                $school = $request->school;
            }
            $update_detail = \App\DetailUser::where('id_user', Auth::user()->id)->update([
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'kelas' => $kelas,
                'school' => $school
            ]);

            if($update_detail){
                return Response::json([
                    "message" => 'profile Updated'
                ],200);
            } else {
                return Response::json([
                    "message" => 'failed to Updated'
                ],201);
            }
        }
        return $request;
    }

}
