<?php

namespace App\Http\Controllers\Search;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Elasticsearch\ClientBuilder;
use GuzzleHttp\Client;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = new Client();
        $request = $client->request('GET','http://172.22.161.66:9200/jwzx/category/_search',
            [
                // 'query' => 'q=text:教务',
                // 'highlight'=>'fields=about:教务'
                // 'json'=>[
                //     'query' =>[
                //         'term'=>[
                //             'text' => '教务'
                //         ]
                //     ]
                // ]
                'json'=>[
                    'query' =>[
                        'match'=>[
                            'text' => '教务'
                        ]
                    ],
                    "highlight"=>[
                        "fields" => [
                             "text" => []
                        ]
                    ]
                ]
            ]);
        echo $request->getBody();exit;
        $a = new \Elasticsearch\ClientBuilder();
        $search =  $a->create()->setHosts(['172.22.161.66:9200'])->build();
        $params = [
            'index' => 'jwzx',
            'type' => 'category',
            'body' => [
                'query' => [
                    'match' => [
                        'text' => '教务'
                    ],
  
                ],
                'highlight'=> [
                    "pre_tags"  => '<em>',
                    "post_tags" => '</em>',
                ]
            ]
        ];
        $response = $search->search($params);
        print_r($response);

        //var_dump($search);
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
        //
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
