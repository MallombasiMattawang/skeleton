<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "rgn_country".
 *
 * @property integer $id
 * @property string $recordStatus
 * @property string $name
 * @property string $abbreviation
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 * @property integer $createdBy_id
 * @property integer $updatedBy_id
 * @property integer $deletedBy_id
 *
 * @property \common\models\RgnPostcode[] $rgnPostcodes
 * @property \common\models\RgnProvince[] $rgnProvinces
 */
class RgnCountry extends \common\base\Model
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
		return 'rgn_country';

	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['recordStatus'], 'string'],
			[['name'], 'required'],
			[['created_at', 'updated_at', 'deleted_at', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'], 'integer'],
			[['name'], 'string', 'max' => 255],
			[['abbreviation'], 'string', 'max' => 32],
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
			'name'			 => 'Name',
			'abbreviation'	 => 'Abbreviation',
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
	public function getRgnPostcodes()
	{
		return $this->hasMany(\common\models\RgnPostcode::className(), ['country_id' => 'id']);

	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRgnProvinces()
	{
		return $this->hasMany(\common\models\RgnProvince::className(), ['country_id' => 'id']);

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
