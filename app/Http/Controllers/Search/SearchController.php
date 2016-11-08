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
        $request = $client->request('POST','http://172.22.161.66:9200/jwzx/category/_search',
            [
                'json'=>[
                    'query' =>[
                        'match'=>[
                            'text' => '教务'
                        ]
                    ],
                    'highlight'=>[
                        'pre_tags' => '<div color ="red">',
                        'post_tags'=> '</div>',
                        'fields' => [
                            'text' => (object)[]
                        ]
                    ]
                ]
            ]);

        $goal = json_decode($request->getBody());
        $goal_array = $goal->hits->hits;
        
        foreach ($goal_array as $key => $value) {
            echo $goal_array[$key]->highlight->text[0];exit;
        }
        exit;
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
                    'pre_tags'  => '<em>',
                    'post_tags' => '</em>',
                    'text' =>[]
                ]
            ]
        ];
        $response = $search->search($params);
        print_r($response);

        //var_dump($search);
    }

    public function getNewMovies(){
        $client = new Client();
        $request = $client->request('POST','http://172.22.161.11/index/',
            [   
                'query' => [
                    'id' => '1'
                ]
            ]);

        $content=$request->GetBody();
        echo $content;exit;
        $pattern = '<div class="poster">
        <a href="javascript:void(0)" url="/torrent/showInfo/id/7452.html" class="window">
            <img src="http://172.22.161.11/upload/pic/2c38b094a17e875661b36a1edc5811aa.jpg" alt="【美国】自杀小队"  />
        </a>
    </div>';
        $goal = $this->_patternGoal($pattern,$content);

        exit; 
    }

    private function _patternGoal($pattern,$string){//匹配函数
        preg_match_all($pattern,$string,$goalarray);
        return $goalarray;
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
