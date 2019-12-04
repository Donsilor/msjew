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

    public $inputAttrs;
    private $inputAttrName = '';
    private $inputAttrTitle = '';
    private $inputAttrRequire = '';
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $asset = AppAsset::register($this->getView());
        if(!isset($this->inputAttrs)){
            $this->inputAttrs =  [
                    ['name'=>'goods_sn','title'=>'商品编码','require'=>1],                    
                    ['name'=>'market_price','title'=>'市场价','require'=>0],
                    ['name'=>'sale_price','title'=>'销售价','require'=>1],
                    ['name'=>'goods_storage','title'=>'库存','require'=>1],
                    ['name'=>'status','title'=>'状态','require'=>0],
            ];
        }
        $inputAttrCode = '';
        foreach ($this->inputAttrs as $attr){
            $this->inputAttrName .= $attr['name'].',';
            $this->inputAttrTitle .= $attr['title'].',';
            $this->inputAttrRequire .= $attr['require'].',';
        }
        $this->inputAttrName = rtrim($this->inputAttrName,',');
        $this->inputAttrTitle = rtrim($this->inputAttrTitle,',');
        $this->inputAttrRequire = rtrim($this->inputAttrRequire,',');
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
            'inputAttrName'=>$this->inputAttrName,
            'inputAttrTitle'=>$this->inputAttrTitle, 
            'inputAttrRequire'=>$this->inputAttrRequire,
        ]);
    }
}

?>