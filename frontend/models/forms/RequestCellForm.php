<?php

namespace frontend\models\forms;

use yii\base\Model;

class RequestCellForm extends Model
{

    public  $name;
    public  $phone;
    public  $check;

    public function rules(): array
    {
        return [
            [['name', 'phone', 'check'], 'required'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name'  => 'Имя',
            'phone' => 'Телефон',
        ];
    }


    public function save()
    {

    }
}
