<?php

namespace common\models;

use Yii;
use common\models\base\RgnDistrict as BaseRgnDistrict;
use common\models\RgnCity;

/**
 * This is the model class for table "rgn_district".
 *
 * @property string $recordStatusLabel
 * @property integer $country_id
 * @property integer $province_id
 *
 * @property \common\models\RgnCountry $country
 * @property \common\models\RgnProvince $province
 */
class RgnDistrict extends BaseRgnDistrict
{

	public $country_id;

	public $province_id;

	public $_country;

	public $_province;

	/**
	 * @inheritdoc
	 */
	static function findOne($condition)
	{
		$model = parent::findOne($condition);
		$city = $model->city;

		if ($city)
		{
			$model->province_id = $city->province_id;
			$model->_province = $city->province;

			if ($model->_province)
			{
				$model->country_id = $model->_province->country_id;
			}
		}

		return $model;

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
			'city_id'			 => 'City',
			'province_id'		 => 'Province',
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
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			/* default value */
			['recordStatus', 'default', 'value' => static::RECORDSTATUS_USED],
			/* required */
			[['name', 'city_id'], 'required'],
			/* field type */
			[['recordStatus', 'number'], 'string'],
			[['number'], 'string', 'max' => 32],
			[['name'], 'string', 'max' => 255],
			[['created_at', 'updated_at', 'deleted_at', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'], 'integer'],
			/* value limitation */
			['recordStatus', 'in', 'range' => [
					self::RECORDSTATUS_USED,
					self::RECORDSTATUS_DELETED,
				]
			],
			[
				'city_id',
				'exist',
				'targetClass'		 => RgnCity::className(),
				'targetAttribute'	 => 'id',
				'when'				 => function ($model, $attribute)
				{
					return is_numeric($model->$attribute);
				},
				'message' => "City doesn't exist.",
			],
		];

	}

	/**
	 * @return RgnProvince
	 */
	public function getProvince()
	{
		if ($this->_province === NULL)
		{
			if ($this->city_id > 0)
			{
				$city = $this->city;

				if ($city)
				{
					$this->_province = $city->province;
				}
			}
		}

		return $this->_province;

	}

	/**
	 * @return RgnCountry
	 */
	public function getCountry()
	{
		if ($this->_country === NULL)
		{
			if ($this->province)
			{
				$this->_country = $this->province->country;
			}
		}

		return $this->_country;

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
