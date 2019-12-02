<?php

namespace common\widgets\skutable;

use Yii;
use yii\base\Widget;
use common\widgets\skutable\assets\AppAsset;

/**
 * Class Provinces
 * @package common\widgets\provinces
 * @author jianyan74 <751393839@qq.com>
 */
class SkuTable extends Widget
{
    /**
     * 省字段名
     *
     * @var
     */
    public $skuType = 'skuType';

    /**
     * 市字段名
     *
     * @var
     */
    public $skuInputVal = 'skuInputVal';

    /**
     * 区字段名
     *
     * @var
     */
    public $skuValue = 'skuValue';

    /**
     * 显示类型
     *
     * long/short
     *
     * @var string
     */
    public $template = 'default';

    /**
     * 关联的ajax url
     *
     * @var
     */
    public $url;

    /**
     * 级别
     *
     * @var int
     */
    public $level = 3;

    /**
     * 模型
     *
     * @var array
     */
    public $model;

    /**
     * 表单
     * @var
     */
    public $form;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $asset = AppAsset::register($this->getView());
        
        empty($this->url) && $this->url = Yii::$app->urlManager->createUrl(['/provinces/index']);
    }

    /**
     * @return string
     */
    public function run()
    {
        $this->skuType =  [
            [
                'id'=>1,
                'name'=>'存储11',
                'sku_value'=>[
                    [
                        'id'=>1,
                        'name'=>'16G'
                    ],
                    [
                        'id'=>2,
                        'name'=>'32G'
                    ],
                    [
                        'id'=>3,
                        'name'=>'64G'
                    ],
                    [
                        'id'=>4,
                        'name'=>'128G'
                    ],
                ]
            ],
            [
                'id'=>2,
                'name'=>'版本',
                'sku_value'=>[
                    [
                        'id'=>11,
                        'name'=>'中国大陆版'
                    ],
                    [
                        'id'=>12,
                        'name'=>'港版'
                    ],
                ]
            ]

        ];
        $this->skuValue = [1,3,4,11,12];
        return $this->render($this->template, [
            'form' => $this->form,
            'model' => $this->model,
            'skuType' => $this->skuType,
            'skuInputVal' => $this->skuInputVal,
            'skuValue' => $this->skuValue,
            'url' => $this->url,
            'level' => $this->level,
        ]);
    }
}

?>