<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Validation\Validation;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    use ResponseTrait;
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];
    protected $session;
    protected $validation;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
    }
   
    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $this->optionsResponse();   
        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();

    }

    // 取得  Token
    protected function getCleanToken()
    {
        $authorizationHeader = $this->request->header('Authorization');
        if ($authorizationHeader !== null) {
            $token = trim(str_replace('Bearer', '', $authorizationHeader->getValue()));
        } else {
            $token = '';
        }
        return $token;
    }



    /**
     * 輸出處理
     */

     protected function optionsResponse()
     {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: *');
            header('Access-Control-Allow-Headers: X-Custom-Header, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, authorize_token');
            header('Access-Control-Max-Age: 86400');
            $this->response->setStatusCode(200);  
            exit(0);
        }
        
     }

    protected function successResponse($data = null)
    {

        // 設定允許跨域
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Authorization, Authorize_token, Content-Type');
        header('Access-Control-Allow-Methods: *');
        $response = [
            'system_code' => 200,
            'message' => '成功'
        ];
        if ($data !== null) {
            $response['data'] = $data;
        }

        return $this->response->setJSON($response);
    }

    protected function errorResponse($message, $code=500,$data=null)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Authorization, Authorize_token, Content-Type');
        header('Access-Control-Allow-Methods: *');
        $this->response->setStatusCode($code);  
        $response = [
            'system_code' => $code,
            'message' => $message
        ];
        if ($data !== null) {
            $response['data'] = $data;
        }
        return $this->response->setJSON($response);
    }

   
}
