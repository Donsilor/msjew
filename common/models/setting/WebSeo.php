<?php

namespace common\models\setting;

use Yii;

/**
 * This is the model class for table "{{%web_seo}}".
 *
 * @property int $id 主键
 * @property string $page_name 页面名称
 * @property string $create_time 创建时间
 * @property string $update_time 修改时间
 */
class WebSeo extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%web_seo}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['page_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'page_name' => '页面',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }


    /**
     * 语言扩展表
     * @return \common\models\goods\AttributeLang
     */
    public function langModel()
    {
        return new WebSeoLang();
    }

    public function getLangs()
    {
        return $this->hasMany(WebSeoLang::class,['master_id'=>'id']);

    }

    /**
     * 关联语言一对一
     * @param string $languge
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        $query = $this->hasOne(WebSeoLang::class, ['master_id'=>'id']);
        return $query;
    }
}
