<?php

namespace Savannabits\Movesms;

class Movesms
{
    const PLAIN_SMS = 5;
    const NO_DELIVERY_REPORT = 0;
    /**
     * @var string
     */
    private $senderId;
    /**
     * @var
     */
    private $username;
    /**
     * @var string
     */
    private $apiKey;
    /**
     * @var string
     */
    private $to;
    /**
     * @var string
     */
    private $message;
    /**
     * @var int
     */
    private $dlr = self::NO_DELIVERY_REPORT;
    /**
     * @var int
     */
    private $messageType = self::PLAIN_SMS;
    /**
     * @var string
     */
    private $scheduletime;
    /**
     * @var string
     */
    private $apiUrl = "https://sms.movesms.co.ke/api";

    /**
     * Initiate the API
     * @param $username
     * @param $apiKey
     * @param $senderId
     * @return Movesms
     */
    public static function init($username, $apiKey, $senderId) {
        $instance = new self;
        $instance->username = $username;
        $instance->apiKey = $apiKey;
        $instance->senderId = $senderId;
        return $instance;
    }

    /**
     * Set the recipients of the message
     * @param string|array $recipients | Either Comma-separated string of phone numbers or an array of phone numbers
     * @return Movesms
     */
    public function to($recipients) {
        if (is_array($recipients)) {
            $this->to = implode(", ",$recipients);
        } else {
            $this->to = $recipients;
        }
        return $this;
    }

    /**
     * @param string $message
     * @return Movesms
     */
    public function message($message) {
        $this->message = $message;
        return $this;
    }

    /**
     * @param int $type
     * @return Movesms
     */
    public function messageType($type = self::PLAIN_SMS) {
        $this->messageType = $type;
        return $this;
    }

    /**
     * @param int $dlr
     * @return Movesms
     */
    public function deliveryReport($dlr = self::NO_DELIVERY_REPORT) {
        $this->dlr = $dlr;
        return $this;
    }

    /**
     * API to send SMS on demand
     * @return mixed
     */
    public function send() {
        $url = "$this->apiUrl/compose";
        $this->scheduletime = null;
        $data = [
            'username' => $this->username,
            'api_key' => $this->apiKey,
            'sender' => $this->senderId,
            'to' => $this->to,
            'message' => $this->message,
            'msgtype' => $this->messageType,
            'dlr' => $this->dlr,
        ];
        return $this->executePost($url,$data);
    }

    /**
     * API To schedule sms to send later.
     * @param string $when | Datetime when to send the sms in Y-m-d H:i:s format
     * @return mixed
     */
    public function sendLater($when) {
        $url = "$this->apiUrl/schedule";
        $this->scheduletime = $when;
        $data = [
            'username' => $this->username,
            'api_key' => $this->apiKey,
            'sender' => $this->senderId,
            'to' => $this->to,
            'message' => $this->message,
            'msgtype' => $this->messageType,
            'dlr' => $this->dlr,
            'scheduletime' => $this->scheduletime,
        ];
        return $this->executePost($url,$data);
    }

    /**
     * API To Check Balance
     * @param $apiKey
     * @return mixed
     */
    public static function checkBalance($apiKey) {
        $apiUrl = (new self())->apiUrl;
        $url = "$apiUrl/schedule";
        $data = [
            'api_key' => $apiKey,
        ];
        return (new self())->executePost($url,$data);
    }

    /**
     * @param $apiUrl
     * @param array $postData
     * @return mixed
     */
    private function executePost($apiUrl, $postData = []) {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            // echo 'error:' . curl_error($ch);
            $output = curl_error($ch);
        }
        curl_close($ch);
        if (strpos($output,":1701") === false ) {
            return (object)[
                "success" => false,
                "message" => $output
            ];
        } else {
            return (object)[
                "success" => true,
                "message" => $output
            ];
        }
    }
}
