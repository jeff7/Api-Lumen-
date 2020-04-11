<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cars;
use App\Models\ValidationCars;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class CarsController extends Controller
{
    private $model;

    public function __construct(Cars $cars)
    {
        $this->model = $cars;
    }

    public function getAll()
    {
        try {

            //metodo do eloquent que retorna todos os registros do banco
            $cars = $this->model->all();

            if (count($cars) > 0)
            {
                //metodo que transforma a resposta em json
                return response()->json($cars, Response::HTTP_OK);
            }
            else
            {
                //metodo que transforma a resposta em json
                return response()->json([], Response::HTTP_OK);
            }
        } catch (QueryException $th) {
            //throw $th;
            return response()->json(["error" => "erro de sistemas"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

    public function get($id)
    {
        try {
             //metodo que seleciona um registro do banco pelo seu ID
            $cars = $this->model->find($id);

            if (count($cars) > 0)
            {
                //metodo que transforma a resposta em json
                return response()->json($cars, Response::HTTP_OK);
            }
            else
            {
                //metodo que transforma a resposta em json
                return response()->json(null, Response::HTTP_OK);
            }
    
            return response()->json($cars);           
        } catch (QueryException $th) {
            //throw $th;
            return response()->json(["error" => "erro de sistemas"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),    
            ValidationCars::RULE_CAR
        );

        if ( $validator->fails())
        {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        else
       {
            try {
                //metodo que cria um resgistro no banco com os dados da request( dados envidos pelo post  )
                $cars = $this->model->create($request->all());

                return response()->json($cars, Response::HTTP_CREATED);
            
            } catch (QueryException $th) {
                //throw $th;
                return response()->json(["error" => "erro de sistemas"], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
       }

    }

    public function update($id, Request $request)
    {
        try {
            $cars = $this->model->find($id)
            ->update($request->all());
            
            return response()->json($cars, Response::HTTP_CREATED);
            
        } catch (QueryException $th) {
            //throw $th;
            return response()->json(["error" => "erro de sistemas"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function destroy($id)
    {
        try {
            $cars = $this->model->find($id)
                ->delete();

            return response()->json(null, Response::HTTP_OK);
            
        } catch (QueryException $th) {
            //throw $th;
            return response()->json(["error" => "erro de sistemas"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
       
    }


    //
}
