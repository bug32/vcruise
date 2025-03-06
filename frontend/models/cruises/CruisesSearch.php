<?php

namespace frontend\models\cruises;

use frontend\models\ships\Ships;
use Yii;
use yii\caching\TagDependency;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Exception;
use yii\db\Query;
use yii\db\QueryBuilder;


/**
 * This is the model class for table "cruises".
 *
 * @property int $page
 * @property int $limit
 */
class CruisesSearch extends Cruises
{
    public const CACHE_TTL          = YII_ENV_PROD ? 3600 : 1;
    public const DEFAULT_PAGE_LIMIT = 20;

    public $limit;
    public $page;

    public function rules(): array
    {
        return [
            ['id', 'integer'],
            [['ship_id', 'type_id'], 'integer'],
            [['limit', 'page'], 'integer'],
            ['limit', 'default', 'value' => self::DEFAULT_PAGE_LIMIT],
            ['page', 'filter', 'filter' => fn($page) => $page > 0 ? $page : 1],
            [['date_start', 'date_end'], 'string'],

            [['type_id'], 'default', 'value' => 3] // 3 - круизы по рекам России
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
            'type_id',

            'photos',
            'cityMedias',
            'ship' => 'shipShort'
        ];
    }

    public function search(mixed $get)
    {
        $key        = [
            __CLASS__,
            __METHOD__,
            implode('_', $get),
        ];
        $dependency = new TagDependency([
            'tags' => [
                __CLASS__,
                Cruises::class,
            ],
        ]);

        $dataProvider = Yii::$app->cache->get($key);

        if ($dataProvider) {
            Yii::warning('Cache ', 'cache');
            return $dataProvider;
        }
        Yii::warning('No cache ', 'cache');

        $query        = static::find()->joinWith(['ship','photos','cityMedias']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($get, '');
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->select([
            'cruises.name', 'cruises.id', 'cruises.ship_id', 'cruises.slug',
            'cruises.description', 'cruises.route', 'cruises.route_short',
            'cruises.date_start', 'cruises.date_end', 'cruises.days', 'cruises.nights',
            'cruises.min_price', 'cruises.currency', 'cruises.free_cabins',
            'cruises.type_id', 'cruises.parent_cruise',
        ]);

        $query->groupBy(['cruises.id']);

        $query->andFilterWhere([Cruises::tableName() . '.id' => $this->id]);

        // Фильтр по типу круиза (море или река)
        $query->andFilterWhere([Cruises::tableName() . '.type_id' => $this->type_id]);

        // Фильтр по дате начала
        $this->date_start = date('Y-m-d', $this->date_start ? strtotime($this->date_start) : time());
        $query->andFilterWhere(['>=', Cruises::tableName() . '.date_start', $this->date_start]);


        // Взять только активные круизы
        $query->andFilterWhere([
            Cruises::tableName() . '.status' => \common\models\Cruises::STATUS_ACTIVE,
        ]);

        // Фильтр по теплоходу
        $query->andFilterWhere([Cruises::tableName() . '.ship_id' => $this->ship_id]);


        $dataProvider->pagination->setPageSize($this->limit);
        $dataProvider->getModels();

        Yii::$app->cache->set($key, $dataProvider, self::CACHE_TTL, $dependency);

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

        if ($this->ship_id) {
            $q->andWhere(['c.ship_id' => $this->ship_id]);
        }

        $q->limit($this->limit)
            ->offset(($this->page - 1) * $this->limit);

        return $q->all();
    }
}