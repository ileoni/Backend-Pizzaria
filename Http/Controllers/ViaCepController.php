<?php

namespace App\Http\Controllers;

class ViaCepController extends Controller
{
    const BASE_URL = 'https://viacep.com.br/ws/';

    const METHOD = '/json/';

    protected $rules = [
        'cep' => ['required_if:iscep,true', 'regex:/[0-9]{8}+/i', 'size:8'],
        'state' => ['required_if:iscep,false'],
        'city' => ['required_if:iscep,false'],
        'street' => ['required_if:iscep,false'],
    ];
    
    protected $message = [
        'state.required' => 'O campo estado não foi preenchido.',
        'city.required' => 'O campo cidade não foi preenchido.',
        'street.required' => 'O campo rua não foi preenchido.',
        'required_if' => 'O campo :attribute não foi preenchido.',
        'regex' => 'O :attribute deve conter somente números.',
        'size' => 'Este :attribute é invalido.'
    ];

    public function getCep()
    {
        $this->validate(request(), $this->rules, $this->message);
        
        $cep = request('cep');
        $state = request('state');
        $city = request('city');
        $street = request('street');

        if($cep) {
            $url = self::BASE_URL. $cep .self::METHOD;
        } else {
            $url = self::BASE_URL. "{$state}/{$city}/{$street}" .self::METHOD;
        }
     
        $response = $this->requestByCep($url);

        return response()->json([
            "success" => true,
            "data" => $response
        ], 200);
    }

    public function requestByCep($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        
        $response = json_decode(curl_exec($curl));
        
        curl_close($curl);

        return $response;
    }
}