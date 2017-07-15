<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Excel;
use Illuminate\Support\Facades\Log;

class RecipeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('recipe');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function find(Request $request)
    {
        $this->validate($request,[
            'fridge_ingredients'=> 'required|file|mimes:csv,txt',
            'recipes'=> 'required|json'
        ]);
        if(Input::hasFile('fridge_ingredients')) {
            $path = Input::file('fridge_ingredients')->getRealPath();
            $fridgeIngredients = $this->getFridgeIngredientsThroughCsv($path);
        }
        $recipesJson = $request->input('recipes');
        $recipes = $this->getRecipesThroughJson($recipesJson);

        if(empty($fridgeIngredients)) {
            return view('recommend',['recipe'=>'No Ingredients could be used for recipe']);
        }
        if(empty($recipes)) {
            return view('recommend',['recipe'=>'Order Takeout']);
        }

        $recommendRecipe = $this->getRecommendRecipe($fridgeIngredients,$recipes);

        if(empty($recommendRecipe)) {
            $recommendRecipe = 'Order Takeout';
        }
        return view('recommend',['recipe'=>$recommendRecipe]);
    }

    /**
     * @param $path
     * @return array
     */
    public function getFridgeIngredientsThroughCsv($path)
    {
        $fridgeIngredients = array();
        Excel::load($path,function($reader) use (&$fridgeIngredients){
            $reader->noHeading();
            $currentTime = time();
            foreach($reader->toArray() as $row) {
                $useBy = strtotime(str_replace('/', '-', $row[3]));
                //filter the ingredient whose use by date is not ok
                if($currentTime <= $useBy) {
                    $fridgeIngredients[$row[0]] = array(
                        'amount' => $row[1],
                        'unit' => $row[2],
                        'date' => $row[3]
                    );
                }
            }
        });

        return $fridgeIngredients;
    }

    /**
     * @param $recipesJson
     * @return array
     */
    public function getRecipesThroughJson($recipesJson)
    {
        $recipes = array();
        foreach(json_decode($recipesJson) as $item) {
            $ingredientData = array();
            foreach($item->ingredients as $ingredient) {
                $ingredientData[$ingredient->item]=array(
                    'amount' => $ingredient->amount,
                    'unit' => $ingredient->unit
                );
            }
            $recipes[$item->name] = $ingredientData;
        }

        return $recipes;
    }

    /**
     * sort the fridge ingredients according to their use-by date
     * @param $fridgeIngredients
     * @return array
     */
    public function sortFridgeIngredients($fridgeIngredients)
    {
        $currentTime = time();
        $fridgeIngredientAscSequences = array();
        foreach($fridgeIngredients as $fridgeIngredientName=>$fridgeIngredient) {
            $fridgeIngredientTimestamp = strtotime(str_replace('/', '-', $fridgeIngredient['date']));
            $fridgeIngredientAscSequences[$fridgeIngredientName] = $fridgeIngredientTimestamp - $currentTime;
        }

        asort($fridgeIngredientAscSequences,SORT_NUMERIC);

        return $fridgeIngredientAscSequences;
    }


    /**
     * find the suitable recipe which means the amount and unit of the ingredients are suitable
     * @param $recipes
     * @return array
     */
    public function findSuitableRecipe($recipes,$fridgeIngredients)
    {
        $suitableRecipes = array();

        foreach($recipes as $recipeName=>$recipeIngredients) {
            $available = true;
            foreach($recipeIngredients as $recipeIngredientName=>$recipeIngredient) {
                if(isset($fridgeIngredients[$recipeIngredientName]) &&
                    $fridgeIngredients[$recipeIngredientName]['amount'] >= $recipeIngredient['amount'] &&
                    $fridgeIngredients[$recipeIngredientName]['unit'] == $recipeIngredient['unit']) {
                    //this recipe is possible
                }else {
                    $available = false;
                    break;
                }
            }
            if($available) {
                $suitableRecipes[] = $recipeName;
            }
        }

        return $suitableRecipes;
    }

    /**
     * @param $fridgeIngredients
     * @param $recipes
     * @return mixed|string
     */
    public function getRecommendRecipe($fridgeIngredients,$recipes)
    {
        //sort the fridge ingredients according to their use-by date
        $fridgeIngredientAscSequences = $this->sortFridgeIngredients($fridgeIngredients);

        //find the suitable recipe which means the amount and unit of the ingredients are suitable
        $suitableRecipes = $this->findSuitableRecipe($recipes,$fridgeIngredients);

        $recommendRecipe = '';
        foreach($fridgeIngredientAscSequences as $ingredientName=>$priority) {
            $recipeCandidates = array();
            foreach($suitableRecipes as $suitableRecipeName) {
                $recipeIngredientNames = array_keys($recipes[$suitableRecipeName]);
                if(in_array($ingredientName,$recipeIngredientNames)) {
                    $recipeCandidates[] = $suitableRecipeName;
                }
            }
            if(count($recipeCandidates)==1) {
                $recommendRecipe = $recipeCandidates[0];
                break;
            }elseif(count($recipeCandidates) >1) {
                $suitableRecipes = $recipeCandidates;
            }
        }

        return $recommendRecipe;
    }
}
