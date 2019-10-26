<?php namespace App\Http\Controllers;

use App\Repositories\UsersRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UsersController extends Controller
{
    protected static $API_ACCESS_KEY = 'AAAA_vRurwA:APA91bFvUdoT1ruL0WZC3rkvQWoK76WFOgUSAFuc3aUpN0_kjiP22y3Pf_o1TthpfN6_o_0HnHJeMGZMp8MqHzm1zTCk8zuTY4UzAByzknPDlcBlNFvz60oN6fx9Kq3gkfR373aboRy0';

    private $userRepository;

    /**
     * UsersController constructor.
     * @param $userRepository
     */
    public function __construct(UsersRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(Request $request)
    {
        return $this->userRepository->login($request);
    }

    public function checkUsername(Request $request) {
        return $this->userRepository->checkUsername($request->username);
    }

    public function register(Request $request)
    {
        return $this->userRepository->register($request);
    }

    public function getTotalAccount(Request $request) {
        return $this->userRepository->getTotalAccount($request);
    }

    public function get($id)
    {
        return $this->userRepository->get($id);
    }

    public function all()
    {
        return $this->userRepository->all();
    }

    public function remove($id)
    {
        return $this->userRepository->remove($id);
    }

    public function put(Request $request)
    {
        return $this->userRepository->put($request);
    }

    public function getStudentInfo($id)
    {
        return $this->userRepository->getStudentInfo($id);
    }

    public function updateImageProfile(Request $request)
    {
        return $this->userRepository->updateImageProfile($request);
    }

    public function changePassword(Request $request) {
        return $this->userRepository->changePassword($request);
    }

}
