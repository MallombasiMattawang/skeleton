<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "rgn_district".
 *
 * @property integer $id
 * @property string $recordStatus
 * @property string $number
 * @property string $name
 * @property integer $city_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 * @property integer $createdBy_id
 * @property integer $updatedBy_id
 * @property integer $deletedBy_id
 *
 * @property \common\models\RgnCity $city
 * @property \common\models\RgnPostcode[] $rgnPostcodes
 * @property \common\models\RgnSubdistrict[] $rgnSubdistricts
 */
class RgnDistrict extends \common\base\Model
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
		return 'rgn_district';

	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['recordStatus'], 'string'],
			[['name'], 'required'],
			[['city_id', 'created_at', 'updated_at', 'deleted_at', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'], 'integer'],
			[['number'], 'string', 'max' => 32],
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
			'city_id'		 => 'City ID',
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
	public function getCity()
	{
		return $this->hasOne(\common\models\RgnCity::className(), ['id' => 'city_id']);

	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRgnPostcodes()
	{
		return $this->hasMany(\common\models\RgnPostcode::className(), ['district_id' => 'id']);

	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRgnSubdistricts()
	{
		return $this->hasMany(\common\models\RgnSubdistrict::className(), ['district_id' => 'id']);

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
