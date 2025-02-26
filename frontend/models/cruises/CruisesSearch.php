<?php

namespace frontend\models\cruises;

use frontend\models\ships\Ships;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\Query;
use yii\db\QueryBuilder;

class CruisesSearch extends Cruises
{

    public const DEFAULT_PAGE_LIMIT = 20;

    public $limit;
    public $page;
    public $date_start;
    public $date_end;

    public function rules(): array
    {
        return [
            [['limit', 'page'], 'integer'],
            ['limit', 'default', 'value' => self::DEFAULT_PAGE_LIMIT],
            ['page', 'filter', 'filter' => fn($page) => $page > 0 ? $page : 1],
            ['date_start', 'date', 'format' => 'yyyy-MM-dd'],
            ['date_end', 'date', 'format' => 'yyyy-MM-dd'],
        ];
    }

    public function fields(): array
    {
        return [
            'id',
            'name',
            'slug',
            'description',
            'route',
            'route_short',
            'date_start',
            'date_end',
            'days',
            'nights',
            'min_price',
            'currency',
            'free_cabins',
            'parent_cruise',

            'ship'
        ];
    }

    public function search(mixed $get)
    {
        $query        = static::find()->joinWith(['ship',])->limit(10);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($get);
        if (!$this->validate()) {

            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            Cruises::tableName() . '.status' => \common\models\Cruises::STATUS_ACTIVE,
        ]);

        return $dataProvider;
    }

    /**
     * @throws Exception
     */
    public function filter(array $params): \yii\db\DataReader|array
    {
        $this->load($params, '');
        if (!$this->validate()) {
            echo print_r($this->errors, TRUE);
            return [];
        }
        $q = new Query();
        $q->select([
            'c.id', 'c.name', 'c.slug', 'c.description', 'c.route', 'c.route_short', 'c.date_start', 'c.date_end',
            'c.days', 'c.nights', 'c.min_price', 'c.currency', 'c.free_cabins', 'c.parent_cruise',

            's.name as ship_name', 's.slug as ship_slug', 's.id as ship_id'
        ]);

        $q->from(['c' => self::tableName()]);
        $q->leftJoin(['s' => Ships::tableName()], 'c.ship_id = s.id');

        if ($this->date_start && $this->date_end) {
            $q->andWhere(['between', 'c.date_start', $this->date_start, $this->date_end]);
        }

        $q->limit($this->limit)
            ->offset($this->page - 1);

        return $q->all();
    }
}