<?php
/**
 * Created by PhpStorm.
 * User: BDD
 * Date: 2019/11/25
 * Time: 14:07
 */
namespace common\enums;

class GoodsEnum extends  BaseEnum
{

    //净度
    public static function getClarityList()
    {
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::CLARITY);
    }
    //切工
    public static function getCutList()
    {
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::CUT);
    }
    //主石重量
    public static function getDiaWeightList()
    {
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::WEIGHT);
    }
    //钻石形状
    public static function getShapeList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::SHAPE);
    }
    //颜色
    public static function getColorList()
    {
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::COLOR);
    }
    //荧光
    public static function getFluorescenceList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::FLUORESCENCE);
    }
    //成色
    public static function getFinenessList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::FINENESS);
    }
    //克重
    public static function getGramWeightList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::GRAM_WEIGHT);
    }
    //商品定制
    public static function getProductCustomizationList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::PRODUC_CUSTOMIZATION);
    }
    //售后服务
    public static function getSaleServicesList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::SALE_SERVICES);
    }
    //适用人群
    public static function getForPeopleList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::FOR_PEOPLE);
    }
    //用途
    public static function getUseList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::USE);
    }
    //抛光
    public static function getPolishList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::POLISH);
    }
    //对称
    public static function getSymmetryList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::SYMMETRY);
    }
    //鉴定机构
    public static function getAppraisalAgencyList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::APPRAISAL_AGENCY);
    }
    //证书编号
    public static function getCertificateNoList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::CERTIFICATE_NO);
    }
    //切割深度
    public static function getCuttingDepthList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::CUTTING_DEPTH);
    }
    //石面（%）
    public static function getStoneSurfaceList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::STONE_SURFACE);
    }
    //长度（mm）
    public static function getLengthList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::LENGTH);
    }
    //宽度
    public static function getWidthList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::WIDTH);
    }
    //长宽比
    public static function getAspectRatioList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::ASPECT_RATIO);
    }
    //石底层
    public static function getStoneFloorList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::STONE_FLOOR);
    }
    //尺寸
    public static function getSizeList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::SIZE);
    }
    //对戒款式
    public static function getRingStyleList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::RING_STYLE);
    }
    //订婚戒指款式
    public static function getEngagementStyleList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::ENGAGEMENT_STYLE);
    }
    //高度
    public static function getHeightList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::HEIGHT);
    }
    //扣环
    public static function getBuckleList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::BUCKLE);
    }
    //链类型
    public static function getChainTypeList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::CHAIN_TYPE);
    }
    //副石重量
    public static function getSideStoneWeightList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::SIDE_STONE_WEIGHT);
    }
    //副石数量
    public static function getSideStoneQuantityList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::SIDE_STONE_QUANTITY);
    }
    //副石颜色
    public static function getSideStoneColorList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::SIDE_STONE_COLOR);
    }
    //成色
    public static function getSideStoneClarityList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::SIDE_STONE_CLARITY);
    }
    //证书类型
    public static function getCertTypeList()
    {
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(AttrIdEnum::CERT_TYPE);
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








