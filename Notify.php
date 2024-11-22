<?php 
    $path = realpath(__DIR__) ."";
    require_once $path . "/load-env.php";

    class Notify {
        private $Client;
        private $ApiUrl;

        public function __construct() {
            $this->Client = new \GuzzleHttp\Client();
            $this->ApiUrl = $_ENV['NOTIFY_API_URL'];
        }


        // Generic Request Function
        private function request($method, $endpoint, $body = null) {
            $response = $this->Client->request($method, $this->ApiUrl . $endpoint, [
                'body' => $body,
            ]);

            return $response;
        }

        // Send Notification
        public function sendNotification($message) {
            $NotificationResponse = $this->request('POST', '', $message);
            return json_decode($NotificationResponse->getBody());
        }
    }
?>