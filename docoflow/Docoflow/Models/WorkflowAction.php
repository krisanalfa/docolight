<?php

namespace Docoflow\Models;

use Yii;
use CDbCriteria;
use CActiveRecord;
use CActiveDataProvider;

/**
 * This is the model class for table "workflow_action".
 *
 * The followings are the available columns in table 'workflow_action':
 *
 * @property int $id
 * @property int $from_state_activity_id
 * @property int $to_state_activity_id
 */
class WorkflowAction extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className active record class name.
     *
     * @return WorkflowAction the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Get DB Connection
     *
     * @return \CDbConnection
     */
    public function getDbConnection()
    {
        Yii::app()->container->bindIf('docoflow.connection', function ($container) {
            return Yii::app()->db;
        });

        return Yii::app()->container->make('docoflow.connection');
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '"workflow_action"';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('from_state_activity_id, to_state_activity_id', 'required'),
            array('from_state_activity_id, to_state_activity_id', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, from_state_activity_id, to_state_activity_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'fromStateActivity' => [static::BELONGS_TO, WorkflowStateActivity::class, 'from_state_activity_id'],
            'toStateActivity' => [static::BELONGS_TO, WorkflowStateActivity::class, 'to_state_activity_id'],
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'from_state_activity_id' => 'From State Activity',
            'to_state_activity_id' => 'To State Activity',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('"id"', $this->id);
        $criteria->compare('"from_state_activity_id"', $this->from_state_activity_id);
        $criteria->compare('"to_state_activity_id"', $this->to_state_activity_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
