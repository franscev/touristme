<?php

namespace App\Http\Controllers;

use App\Place;
use App\User;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $key = $this->key;
      $headers = getallheaders();
      $token = $headers['Authorization'];
      $user = JWT::decode($token, $key, array('HS256'));
      $idUser = $user->user->id;

      $userPlaces = Place::where('user_id', $idUser)->get();

      if (count($userPlaces) == 0){

        return response(204);
      }
      
      $places = [];

        foreach ($userPlaces as $place){
          $places[] = $place;
        }
      
        return response()->json([
        'places'=> $places,
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
      $key = $this->key;
      $headers = getallheaders();
      $token = $headers['Authorization'];
      $user = JWT::decode($token, $key, array('HS256'));

       
      $place = new Place();
      $place->title = $request->title;
      $place->description = $request->description;
      $place->startDate = $request->startDate;
      $place->endDate = $request->endDate;
      $place->coordX = $request->coordX;
      $place->coordY = $request->coordY;
      $place->user_id = $user->user->id;

        if ($request->title == null or $request->description == null or $request->startDate == null or $request->endDate == null or $request->coordX == null or $request->coordY == null)
      {

        return response(204);

       }

       $place->save();
       return response(200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function show(Place $place)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function edit(Place $place)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Place $place)
    {
        $header = getallheaders();

        $userParams = JWT::decode($header['Authorization'], $this->key, array('HS256'));
        
        if ($userParams->user->id == $place->user_id) {
            if (empty($request->title) || empty($request->startDate) || empty($request->endDate) || empty($request->description) || empty($request->coordX) || empty($request->coordY))
            {
                return response()->json([
                    'MESSAGE' => 411, 'Some fields are empty'
                ]);    
            }
            else {
                $place->title = $request->title;
                $place->description = $request->description;
                $place->startDate = $request->startDate;
                $place->endDate = $request->endDate;
                $place->coordX = $request->coordX;
                $place->coordY = $request->coordY;
                $place->save();
                return response()->json([
                    'MESSAGE' => 200, 'The place has been updated correctly'
                ]); 
            }
        }else {
            return response()->json([
                'MESSAGE' => 403, 'Dont have enough permission'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Place  $place
     * @return \Illuminate\Http\Response
     */



    public function destroy(Place $place)
    {
    $header = getallheaders();

    if (!empty($header['Authorization'])) 
    {
        $userParams = JWT::decode($header['Authorization'], $this->key, array('HS256'));
        $places = Place::all();

        if ($user = Place::where('user_id', $userParams->user->id)->first()) 
        {
            foreach ($places as $key => $place) {
                if ($place->user_id == $userParams->user->id) {
                    $place->delete();
                    return response()->json([
                        'MESSAGE' => 200, 'The place has been deleted correctly'
                    ]);
                } else {
                    return response()->json([
                        'MESSAGE' => 403, 'Dont have enough permission' 
                    ]);
                }
            }
        } else {
            return response()->json([
                    'MESSAGE' => 403, 'Dont have enough permission'
                ]);
            }
        }else{
            return response()->json([
                'MESSAGE' => 'The user is not logged'
            ]);
        }
    }
}
