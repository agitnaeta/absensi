<?php

namespace App\Services\Acc;

use App\Services\Acc\AccTransaction;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class Acc
{

    protected $host;

    protected $key;


    public function __construct()
    {
        $this->host = env('ACC_HOST');
        $this->key = env('ACC_KEY');
    }

    private function headers()
    {
        return [
            'Authorization' => 'Bearer '.$this->key,
            'Content-Type' => 'application/json',
            'Accept' => 'application/vnd.api+json',
        ];
    }
    public function withdraw(AccTransaction $data)
    {
        $client = new Client();
        $body =  [
            "error_if_duplicate_hash"=> true,
            "apply_rules"=> false,
            "fire_webhooks"=> false,
            "transactions" => [$data]
        ];

        $request = new Request('POST', $this->host.'/api/v1/transactions', $this->headers(), json_encode($body));
        $res = $client->sendAsync($request)->wait();
        return json_decode($res->getBody());
    }
    public function deposit(AccTransaction $data)
    {
        $client = new Client();
        $body =  [
            "error_if_duplicate_hash"=> true,
            "apply_rules"=> false,
            "fire_webhooks"=> false,
            "transactions" => [$data]
        ];

        $request = new Request('POST', $this->host.'/api/v1/transactions', $this->headers(), json_encode($body));
        $res = $client->sendAsync($request)->wait();
        return json_decode($res->getBody());
    }

    public function getAccounts()
    {
        $client = new Client();
        $url = $this->host.'/api/v1/accounts?limit=100&page=1&type=all';
        $request =new Request('GET', $url, $this->headers(), '');
        $res = $client->sendAsync($request)->wait();
        $values =  json_decode($res->getBody());

        $data  = [];
        foreach ($values->data as $value) {
            $data[$value->id] = $value->attributes->name;
        }
        return $data;
    }
}
