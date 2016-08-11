<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Rancher;
use App\Http\Controllers\Controller;

class StackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $stacks = Rancher::environment()->all();

        foreach ($stacks as $stack)
        {
            unset($stack->dockerCompose);
            unset($stack->rancherCompose);
        }

        return response()->json($stacks);
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

            $response = Rancher::environment()->with(['services'])->get($id);

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
