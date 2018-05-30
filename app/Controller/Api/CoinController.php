<?php

namespace App\Controller\Api;

use App\Helpers\CoinHelpers;
use App\Leven;
use App\Model\Chat;
use App\Model\Order;
use App\Service\ChatService;
use Carbon\Carbon;
use Leven\Auth;
use Leven\Log;

use Slim\Http\Request;
use Slim\Http\Response;

class CoinController extends Controller
{

    use CoinHelpers;



    public function price (Request $request,Response $response){
        $coins=CoinHelpers::get();
        $assets=[];
        foreach ($coins as $coin) {
             $coin["price"]=0.2;
             $assets[]=$coin;
        }
        return $this->json($response,$assets);

    }



}
