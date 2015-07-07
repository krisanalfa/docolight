<?php

namespace Docolight\Docoflow\Models;

use Closure;
use CActiveRecord;
use Docolight\Docoflow\Traits\HasMutator;

/**
 * This is the model class for table "workflow_verificator".
 *
 * The followings are the available columns in table 'workflow_verificator':
 *
 * @property integer $id
 * @property integer $workflow_groups_id
 * @property integer $user_id
 * @property integer $status
 * @property string  $message
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class WorkflowVerificator extends CActiveRecord
{
    use HasMutator;

    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className active record class name.
     *
     * @return WorkflowVerificator the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return CDbConnection database connection
     */
    public function getDbConnection()
    {
        return container('docoflow.connection');
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'workflow_verificator';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('workflow_groups_id, user_id', 'required'),
            array('workflow_groups_id, user_id, status', 'numerical', 'integerOnly' => true),
            array('message', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, workflow_groups_id, user_id, status, message', 'safe', 'on' => 'search'),
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
            'group' => array(static::BELONGS_TO, '\Docolight\Docoflow\Models\WorkflowGroups', 'workflow_groups_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'workflow_groups_id' => 'Workflow Groups',
            'user_id' => 'User',
            'status' => 'Status',
            'message' => 'Message',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('workflow_groups_id', $this->workflow_groups_id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('status', $this->status);
        $criteria->compare('message', $this->message, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Get user information from mutator
     *
     * @return mixed
     */
    public function getUser()
    {
        if (static::hasMutator('user')) {
            return static::callMutator('user', [static::getInstance()]);
        }
    }
}
