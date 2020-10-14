<?php
/**
 * Created by PhpStorm.
 * User: MORESHINE
 * Date: 2019/11/25
 * Time: 14:07
 */
namespace common\enums;

class GoodsEnum extends  BaseEnum
{

    //裸钻信息
    //净度
    public static function getClarityList()
    {
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::CLARITY);
    }
    //切工
    public static function getCutList()
    {
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::CUT);
    }
    //主石重量
    public static function getDiaWeightList()
    {
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::CARAT);
    }
    //钻石形状
    public static function getShapeList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::SHAPE);
    }
    //颜色
    public static function getColorList()
    {
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::COLOR);
    }
    //荧光
    public static function getFluorescenceList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::FLUORESCENCE);
    }
    //成色
    public static function getFinenessList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::FINENESS);
    }
    //克重
    public static function getGramWeightList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::GRAM_WEIGHT);
    }
    //商品定制
    public static function getProductCustomizationList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::PRODUC_CUSTOMIZATION);
    }
    //售后服务
    public static function getSaleServicesList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::SALE_SERVICES);
    }
    //适用人群
    public static function getForPeopleList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::FOR_PEOPLE);
    }
    //用途
    public static function getUseList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::USE);
    }
    //抛光
    public static function getPolishList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::POLISH);
    }
    //对称
    public static function getSymmetryList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::SYMMETRY);
    }
    //鉴定机构
    public static function getAppraisalAgencyList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::APPRAISAL_AGENCY);
    }
    //证书编号
    public static function getCertificateNoList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::CERTIFICATE_NO);
    }
    //切割深度
    public static function getCuttingDepthList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::CUTTING_DEPTH);
    }
    //石面（%）
    public static function getStoneSurfaceList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::STONE_SURFACE);
    }
    //长度（mm）
    public static function getLengthList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::LENGTH);
    }
    //宽度
    public static function getWidthList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::WIDTH);
    }
    //长宽比
    public static function getAspectRatioList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::ASPECT_RATIO);
    }
    //石底层
    public static function getStoneFloorList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::STONE_FLOOR);
    }
    //尺寸
    public static function getSizeList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::SIZE);
    }
    //对戒款式
    public static function getRingStyleList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::RING_STYLE);
    }
    //订婚戒指款式
    public static function getEngagementStyleList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::ENGAGEMENT_STYLE);
    }
    //高度
    public static function getHeightList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::HEIGHT);
    }
    //扣环
    public static function getBuckleList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::BUCKLE);
    }
    //链类型
    public static function getChainTypeList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::CHAIN_TYPE);
    }
    //副石重量
    public static function getSideStoneWeightList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::SIDE_STONE_WEIGHT);
    }
    //副石数量
    public static function getSideStoneQuantityList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::SIDE_STONE_QUANTITY);
    }
    //副石颜色
    public static function getSideStoneColorList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::SIDE_STONE_COLOR);
    }
    //成色
    public static function getSideStoneClarityList(){
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::SIDE_STONE_CLARITY);
    }
    //证书类型
    public static function getCertTypeList()
    {
        return \Yii::$app->services->goodsAttribute->getValuesByAttrId(DiamondEnum::CERT_TYPE);
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








