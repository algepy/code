<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "articles".
 *
 * @property integer $id
 * @property integer $keyword_id
 * @property integer $status
 * @property string $lang
 * @property string $url
 * @property string $title
 * @property string $metaDescription
 * @property string $metaKeywords
 * @property string $canonicalLink
 * @property string $domain
 * @property string $tags
 * @property string $links
 * @property string $movies
 * @property string $topMovie
 * @property string $articleText
 * @property string $popularWords
 * @property string $topImage
 * @property string $allImages
 * @property string $last_update
 */
class Articles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'articles';
    }

    
    public static function deleteOld() {
        $oldTime  = (int) strtotime("-10 day");        
        $filter  =  [];
        $filter['last_update'] = ['$lt'=>$oldTime];
        foreach (self::find()->where($filter)->all() as $model) {
            $model->delete();
        }
    }
    
    
    public $images  =  [];
    
    public static function mGetOne($data ) {
        $collection = Yii::$app->mongodb->getCollection('articles');
        $data  =  $collection->findOne($data);
        if(!empty($data)) 
            return [];        
        return $data;
    }
    
    public static function mSet($data) {
        $collection = Yii::$app->mongodb->getCollection('articles');
        $tmp  =  $collection->findOne(['url'=>$data['url']]);
        var_dump($tmp);
        if(!empty($tmp)) 
            return false;        
        return $collection->insert($data);
    }
    
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyword_id', 'status'], 'integer'],
            [['links', 'movies', 'topMovie', 'articleText', 'popularWords', 'allImages'], 'string'],
            [['last_update'], 'safe'],
            [['lang'], 'string', 'max' => 10],
            [['url', 'metaKeywords'], 'string', 'max' => 1000],
            [['title', 'metaDescription'], 'string', 'max' => 2000],
            [['canonicalLink', 'tags', 'topImage'], 'string', 'max' => 500],
            [['domain'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'keyword_id' => 'Keyword ID',
            'status' => 'Status',
            'lang' => 'Language',
            'url' => 'Url',
            'title' => 'Title',
            'metaDescription' => 'Meta Description',
            'metaKeywords' => 'Meta Keywords',
            'canonicalLink' => 'Canonical Link',
            'domain' => 'Domain',
            'tags' => 'Tags',
            'links' => 'Links',
            'movies' => 'Movies',
            'topMovie' => 'Top Movie',
            'articleText' => 'Article Text',
            'popularWords' => 'Popular Words',
            'topImage' => 'Top Image',
            'allImages' => 'All Images',
            'last_update' => 'Last Update',
        ];
    }

    public static function getIfExist($url) {
        $item  = Articles::find()->where(['url'=>$url])->one();
        if(!empty($item))
            return $item;
        return false;
    }

    
    public static function isExist($url) {
        $item  = Articles::find()->where(['url'=>$url])->one();
        if(!empty($item))
            return true;
        return false;
    }
    
    /**
     * @inheritdoc
     * @return ArticlesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ArticlesQuery(get_called_class());
    }
}
