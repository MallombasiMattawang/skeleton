<?php

namespace common\models;

use Yii;
use common\models\base\RgnCountry as BaseRgnCountry;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "rgn_country".
 *
 * @property string $recordStatusLabel
 */
class RgnCountry extends BaseRgnCountry
{

	/**
	 * Preparing option data for forms
	 *
	 * @return array
	 */
	static function asOption()
	{
		$query = static::find()->all();

		return ArrayHelper::map($query, 'id', 'name');

	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			/* default value */
			['recordStatus', 'default', 'value' => static::RECORDSTATUS_USED],
			/* required */
			[['name'], 'required'],
			/* field type */
			[['recordStatus'], 'string'],
			[['name'], 'string', 'max' => 255],
			[['abbreviation'], 'string', 'max' => 32],
			[['created_at', 'updated_at', 'deleted_at', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'], 'integer'],
			/* value limitation */
			['recordStatus', 'in', 'range' => [
					self::RECORDSTATUS_USED,
					self::RECORDSTATUS_DELETED,
				]
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
			'name'				 => 'Name',
			'abbreviation'		 => 'Abbreviation',
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
