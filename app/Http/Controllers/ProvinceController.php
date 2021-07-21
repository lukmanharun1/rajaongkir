<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $province = Province::all();
            return response()->json([
                'status' => 'success',
                'data' => $province
            ], Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errorInfo
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $responseRajaOngkir = Http::get("https://api.rajaongkir.com/starter/province?key=" . env('KEY'))
                                ->collect()['rajaongkir']['results'];
        try {
            Province::insert($responseRajaOngkir);

            return response()->json([
                'status' => 'success',
                'data' => $responseRajaOngkir
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errorInfo
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Province $province_id)
    {
        return response()->json([
            'status' => 'success',
            'data' => $province_id
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        
        try {
            $validator = Validator::make($request->all(), [
                'province' => 'required|max:128|unique:App\Models\Province,province'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()
                ], Response::HTTP_BAD_REQUEST);
            }
            $updateProvince = Province::where('province_id',  $id);
            if ($updateProvince->count() === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'province_id not found'
                ], Response::HTTP_NOT_FOUND);
            }
            $updateProvince->update($request->all());
           
            return response()->json([
                'status' => 'success', 
                'data' => Province::where('province_id',  $id)->get()
            ], Response::HTTP_OK);
            
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errorInfo
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $deleteProvince = Province::where('province_id',  $id);
            if ($deleteProvince->count() === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'province_id not found'
                ], Response::HTTP_NOT_FOUND);
            }
            $deleteProvince->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Province deleted'
            ], Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errorInfo
            ], Response::HTTP_BAD_REQUEST);
        }

    }
}
