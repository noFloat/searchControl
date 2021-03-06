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
        // $request = $client->request('PUT','http://172.22.161.66:9200/logs/',
        //     [
        //         "jwzx"=>[ 
        //           "properties"=>[  
        //              "ID"=>[
        //                 "type"=>"string",  
        //                 "index"=>"not_analyzed"   
        //              ],              
        //              "NAME"=>[
        //                 "type"=>"string",
        //                 "fields"=>[
        //                     "NAME" =>[
        //                         "type"=>"string"
        //                     ],
        //                     "raw"=>[
        //                         "type"=>"string",
        //                         "index"=>"not_analyzed"  
        //                     ]
        //                 ]                  
        //             ]                  
        //           ]  
        //        ]   

        //     ]);
        $request = $client->request('POST','http://172.22.161.66:9200/jwzx/category/_search',
            [
                'json'=>[
                    // 'query'=>[
                    //     'filter'=>[
                    //         'term'=>[
                    //             'title'=>''
                    //         ]

                    //     ]
                    // ]
                    'query'=>[
                        'script'=>"doc['title']==''"
                    ]
                ]
            ]);
        echo $request->body();
        exit;
        $goal = json_decode($request->getBody());
        $goal_array = $goal->hits->hits;
        //echo $goal->hits->total;exit;
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
        header("Access-Control-Allow-Origin: *");
        $client = new Client();
        $request = $client->request('POST','http://172.22.161.11/index/',
            [   
                'query' => [
                    'id' => '1'
                ]
            ]);

        $content=$request->GetBody();
        $pattern = '/<img src="(.*?)" alt="(.*?)"  \/>/';
        $goal = $this->_patternGoal($pattern,$content);
        for($i=0;$i<3;$i++) {
            $movies[$i]['href'] = 'http://172.22.161.11';
            $movies[$i]['image'] = $goal[1][$i];
            $movies[$i]['title'] = $goal[2][$i];
        }
        echo json_encode($movies);
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
        header("Access-Control-Allow-Origin: *");
        $client = new Client();
        $request = $client->request('POST','http://172.22.161.66:9200/jwzx/category/_search',
            [
                'json'=>[
                    'from' => 0, 
                    'size' => 10, 
                    'query' =>[
                        'match'=>[
                            'text' => $id
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
        $last_array['total'] = $goal->hits->total;
        foreach ($goal_array as $key => $value) {
            $last_array['info'][$key]=$goal_array[$key]->highlight;
        }
        var_dump($last_array);exit;
        echo json_encode($last_array);
        exit;
    }
    /*
     分词重配，全文匹配重配
    */
    public function show_goals(Request $request,$goal,$id)
    {
        //echo 1481949574114 -2592000*1000;exit;

        header("Access-Control-Allow-Origin: *");
        $client = new Client();
        $request = $client->request('POST','http://172.22.161.66:9200/jwzx/category/_search',
            [
                'json'=>[
                    'from' => $id-1, 
                    'size' => 10,
                    'query' =>[
                        'filtered'=>[
                            'filter'=>[
                                'script'=>[
                                    'script'=>"doc['title'].value?1:0"
                                ]
                            ],
                            'query' =>[
                                'match'=>[
                                    'title' => $goal
                                ],                                        
                                'match'=>[
                                    'text' => [
                                        'query'=>$goal,
                                        "minimum_should_match"=> "50%"
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'highlight'=>[
                        'pre_tags' => '<div style = "color:red">',
                        'post_tags'=> '</div>',
                        'fields' => [
                            'text' => (object)[]
                        ]
                    ]
                ]
            ]);
        $goal = json_decode($request->getBody());

        $goal_array = $goal->hits->hits;

        $last_array['total'] = $goal->hits->total;
        $last_array['cut'] = $id;

        list($t1, $t2) = explode(' ', microtime());
        $now_time =  $t2 . ceil( ($t1 * 1000) );
        foreach ($goal_array as $key => $value) {
            if($goal_array[$key]->_source->fetchTime>$now_time){
                $time = $goal_array[$key]->_source->fetchTime - $goal_array[$key]->_source->fetchInterval*1000;
            }else{
                $time = $goal_array[$key]->_source->fetchTime;
            }
            $last_array['info'][$key] = [
                "content" => $goal_array[$key]->highlight,
                "url"     => $goal_array[$key]->_source->baseUrl,
                "title"    => $goal_array[$key]->_source->title,
                "fetch_time"   => $time,
            ];
        }
        echo json_encode($last_array);
        exit;
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
