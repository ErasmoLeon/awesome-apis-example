<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    private $twitterService;
    private $meaningCloudService;
    private $mercadoLibreService;
    private $clarifaiService;

    public function __construct()
    {
        $this->twitterService = app()->make('TwitterService');
        $this->meaningCloudService = app()->make('MeaningCloudService');
        $this->mercadoLibreService = app()->make('MercadoLibreService');
        $this->clarifaiService = app()->make('ClarifaiService');
    }

    public function search(Request $request)
    {
        $query = $request->input("q");
        $tweets = $this->twitterService->searchTweets($query);

        $text = join(',', $tweets);

        $topics = $this->meaningCloudService->getTopicByText($text);

        $searchQuery = join(',', $topics);

        $items = $this->mercadoLibreService->searchItems($searchQuery);

        return response()->json($items);
    }

    public function searchByImageUrl(Request $request)
    {
        $imageUrl = $request->input('url');

        $words = $this->clarifaiService->searchEntitiesByUrl($imageUrl);

        $items = $this->mercadoLibreService->searchItems(join(',', $words));

        return response()->json($items);
    }

}
