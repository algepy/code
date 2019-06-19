<?php
namespace app\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Exception;

class ServersController extends Controller {
    public $options = array(
        'http' => array(
            'method'=>"GET",
            'max_redirects' => 3,            
            'follow_location' => true,
            'timeout' => 10,
        )
    );
    
    public function actionIndex() {
        $api  =  \Yii::$app->params['apiUrl']."servers/clone";
        $data  = json_decode(file_get_contents($api),true);
        return $this->renderPartial('list',['list'=>$data]);
    }
    
    public function actionLinksServersList() {
        
        $key  =  "ServersController::actionLinksServersList";
        $list  =  Yii::$app->cache->get($key);
        
        if($list == false || $list == null || empty($list)) {
            $servers = [
                "clone.o-news.info:2511",
                "clone.o-news.info:2512",
                "clone1.o-news.info:2511",
                "clone1.o-news.info:2512",
                "clone2.o-news.info:2511",
                "clone2.o-news.info:2512"
            ];
            $context            =   stream_context_create($this->options);
            $list  =  [];
            foreach ($servers as $server) {
                $url  =  "http://".$server."/stats/";
                $jsonData  = @file_get_contents($url, false, $context);
                $json  = json_decode($jsonData,true);
                if(empty($json) == false && isset($json['status']) && $json['status'] == "ok") {                
                    $time  = $json['uptime'];
                    $list[$server] = [
                        'status'=>1,
                        'uptime'=>gmdate("H:i:s", $time)
                    ];
                } else {
                    $list[$server] = [
                        'status'=>0,
                        'uptime'=>0
                    ];
                }
            }
        }
        Yii::$app->cache->set($key, $list,5);
        return $this->renderPartial('links-servers-list',['list'=>$list]);
    }
    
    
}
