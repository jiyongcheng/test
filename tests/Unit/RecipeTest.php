<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\RecipeController;

class RecipeTest extends TestCase
{
    public function testReadCsvTest()
    {
        $path = storage_path('documents/fridge_ingredients.csv');
        $controller = new RecipeController();
        $data = $controller->getFridgeIngredientsThroughCsv($path);
        $this->assertEquals(0,count($data));
    }

    public function testFridgeIngredientsPassUseByTest()
    {
        $path = storage_path('documents/fridge_ingredients2.csv');
        $controller = new RecipeController();
        $data = $controller->getFridgeIngredientsThroughCsv($path);
        $this->assertEquals(4,count($data));
    }

    public function testGetJsonDataTest()
    {
        $json = '[{ "name": "grilled cheese on toast", "ingredients": [ { "item":"bread", "amount":"2", "unit":"slices"}, { "item":"cheese", "amount":"2", "unit":"slices"} ] },{ "name": "salad sandwich", "ingredients": [ { "item":"bread", "amount":"2", "unit":"slices"}, { "item":"mixed salad", "amount":"100", "unit":"grams"} ] }]';
        $controller = new RecipeController();
        $data = $controller->getRecipesThroughJson($json);
        $this->assertArrayHasKey('grilled cheese on toast',$data);
    }

    public function testSortIngredientsTest()
    {
        $ingredients = array(
            'bread'=>array(
                'amount'=>10,
                'unit' =>'slices',
                'date' => '12/12/2017'
            ),
            'butter'=>array(
                'amount'=>10,
                'unit' =>'slices',
                'date' => '15/12/2017'
            ),
            'cheese'=>array(
                'amount'=>10,
                'unit' =>'slices',
                'date' => '28/07/2017'
            ),
        );
        $controller = new RecipeController();
        $data = $controller->sortFridgeIngredients($ingredients);
        $keys = array_keys($data);
        $this->assertEquals('cheese',$keys[0]);
    }

    public function testFindSuitableRecipeTest()
    {
        $recipes = array(
            'grilled cheese on toast' => array(
                'bread' => array(
                    'amount'=>2,
                    'unit' => 'slices'
                ),
                'cheese' => array(
                    'amount'=>2,
                    'unit' => 'slices'
                ),
            ),
            'salad sandwich' => array(
                'bread' => array(
                    'amount'=>2,
                    'unit' => 'slices'
                ),
                'mixed salad' => array(
                    'amount'=>100,
                    'unit' => 'grams'
                ),
            ),
        );
        $fridgeIngredients = array(
            'bread' => array(
                'amount'=>10,
                'unit' => 'slices',
                'date' => '25/12/2014'
            ),
            'cheese' => array(
                'amount'=>10,
                'unit' => 'slices',
                'date' => '25/12/2014'
            ),
            'butter' => array(
                'amount'=>250,
                'unit' => 'grams',
                'date' => '25/12/2014'
            ),
            'peanut butter' => array(
                'amount'=>250,
                'unit' => 'grams',
                'date' => '2/12/2014'
            ),
            'mixed salad' => array(
                'amount'=>150,
                'unit' => 'grams',
                'date' => '26/12/2013'
            ),
        );
        $controller = new RecipeController();
        $data = $controller->findSuitableRecipe($recipes,$fridgeIngredients);
        $this->assertCount(2,$data);
    }

    public function testGetRecommendRecipeTest()
    {
        $recipes = array(
            'grilled cheese on toast' => array(
                'bread' => array(
                    'amount'=>2,
                    'unit' => 'slices'
                ),
                'cheese' => array(
                    'amount'=>2,
                    'unit' => 'slices'
                ),
            ),
            'salad sandwich' => array(
                'bread' => array(
                    'amount'=>2,
                    'unit' => 'slices'
                ),
                'mixed salad' => array(
                    'amount'=>100,
                    'unit' => 'grams'
                ),
            ),
        );
        $fridgeIngredients = array(
            'bread' => array(
                'amount'=>10,
                'unit' => 'slices',
                'date' => '25/12/2014'
            ),
            'cheese' => array(
                'amount'=>10,
                'unit' => 'slices',
                'date' => '25/12/2014'
            ),
            'butter' => array(
                'amount'=>250,
                'unit' => 'grams',
                'date' => '25/12/2014'
            ),
            'peanut butter' => array(
                'amount'=>250,
                'unit' => 'grams',
                'date' => '2/12/2014'
            ),
            'mixed salad' => array(
                'amount'=>150,
                'unit' => 'grams',
                'date' => '26/12/2013'
            ),
        );
        $controller = new RecipeController();
        $data = $controller->getRecommendRecipe($fridgeIngredients,$recipes);
        $this->assertEquals('salad sandwich',$data);
    }

    public function testOrderTakeoutTest()
    {
        $recipes = array(
            'grilled cheese on toast' => array(
                'bread' => array(
                    'amount'=>2,
                    'unit' => 'slices'
                ),
                'cheese' => array(
                    'amount'=>2,
                    'unit' => 'slices'
                ),
            ),
            'salad sandwich' => array(
                'bread' => array(
                    'amount'=>2,
                    'unit' => 'slices'
                ),
                'mixed salad' => array(
                    'amount'=>100,
                    'unit' => 'grams'
                ),
            ),
        );
        $fridgeIngredients = array(
            'bread' => array(
                'amount'=>1,
                'unit' => 'slices',
                'date' => '25/12/2017'
            ),
            'cheese' => array(
                'amount'=>10,
                'unit' => 'slices',
                'date' => '25/12/2018'
            ),
            'butter' => array(
                'amount'=>250,
                'unit' => 'grams',
                'date' => '25/12/2018'
            ),
            'peanut butter' => array(
                'amount'=>250,
                'unit' => 'grams',
                'date' => '2/12/2017'
            ),
            'mixed salad' => array(
                'amount'=>150,
                'unit' => 'grams',
                'date' => '26/12/2019'
            ),
        );
        $controller = new RecipeController();
        $data = $controller->getRecommendRecipe($fridgeIngredients,$recipes);
        $this->assertEquals('',$data);
    }
}
