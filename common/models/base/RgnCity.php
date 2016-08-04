<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "rgn_city".
 *
 * @property integer $id
 * @property string $recordStatus
 * @property string $number
 * @property string $name
 * @property string $abbreviation
 * @property integer $province_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 * @property integer $createdBy_id
 * @property integer $updatedBy_id
 * @property integer $deletedBy_id
 *
 * @property \common\models\RgnProvince $province
 * @property \common\models\RgnDistrict[] $rgnDistricts
 * @property \common\models\RgnPostcode[] $rgnPostcodes
 */
class RgnCity extends \common\base\Model
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
		return 'rgn_city';

	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['recordStatus'], 'string'],
			[['province_id', 'created_at', 'updated_at', 'deleted_at', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'], 'integer'],
			[['number'], 'string', 'max' => 32],
			[['name'], 'string', 'max' => 255],
			[['abbreviation'], 'string', 'max' => 64],
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
			'province_id'	 => 'Province ID',
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
	public function getProvince()
	{
		return $this->hasOne(\common\models\RgnProvince::className(), ['id' => 'province_id']);

	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRgnDistricts()
	{
		return $this->hasMany(\common\models\RgnDistrict::className(), ['city_id' => 'id']);

	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRgnPostcodes()
	{
		return $this->hasMany(\common\models\RgnPostcode::className(), ['city_id' => 'id']);

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
