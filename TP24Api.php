<?php 
$path = realpath(__DIR__) ."";
require_once $path . "/load-env.php";
    class TP24Api {
    private $Client;
    private $ApiUrl;
    private $RequestHeaders;
    private $Token;

    public function __construct() {
        $this->Client = new \GuzzleHttp\Client();
        $this->ApiUrl = $_ENV['TP24_API_URL'];
        $this->RequestHeaders = $this->generateTp24AuthHeader();
    }
    // Get OAuth2 Token from TP24 API
    private function getTp24Token() {
        $ClientId = $_ENV['TP24_API_CLIENT_ID'];
        $ClientSecret = $_ENV['TP24_API_CLIENT_SECRET'];
        $TokenEndpoint = $_ENV['TP24_API_TOKEN_ENDPOINT'];
        $TokenUrl = $this->ApiUrl . $TokenEndpoint;
        $GrantType = "client_credentials";

        $requestBody = json_encode([
            'client_id' => $ClientId,
            'client_secret' => $ClientSecret,
            'grant_type' => $GrantType
        ]);

        $response = $this->Client->request('POST', $TokenUrl, [
            'body' => $requestBody,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        $responseBody = json_decode($response->getBody());
        return $responseBody->access_token;
    }
    // Generate TP24 Auth Header based on OAuth2 Token
    private function generateTp24AuthHeader() {
        $this->Token = $this->getTp24Token();
        return [
            'Authorization' => 'Bearer ' . $this->Token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    // Generic Request Function
    private function request($method, $endpoint, $body = null) {
        $response = $this->Client->request($method, $this->ApiUrl . $endpoint, [
            'body' => $body,
            'headers' => $this->RequestHeaders,
        ]);

        return $response;
    }

    // GET List of all Products
    public function getProductList() {
        $ProductListResponse = $this->request('GET', '/product');
        return json_decode($ProductListResponse->getBody());
    }

    // GET Product Details
    public function getProduct($productId) {
        $ProductResponse = $this->request('GET', '/product/'.$productId);
        return json_decode($ProductResponse->getBody());
    }


    // Filter Products based on one of the Product Fields
    public function searchProduct($field, $filterType, $filterValue) {
        $ProductSearchResponse = $this->request('POST', '/search/product', json_encode([
            'filter' => [
                'type' => $filterType,
                'value' => $filterValue,
                'field' => $field,
            ],   
        ]));
        return json_decode($ProductSearchResponse->getBody());
    }

    // Patch Stock in Product
    public function setStock($productId, $stock) {
        $StockResponse = $this->request('PATCH', '/product/'.$productId , json_encode([
            'stock' => $stock,
        ]));

        return json_decode($StockResponse->getBody());
    }
}

?>