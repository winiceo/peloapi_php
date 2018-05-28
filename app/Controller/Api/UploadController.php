<?php

namespace App\Controller\Api;


use App\Leven;
use App\Model\Chat;
use App\Model\Order;
use App\Service\ChatService;
use Carbon\Carbon;
use Leven\App;
use Leven\Auth;
use Leven\Log;
use Leven\View;
use Pusher\Pusher;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

class UploadController extends Controller
{

    public function index(Request $request, Response $response){
        echo exec('whoami');
        dump($request->getUri()->getBaseUrl());
        View::render($response,'upload/upload.html.twig');
    }

    public function upload(Request $request, Response $response)
    {
        $uploadedFiles = $request->getUploadedFiles();
        $dir = App::getRootDir() . '/public/upload';

        $order_id = $request->getParam('orderId') ;


        // handle single input with single file upload
        $uploadedFile = $uploadedFiles['chatUpload'];
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = $this->moveUploadedFile($dir, $uploadedFile);
            $url=$request->getUri()->getBaseUrl()."/upload/".$filename;
            ChatService::setImageMessage($url);
           return $this->setData(["url"=>$url])->success();
           // $response->write('uploaded ' . $filename . '<br/>');
        }

    }

    public function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }


}
