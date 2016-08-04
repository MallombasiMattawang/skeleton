<?php

namespace common\models;

use Yii;
use common\models\base\RgnProvince as BaseRgnProvince;

/**
 * This is the model class for table "rgn_province".
 *
 * @property string $recordStatusLabel
 */
class RgnProvince extends BaseRgnProvince
{

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			/* default value */
			['recordStatus', 'default', 'value' => static::RECORDSTATUS_USED],
			/* required */
			[['name', 'country_id'], 'required'],
			/* field type */
			[['recordStatus'], 'string'],
			[['number', 'abbreviation'], 'string', 'max' => 32],
			[['name'], 'string', 'max' => 255],
			[['created_at', 'updated_at', 'deleted_at', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'], 'integer'],
			/* value limitation */
			['recordStatus', 'in', 'range' => [
					static::RECORDSTATUS_USED,
					static::RECORDSTATUS_DELETED,
				],
			],
			[
				'country_id',
				'exist',
				'targetClass'		 => RgnCountry::className(),
				'targetAttribute'	 => 'id',
				'when'				 => function ($model, $attribute)
				{
					return is_numeric($model->$attribute);
				},
				'message' => "Country doesn't exist.",
			],
		];

	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'				 => 'ID',
			'recordStatus'		 => 'Record Status',
			'recordStatusLabel'	 => 'Record Status',
			'number'			 => 'Number',
			'name'				 => 'Name',
			'abbreviation'		 => 'Abbreviation',
			'country_id'		 => 'Country',
			'created_at'		 => 'Created At',
			'updated_at'		 => 'Updated At',
			'deleted_at'		 => 'Deleted At',
			'createdBy_id'		 => 'Created By',
			'updatedBy_id'		 => 'Updated By',
			'deletedBy_id'		 => 'Deleted By',
		];

	}

	/**
	 * get recordStatus label
	 *
	 * @return string
	 */
	public function getRecordStatusLabel()
	{
		return parent::getRecordStatusValueLabel($this->recordStatus);

	}

	/**
	 * @inheritdoc
	 */
	public function delete()
	{
		$this->recordStatus = static::RECORDSTATUS_DELETED;

		return parent::softDelete();

	}

	/**
	 * @inheritdoc
	 */
	public function restore()
	{
		$this->recordStatus = static::RECORDSTATUS_USED;

		return parent::restore();

	}

}
