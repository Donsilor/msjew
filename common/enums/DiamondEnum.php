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
    public static function getCertTypeList()
    {
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::DIA_CERT_TYPE);
    }
    //钻石颜色   
    //const COLOR = 'color';
    public static function getColorList()
    {
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::DIA_COLOR);
    }

    //钻石净度
    public static function getClarityList()
    {
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::DIA_CLARITY);
    }
    //钻石切工
    //const CUT = 'cut';
    public static function getCutList()
    {
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::DIA_CUT);
    }
    //钻石形状
    //const SHAPE = 'shape';
    public static function getShapeList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::DIA_SHAPE);
    }
    //对称
    public static function getSymmetryList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::DIA_SYMMETRY);
    }
    //抛光
    public static function getPolishList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::DIA_POLISH);
    }

    //荧光
    public static function getFluorescenceList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::DIA_FLUORESCENCE);
    }

    
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
                
        ];
    }
}








