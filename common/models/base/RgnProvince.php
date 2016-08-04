<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "rgn_province".
 *
 * @property integer $id
 * @property string $recordStatus
 * @property string $number
 * @property string $name
 * @property string $abbreviation
 * @property integer $country_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 * @property integer $createdBy_id
 * @property integer $updatedBy_id
 * @property integer $deletedBy_id
 *
 * @property \common\models\RgnCity[] $rgnCities
 * @property \common\models\RgnPostcode[] $rgnPostcodes
 * @property \common\models\RgnCountry $country
 */
class RgnProvince extends \common\base\Model
{

	/**
	 * ENUM field values
	 */
	const RECORDSTATUS_USED = 'used';

	const RECORDSTATUS_DELETED = 'deleted';

	var $enum_labels = false;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'rgn_province';

	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['recordStatus'], 'string'],
			[['name'], 'required'],
			[['country_id', 'created_at', 'updated_at', 'deleted_at', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'], 'integer'],
			[['number', 'abbreviation'], 'string', 'max' => 32],
			[['name'], 'string', 'max' => 255],
			['recordStatus', 'in', 'range' => [
					self::RECORDSTATUS_USED,
					self::RECORDSTATUS_DELETED,
				]
			]
		];

	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'			 => 'ID',
			'recordStatus'	 => 'Record Status',
			'number'		 => 'Number',
			'name'			 => 'Name',
			'abbreviation'	 => 'Abbreviation',
			'country_id'	 => 'Country ID',
			'created_at'	 => 'Created At',
			'updated_at'	 => 'Updated At',
			'deleted_at'	 => 'Deleted At',
			'createdBy_id'	 => 'Created By ID',
			'updatedBy_id'	 => 'Updated By ID',
			'deletedBy_id'	 => 'Deleted By ID',
		];

	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRgnCities()
	{
		return $this->hasMany(\common\models\RgnCity::className(), ['province_id' => 'id']);

	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRgnPostcodes()
	{
		return $this->hasMany(\common\models\RgnPostcode::className(), ['province_id' => 'id']);

	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCountry()
	{
		return $this->hasOne(\common\models\RgnCountry::className(), ['id' => 'country_id']);

	}

	/**
	 * get column recordStatus enum value label
	 * @param string $value
	 * @return string
	 */
	public static function getRecordStatusValueLabel($value)
	{
		$labels = self::optsRecordStatus();

		if (isset($labels[$value]))
		{
			return $labels[$value];
		}

		return $value;

	}

	/**
	 * column recordStatus ENUM value labels
	 * @return array
	 */
	public static function optsRecordStatus()
	{
		return [
			self::RECORDSTATUS_USED		 => 'Used',
			self::RECORDSTATUS_DELETED	 => 'Deleted',
		];

	}

}
