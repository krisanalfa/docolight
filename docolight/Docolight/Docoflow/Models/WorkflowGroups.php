<?php

namespace Docolight\Docoflow\Models;

use CDbCriteria;
use CActiveRecord;
use CActiveDataProvider;
use Docolight\Docoflow\Traits\HasMutator;

/**
 * This is the model class for table "workflow_groups".
 *
 * The followings are the available columns in table 'workflow_groups':
 *
 * @property integer $id
 * @property integer $workflow_step_id
 * @property string  $name
 * @property integer $status
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class WorkflowGroups extends CActiveRecord
{
    use HasMutator;

    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className active record class name.
     *
     * @return WorkflowGroups the static model class
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
        return 'workflow_groups';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('workflow_step_id', 'required'),
            array('workflow_step_id, status', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, workflow_step_id, name, status', 'safe', 'on' => 'search'),
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
            'step' => array(static::BELONGS_TO, '\Docolight\Docoflow\Models\WorkflowStep', 'workflow_step_id'),
            'verificators' => array(static::HAS_MANY, '\Docolight\Docoflow\Models\WorkflowVerificator', 'workflow_groups_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'workflow_step_id' => 'Workflow Step',
            'name' => 'Name',
            'status' => 'Status',
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
        $criteria->compare('workflow_step_id', $this->workflow_step_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
