<?php
/**
 * Author: lf
 * Blog: https://blog.feehi.com
 * Email: job@feehi.com
 * Created at: 2017-08-13 12:12
 */

namespace backend\models\search;

use Yii;
use backend\behaviors\TimeSearchBehavior;
use backend\components\search\SearchEvent;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class UserSearch extends \frontend\models\User implements SearchInterface
{

    public function behaviors()
    {
        return [
            TimeSearchBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'created_at', 'updated_at'], 'string'],
            ['status', 'integer'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @param array $params
     * @param array $options
     * @return \yii\data\ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function search(array $params = [], array $options = [])
    {
        $query = self::find();
        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = Yii::createObject([
            'class' => ActiveDataProvider::className(),
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'updated_at' => SORT_DESC,
                    'username' => SORT_ASC,
                ]
            ]
        ]);
        $this->load($params);
        if (! $this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['=', 'status', $this->status]);

        $this->trigger(SearchEvent::BEFORE_SEARCH, Yii::createObject([ 'class' => SearchEvent::className(), 'query'=>$query]));
        return $dataProvider;
    }

}