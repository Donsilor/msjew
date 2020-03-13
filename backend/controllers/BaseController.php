<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\UnauthorizedHttpException;
use common\components\BaseAction;
use common\helpers\Auth;
use common\behaviors\ActionLogBehavior;
use common\components\Page;

/**
 * Class BaseController
 * @package backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class BaseController extends Controller
{
    use BaseAction;
    use Page;
    
    protected $authOptional = [];
    /**
     * @return array
     */
    public function behaviors()
    {
        //Yii::$app->language = 'zh-CN';
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // 登录
                    ],
                ],
            ],
            'actionLog' => [
                'class' => ActionLogBehavior::class
            ]
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws UnauthorizedHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // 每页数量
        $this->pageSize = Yii::$app->request->get('per-page', 10);
        $this->pageSize > 50 && $this->pageSize = 50;

        // 判断当前模块的是否为主模块, 模块+控制器+方法
        $permissionName = '/' . Yii::$app->controller->route;
        // 判断是否忽略校验
        if (in_array($permissionName, Yii::$app->params['noAuthRoute'])) {
            return true;
        }
        
        //权限白名单
        $actionId = Yii::$app->controller->action->id;
        if(in_array($actionId,$this->authOptional)) {
            return true;
        }
            
        // 开始权限校验
        if (!Auth::verify($permissionName)) {
            throw new UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
        }

        return true;
    }
    /**
     * 局部默认语言
     * @param string $language
     */
    public function setLocalLanguage($language = '')
    {
        //切换默认语言
        Yii::$app->params['language'] = $language ? $language : Yii::$app->params['language']; 
    }

    /**
     * 局部默认地区
     * @param string $language
     */
    public function setLocalAreaId($area_id = '')
    {
        //切换默认语言   
        Yii::$app->params['areaId'] = $area_id ? $area_id : Yii::$app->params['areaId'];
    }
}