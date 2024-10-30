<?php 
require_once "./load-env.php";

    class PTApi {
    private $Client;
    private $ApiUrl;
    private $RequestHeaders;
    private $Token;

    public function __construct() {
        $this->Client = new \GuzzleHttp\Client();
        $this->ApiUrl = $_ENV['PT_API_URL'];
        $this->RequestHeaders = $this->generatePTAuthHeader();
    }

    private function getPtToken() {
        // PT uses Basic Auth 
        $Username = $_ENV['PT_API_USERNAME'];
        $Password = $_ENV['PT_API_PASSWORD'];

        return base64_encode("$Username:$Password");
    }

    // Generate PT Auth Header based on Basic Auth Token
    private function generatePtAuthHeader() {
        $this->Token = $this->getPtToken();
        return [
            'Authorization' => 'Basic ' . $this->Token,
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
        $ProductListResponse = $this->request('GET', '/products');
        return json_decode($ProductListResponse->getBody());
    }

    // GET Product Details
    public function getProductDetails($productId) {
        $ProductDetailsResponse = $this->request('GET', "/product/$productId");
        return json_decode($ProductDetailsResponse->getBody());
    }

    // Get Stock for Product by Id
    public function getProductStock($productId) {
        $Product = $this->getProductDetails($productId)->product;
        return $Product->stock;
    }

}

?>