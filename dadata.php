<?php

class Dadata
{
    private $clean_url = "https://cleaner.dadata.ru/api/v1/clean";
    private $token;
    private $secret;
    private $handle;

    public function __construct($token, $secret)
    {
        $this->token = $token;
        $this->secret = $secret;
    }

    public function init()
    {
        $this->handle = curl_init();
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->handle, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: Token " . $this->token,
            "X-Secret: " . $this->secret,
        ));
        curl_setopt($this->handle, CURLOPT_POST, 1);
    }

    public function cleanName($name)
    {
        $url = $this->clean_url . "/name";
        $fields = array($name);
        return $this->executeRequest($url, $fields);
    }

    public function close()
    {
        curl_close($this->handle);
    }

    private function executeRequest($url, $fields)
    {
        curl_setopt($this->handle, CURLOPT_URL, $url);
        curl_setopt($this->handle, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = $this->exec();
        $result = json_decode($result, true);
        return $result;
    }

    private function exec()
    {
        $result = curl_exec($this->handle);
        $info = curl_getinfo($this->handle);
        if ($info['http_code'] != 200) {
            throw new Exception('Request failed with http code ' . $info['http_code'] . ': ' . $result);
        }
        return $result;
    }
}

$token = "3283c6c1e8a1525d49af2871bfe55b9d59f60a37";
$secret = "f93d8189f8fa28e152d96d45cf3ba1967b20a6cd";

$dadata = new Dadata($token, $secret);
$dadata->init();

$result = ($_POST['user_name']." ".$_POST['user_second_name']." ".$_POST['user_last_name']);

echo '<pre>';
print_r($result);
echo '</pre>';

$dadata->close();

