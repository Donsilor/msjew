<?php
/**
 * Created by PhpStorm.
 * User: BDD
 * Date: 2019/11/25
 * Time: 14:07
 */
namespace common\enums;

class DiamondEnum extends  BaseEnum
{

    //证书类型
    //const COLOR = 'color';
    public static $typeOptions = [
        1 => '颜色',
    ];
    public static function getCertTypeList()
    {
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::DIA_CERT_TYPE);
    }
    //钻石颜色   
    //const COLOR = 'color';
    public static $colorOptions = [
        1 => '颜色',
    ]; 
    //钻石净度
    //const CLARITY = 'clarity';
    public static $clarityOptions = [
        1=>'净度'            
    ];
    //钻石切工
    //const CUT = 'cut';
    public static $cutOptions = [
        1=>'切工',
    ];
    //钻石形状
    //const SHAPE = 'shape';
    public static $shapeOptions = [
       1=>'形状'
    ];
    //对称
    public static $symmetryOptions = [
       1=>'对称'
    ];
    //抛光
    public static $polishOptions = [
       1=>'抛光'
    ];
    //荧光
    public static $fluorescenceOptions = [
       1=>'荧光'
    ];
    
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
                
        ];
    }
}








