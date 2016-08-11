<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Rancher;
use App\Http\Controllers\Controller;

class HostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $hosts = Rancher::host()->all();

        foreach ($hosts as $host)
        {
            unset($host->publicEndpoints);
        }

        return response()->json($hosts);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {

            $response = Rancher::host()->fields(['created','labels'])->get($id);

            $statusCode = 200;
        }
        catch(\Exception $e){
            $response = [
                "error" => "object doesn't exist"
            ];
            $statusCode = 404;
        }

        return response()->json($response, $statusCode);
    }
}
