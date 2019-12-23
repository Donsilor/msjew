<?php

namespace api\modules\v2\forms;

use common\models\api\AccessToken;
use yii\base\Model;

/**
 * Class LoginForm
 * @package api\modules\v1\forms
 * @author jianyan74 <751393839@qq.com>
 */
class BookForm extends Model
{
    public $title;

    public $content;

    public $member_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'member_id'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => '主题',
            'content' => '内容',
            'member_id' => '用户ID',

        ];
    }


}
