<?php namespace App\Http\Controllers;

use App\Repositories\SekolahRepository;
use Illuminate\Http\Request;

class SekolahsController extends Controller
{

    private $sekolahRepository;

    /**
     * SekolahsController constructor.
     * @param $sekolahRepository
     */
    public function __construct(SekolahRepository $sekolahRepository)
    {
        $this->sekolahRepository = $sekolahRepository;
    }

    public function getDataThisMonth() {
        return $this->sekolahRepository->getDataThisMonth();
    }


    public function all()
    {
        return $this->sekolahRepository->all();
    }

    public function get($id)
    {
        return $this->sekolahRepository->get($id);
    }

    public function add(Request $request)
    {
        return $this->sekolahRepository->add($request);
    }

    public function put($id, Request $request)
    {
        return $this->sekolahRepository->put($id, $request);
    }

    public function remove($id)
    {
        return $this->sekolahRepository->remove($id);
    }
}
