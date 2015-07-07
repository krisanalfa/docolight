<?php

namespace Docoflow\Models;

use CDbCriteria;
use CActiveRecord;
use CActiveDataProvider;
use Docoflow\Traits\Validable;
use Docoflow\Traits\HasMutator;
use Docoflow\Contracts\ValidationStatus;

/**
 * This is the model class for table "workflow_step".
 *
 * The followings are the available columns in table 'workflow_step':
 *
 * @property integer $id
 * @property integer $workflow_id
 * @property string  $name
 * @property integer $status
 * @property string  $expired_at
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class WorkflowStep extends CActiveRecord implements ValidationStatus
{
    use HasMutator, Validable;

    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className active record class name.
     *
     * @return WorkflowStep the static model class
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
        return 'workflow_step';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('workflow_id', 'required'),
            array('workflow_id, status', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            array('expired_at', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, workflow_id, name, status, expired_at', 'safe', 'on' => 'search'),
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
            'workflow' => array(static::BELONGS_TO, '\Docoflow\Models\Workflow', 'workflow_id'),
            'groups' => array(static::HAS_MANY, '\Docoflow\Models\WorkflowGroups', 'workflow_step_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'workflow_id' => 'Workflow',
            'name' => 'Name',
            'status' => 'Status',
            'expired_at' => 'Expired At',
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
        $criteria->compare('workflow_id', $this->workflow_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('expired_at', $this->expired_at, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
