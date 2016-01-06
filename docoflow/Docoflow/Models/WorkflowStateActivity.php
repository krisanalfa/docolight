<?php

namespace Docoflow\Models;

use Yii;
use CDbCriteria;
use CActiveRecord;
use CActiveDataProvider;

/**
 * This is the model class for table "workflow_state_activity".
 *
 * The followings are the available columns in table 'workflow_state_activity':
 *
 * @property int $id
 * @property int $activity_id
 * @property int $state_id
 * @property int $assignor
 * @property int $assignee
 * @property float $is_failed
 * @property float $is_notification
 * @property string $notification_subject
 * @property string $notification_body
 */
class WorkflowStateActivity extends CActiveRecord
{
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
     * Returns the static model of the specified AR class.
     *
     * @param string $className active record class name.
     *
     * @return WorkflowStateActivity the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '"workflow_state_activity"';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('activity_id, state_id, assignor, assignee', 'required'),
            array('activity_id, state_id, assignor, assignee', 'numerical', 'integerOnly' => true),
            array('is_failed, is_notification', 'numerical'),
            array('notification_subject, notification_body', 'length', 'max' => 512),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, activity_id, state_id, assignor, assignee, is_failed, is_notification, notification_subject, notification_body', 'safe', 'on' => 'search'),
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
            'activity' => [static::BELONGS_TO, WorkflowActivity::class, 'activity_id'],
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'activity_id' => 'Activity',
            'state_id' => 'State',
            'assignor' => 'Assignor',
            'assignee' => 'Assignee',
            'is_failed' => 'Is Failed',
            'is_notification' => 'Is Notification',
            'notification_subject' => 'Notification Subject',
            'notification_body' => 'Notification Body',
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
        $criteria->compare('"activity_id"', $this->activity_id);
        $criteria->compare('"state_id"', $this->state_id);
        $criteria->compare('"assignor"', $this->assignor);
        $criteria->compare('"assignee"', $this->assignee);
        $criteria->compare('"is_failed"', $this->is_failed);
        $criteria->compare('"is_notification"', $this->is_notification);
        $criteria->compare('"notification_subject"', $this->notification_subject, true);
        $criteria->compare('"notification_body"', $this->notification_body, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
