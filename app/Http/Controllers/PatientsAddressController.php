<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientsAddressRequest;
use App\Http\Resources\PatientsAddressResource;
use App\Models\PatientsAddress;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class PatientsAddressController extends Controller
{
    public function index()
    {
        return PatientsAddressResource::collection(PatientsAddress::all());
    }

    public function store(PatientsAddressRequest $request)
    {
        return new PatientsAddressResource(PatientsAddress::create($request->validated()));
    }

    public function show(PatientsAddress $patientsAddress)
    {
        return new PatientsAddressResource($patientsAddress);
    }

    public function update(PatientsAddressRequest $request, PatientsAddress $patientsAddress)
    {
        $patientsAddress->update($request->validated());

        return new PatientsAddressResource($patientsAddress);
    }

    public function destroy(PatientsAddress $patientsAddress)
    {
        $patientsAddress->delete();

        return response()->json();
    }

    /**
     * @throws GuzzleException
     * @throws InvalidArgumentException
     */
    public function searchCep($cep)
    {
        $cep = str_replace('-', '', $cep);
        if (Cache::store('redis')->has($cep)) {
            return response()->json(Cache::store('redis')->get($cep));
        }
        $request = new Client();
        $response = $request->request('GET', 'https://viacep.com.br/ws/' . $cep . '/json/');
        $response = json_decode($response->getBody()->getContents());
        Cache::store('redis')->put($cep, $response, 60 * 60 * 24);
        return response()->json($response);
    }
}
