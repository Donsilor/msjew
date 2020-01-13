<?php

namespace api\modules\web\controllers\goods;

use api\controllers\OnAuthController;
use common\models\goods\Style;
/**
 * Class ProvincesController
 * @package api\modules\v1\controllers\member
 */
class CommentsController extends OnAuthController
{

    /**
     * @var Provinces
     */
    public $modelClass = Style::class;
    protected $authOptional = ['index'];

    /**
     * 商品评论
     * @return array
     */
    public function actionIndex()
    {
        $result['page'] = 1;
        $result['data'] = null;
        $result['page_size'] = null;
        $result['page_size'] = null;
        $result['page_count'] = null;

        return $result;
        
    }

    
    
    
}