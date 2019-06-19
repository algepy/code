<?php

namespace app\models;

use Yii;
use yii\mongodb\ActiveRecord;

class MArticles extends ActiveRecord {
    /**
     * @inheritdoc
     */
    
    public static function deleteOld() {
        $oldTime  = (int) strtotime("-1 month");        
        $filter  =  [];
        $filter['time'] = ['$lt'=>$oldTime];
        foreach (self::find()->where($filter)->all() as $model) {
            $model->delete();
        }
    }
    
    public static function collectionName()
    {
        return 'articles';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'url', 'lang', 'keyword_id', 'status','title','domain','content','time','tr','id'];
    }    
    
}
