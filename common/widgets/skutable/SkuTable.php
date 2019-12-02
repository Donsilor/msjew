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
     * SKU数据
     *'data' =>[
            [
                    'id'=>1,
                    'name'=>'颜色',
                    'value'=>[
                             1=>'16G',
                             2=>'32G',                                                 
                             3=>'64G',                                              
                             4=>'128G',
                    ],
                    'current'=>[1,3,4]
            ],
            [
                    'id'=>2,
                    'name'=>'净度',
                    'value'=>[
                            11=>'SI',
                            12=>'V'
                     ],
                    'current'=>[11]
            ]
            
    ],
     * @var
     */
    public $data;


    /**
     * 模板
     *
     * long/short
     *
     * @var string
     */
    public $template = 'default';

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

    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render($this->template, [
            'form' => $this->form,
            'model' => $this->model,
            'data' => $this->data,
        ]);
    }
}

?>