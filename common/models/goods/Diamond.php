<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_diamond}}".
 *
 * @property int $id
 * @property string $goods_sn 商品编码
 * @property string $goods_image 主图
 * @property int $goods_num 商品数量
 * @property string $cert_type 证书类型
 * @property string $cert_id 证书号
 * @property string $market_price 市场价
 * @property string $sale_price 销售价
 * @property string $cost_price
 * @property double $carat 石重
 * @property string $clarity 净度
 * @property string $cut 切工
 * @property string $color 颜色
 * @property int $shape 形状
 * @property string $depth_lv 深度
 * @property string $table_lv 台宽
 * @property string $symmetry 对称
 * @property string $polish 抛光
 * @property string $fluorescence 荧光
 * @property int $source_id 货品来源 0手动录制
 * @property string $source_discount 来源折扣
 * @property int $is_stock 库存类型 1现货 0期货
 * @property int $status 状态：1上架 0下架
 * @property int $created_at
 * @property int $updated_at
 */
class Diamond extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_diamond}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','goods_num', 'shape', 'source_id', 'is_stock', 'status', 'created_at', 'updated_at'], 'integer'],
            [[ 'sale_price','source_id','shape','goods_num','goods_sn', 'carat', 'clarity', 'cut', 'color', 'symmetry', 'polish', 'fluorescence'], 'required'],
            [['market_price', 'sale_price', 'cost_price', 'carat', 'source_discount'], 'number'],
            [['goods_sn'], 'string', 'max' => 60],
            [['goods_image'], 'string', 'max' => 100],
            [['cert_type', 'cut', 'color', 'symmetry', 'polish', 'fluorescence'], 'string', 'max' => 10],
            [['cert_id'], 'string', 'max' => 30],
            [['clarity'], 'string', 'max' => 40],
            [['depth_lv', 'table_lv'], 'string', 'max' => 20],
            [['cert_id'], 'unique'],
            [['parame_images'],'parseParameImages'],
            [['sale_services'],'parseSaleServices'],
            [['goods_name','language'],'safe'],
            [['cert_type', 'cert_id'], 'unique', 'targetAttribute' => ['cert_type', 'cert_id']],
        ];
    }

    /**
 * 款式图库
 */
    public function parseParameImages()
    {
        if(is_array($this->parame_images)){
            $this->parame_images = implode(',',$this->parame_images);
        }
        return $this->parame_images;
    }

    /**
     * 售后服务
     */
    public function parseSaleServices()
    {
        if(is_array($this->sale_services)){
            $this->sale_services = implode(',',$this->sale_services);
        }
        return $this->sale_services;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('goods_diamond', 'ID'),
            'goods_sn' => '商品编码',
            'goods_image' => '主图',
            'goods_num' => '商品数量',
            'cert_type' => '证书类型',
            'cert_id' => '证书号',
            'market_price' => '市场价',
            'sale_price' => '销售价',
            'cost_price' => Yii::t('goods_diamond', '成本价'),
            'carat' => '石重',
            'clarity' => '净度',
            'cut' => '切工',
            'color' => '颜色',
            'shape' => '形状',
            'depth_lv' => '长度',
            'table_lv' => '宽度',
            'symmetry' => '对称',
            'polish' => '抛光',
            'stone_floor' => '石底层',
            'stone_surface' => '石面',
            'aspect_ratio' => '长宽比',
            'cutting_depth' => '切割深度',
            'sale_services' => '售后服务',
            'goods_3ds' => '360°主圖',
            'parame_images' => '参数示意图',
            'goods_gia_image' => 'GIA证书图片',
            'fluorescence' => '荧光',
            'source_id' => '货品来源 ',
            'source_discount' => '来源折扣',
            'is_stock' => '库存类型',
            'status' => '状态',
            'created_at' => Yii::t('goods_diamond', '创建时间'),
            'updated_at' => Yii::t('goods_diamond', '更新时间'),
        ];
    }


    /**
     * 语言扩展表
     * @return \common\models\goods\AttributeLang
     */
    public function langModel()
    {
        return new DiamondLang();
    }

    public function getLangs()
    {
        return $this->hasMany(DiamondLang::class,['master_id'=>'id']);

    }

    /**
     * 关联语言一对一
     * @param string $languge
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        $query = $this->hasOne(DiamondLang::class, ['master_id'=>'id'])->alias('lang')->where(['lang.language' => Yii::$app->params['language']]);
        return $query;
    }
}
