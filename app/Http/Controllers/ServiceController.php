<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Rancher;
use Benmag\Rancher\Factories\Entity\Service;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(Rancher::service()->all());
    }
    /**
     * Display a listing of the resource by stack ID.
     *
     * @param  int  $stack_id
     * @return \Illuminate\Http\Response
     */
    public function index_by_stack($stack_id)
    {
        //
        return response()->json(Rancher::service()->filter(['environmentId' => $stack_id])->all());
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

            $response = Rancher::service()
                ->with(['transitioning','transitioningMessage','transitioningProgress'])
                ->get($id);

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

    public function show_by_stack($stack_id, $id)
    {
        //
        return $this->show($id);
    }


    public function upgrade($id)
    {
        //
        try {
            $service = Rancher::service()->get($id);

            $serviceUpgrade = [
                'inServiceStrategy' => [
                    'batchSize' => 1,
                    'intervalMillis' => 2000,
                    'startFirst' => true,
                    'launchConfig' => $service->launchConfig
                ]
            ];

            $serviceUpgrade['inServiceStrategy']['launchConfig']->labels->{"io.rancher.container.pull_image"} = "always";

            $response = Rancher::service()->upgrade($id, $serviceUpgrade);

            $statusCode = 200;
           // $response = $serviceUpgrade;
        }
        catch(\Exception $e){
            $response = [
                "error" => "Cannot execute upgrade"
            ];
            $statusCode = 422;
        }

        return response()->json($response, $statusCode);
    }

    public function finish_upgrade($id)
    {
        //
        try {

            $looping = true;

            do {

                $service = Rancher::service()->get($id);

                if ($service->state == "active" || $service->state == "finishing-upgrade" )
                {
                    $response = $service;
                    $looping = false;

                }
                else if ($service->state == "upgraded")
                {

                    $response = Rancher::service()->finishUpgrade($id);
                    $looping = false;
                }
                else if ($service->state == "upgrading")
                {
                    // need to wait....
                    sleep(5);
                }

            } while($looping); //loop will run infinite

            $statusCode = 200;
            // $response = $serviceUpgrade;
        }
        catch(\Exception $e){
            $response = [
                "error" => "cannot finish upgrade"
            ];
            $statusCode = 422;
        }

        return response()->json($response, $statusCode);
    }

    public function scale($id,$scale)
    {
        try {

            $service = Rancher::service()->get($id);

            $updatedService = new Service([
                "description" => $service->description,
                "name" => $service->name,
                "scale" => $scale
            ]);
            $response = Rancher::service()->update($id, $updatedService);

            $statusCode = 200;
        }
        catch(\Exception $e){
            $response = [
                "error" => "cannot scale service"
            ];
            $statusCode = 422;
        }

        return response()->json($response, $statusCode);
    }
}
