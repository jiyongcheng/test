<?php

namespace App\Http\Controllers\Mypal;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Pet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;
use JWTAuth;

class PetController extends ApiController
{
    /*
     * retrieve a list of all pets, ordered by list date desc
     */
    public function list() {
        $pets = Pet::orderBy('list_date','desc')->get();
        return $this->success($pets);
    }

    /*
     * retrieve details of a specific pet
     */
    public function detail($id)
    {
        $pet = Pet::find($id);
        if($pet) {
            return $this->success($pet);
        }else {
            return $this->error($pet,'pet not found');
        }

    }

    /*
     * add a new pet to the system
     */
    public function create(Request $request)
    {
        $this->validate($request,[
            'breed'=>'required|max:255',
            'age'=>'required|integer',
            'price'=>'required|numeric|between:0,999999.99|regex:/^\d*(\.\d{1,2})?$/',
            'list_date'=>'required|date'
        ]);
        $pet = new Pet;
        $pet->breed = $request->input('breed');
        $pet->age = $request->input('age');
        $pet->name=$request->input('name');
        $pet->price = $request->input('price');
        $pet->list_date = $request->input('list_date');
        $pet->sale_date = $request->input('sale_date');
        $currentDatTime = new \DateTime('now',new \DateTimeZone('Australia/Sydney'));
        $pet->created_at = $currentDatTime->format('Y-m-d H:i:s');
        $pet->updated_at = $currentDatTime->format('Y-m-d H:i:s');
        try {
            $pet->save();
            return $this->success($pet,'pet create success');
        }catch (\Exception $e){
            return $this->error(array(),$e->getMessage());
        }

    }

    /*
     * retrieve details of a specific pet
     */
    public function edit(Request $request, $id)
    {
        $pet = Pet::find($id);
        if($pet) {
            $pet->breed = $request->input('breed',$pet->breed);
            $pet->age = $request->input('age',$pet->age);
            $pet->name=$request->input('name',$pet->name);
            $pet->price = $request->input('price',$pet->price);
            $pet->list_date = $request->input('list_date',$pet->list_date);
            $pet->sale_date = $request->input('sale_date',$pet->sale_date);
            $currentDatTime = new \DateTime('now',new \DateTimeZone('Australia/Sydney'));
            $pet->updated_at = $currentDatTime->format('Y-m-d H:i:s');
            try {
                $pet->save();
                return $this->success($pet,'pet edit success');
            }catch (\Exception $e){
                return $this->error(array(),$e->getMessage());
            }
        }else {
            return $this->error($pet,'pet not found');
        }

    }

    /*
     * delete a pet from the system
     */
    public function delete($id)
    {
        $pet = Pet::find($id);
        if($pet) {
            $pet->delete();
            return $this->success($pet,'pet delete success');
        }else {
            return $this->error($pet,'pet not found');
        }
    }

}