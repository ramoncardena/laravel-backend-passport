<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PortfolioResource;
use Facade\FlareClient\Http\Exceptions\NotFound;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $portfolios = Auth::user()->portfolios;
        return response()->json(["status" => "success", "error" => false, "count" => count($portfolios), "data" => $portfolios], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|min:3|unique:portfolios,name",
            "description" => "required"
        ]);

        if ($validator->fails()) {
            return $this->validationErrors($validator->errors());
        }

        try {
            $portfolio = Portfolio::create([
                "name" => $request->name,
                "description" => $request->description,
                "user_id" => Auth::user()->id
            ]);
            return response()->json(["status" => "success", "error" => false, "message" => "Success! portfolio created."], 201);
        } catch (Exception $exception) {
            return response()->json(["status" => "failed", "error" => $exception->getMessage()], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $portfolio = Auth::user()->portfolios->find($id);

        if ($portfolio) {
            return response()->json(["status" => "success", "error" => false, "data" => $portfolio], 200);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed! no portfolio found."], 404);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $portfolio = Auth::user()->portfolios->find($id);

        if ($portfolio) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->validationErrors($validator->errors());
            }

            $portfolio['name'] = $request->name;
            $portfolio['description'] = $request->description;

            // if has active
            if ($request->active) {
                $portfolio['active'] = $request->active;
            }

            // if has completed
            if ($request->completed) {
                $portfolio['completed'] = $request->completed;
            }

            $portfolio->save();
            return response()->json(["status" => "success", "error" => false, "message" => "Success! portfolio updated."], 201);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed no portfolio found."], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $portfolio = Auth::user()->portfolios->find($id);
        if ($portfolio) {
            $portfolio->delete();
            return response()->json(["status" => "success", "error" => false, "message" => "Success! portfolio deleted."], 200);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed no portfolio found."], 404);
    }
}
