<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Tour;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TourController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'min_time' => ['required', 'int']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Tour
     */
    protected function create(array $data)
    {
        return Tour::create([
            'name' => $data['name'],
            'min_time' => 0
        ]);
       
    }

    /**
    * success response method.
    *
    * @return \Illuminate\Http\Response
    */
    public function getTours()
    {
        $data = DB::select('select * from tours');
        $type = DB::select('select tour_id, type_id, types.name as name from tours_types, tours, types where tours.id = tours_types.tour_id and tours_types.type_id = types.id');
        return view('tour')->with('data', json_encode($data))->with('type', json_encode($type));
    }

    public function newTourSubmit(Request $request)
    {
        $tour = Tour::create([
            'name' => $request['name'],
            'min_time' => 0
        ]);

        return response()->json([$request->all()]);
    }

    public function deleteTour(Request $request)
    {
        Tour::destroy($request['id']);
        return response()->json([$request->all()]);
    }

    public function tourSubmit(Request $request)
    {
        DB::insert('insert into locations values (NULL, ?, ?, ?, ?, ?, NOW(), NULL)', [$request['name'], $request['x_axis'], $request['y_axis'], $request['description'], $request['min_time']]);
        return response()->json([$request->all()]);
    }

    public function tourTimeUpdate(Request $request)
    {
        
        DB::table('tours')
            ->where('id', $request['id'])
            ->update(['min_time' => $request['min_time']]);
        
        return response()->json([$request->all()]);
    }

    public function tourSubmitLocation(Request $request)
    {
        $data = DB::select('select * from tours_locations where tour_id = ? and location_id = ?', [$request['id'], $request['location_id']]);
        if ($data != null) {
            DB::table('tours_locations')
                ->where('tour_id', $request['id'])
                ->where('location_id', $request['location_id'])
                ->update(['order' => $request['location_order']]);
        } else {
            DB::insert('insert into tours_locations values (NULL, ?, ?, ?)', [$request['id'], $request['location_order'], $request['location_id']]);
        }
        return response()->json([$request->all()]);
    }

    public function tourDeleteLocation(Request $request)
    {
        DB::delete('delete from tours_locations where tour_id = ? and location_id = ?', [$request['id'], $request['location_id']]);
        return response()->json([$request->all()]);
    }

    public function getTourType(Request $request)
    {
        $type_list = DB::select('select * from types');
        $type = DB::select('select type_id as id, name from types, tours_types where types.id = tours_types.type_id and tours_types.tour_id = ?', [$request['id']]);
        return view('tour_type')->with('type', json_encode($type))->with('type_list', json_encode($type_list));
    }

    public function tourSubmitType(Request $request)
    {
        $data = DB::select('select * from tours_types where tour_id = ? and type_id = ?', [$request['id'], $request['type_id']]);
        if ($data == null) {
            DB::insert('insert into tours_types values (NULL, ?, ?)', [$request['id'], $request['type_id']]);
        }
        return response()->json([$request->all()]);
    }

    public function tourDeleteType(Request $request)
    {
        DB::delete('delete from tours_types where tour_id = ? and type_id = ?', [$request['id'], $request['type_id']]);
        return response()->json([$request->all()]);
    }
    
}
