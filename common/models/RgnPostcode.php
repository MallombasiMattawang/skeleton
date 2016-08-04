<?php

namespace common\models;

use Yii;
use common\models\base\RgnPostcode as BaseRgnPostcode;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "rgn_postcode".
 *
 * @property string $recordStatusLabel
 */
class RgnPostcode extends BaseRgnPostcode
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
			[['postcode', 'country_id'], 'required'],
			/* optional type */
			[['subdistrict_id', 'district_id', 'city_id', 'province_id'], 'safe'],
			/* field type */
			[['recordStatus'], 'string'],
			[['postcode'], 'integer'],
			/* value limitation */
			['recordStatus', 'in', 'range' => [
					self::RECORDSTATUS_USED,
					self::RECORDSTATUS_DELETED,
				]
			],
			[
				'country_id',
				'exist',
				'targetClass'		 => RgnCountry::className(),
				'targetAttribute'	 => 'id',
				'when'				 => function (RgnPostcode $model, $attribute)
				{
					$num = is_numeric($model->$attribute);
					//$model->addError($attribute, "num: [{$num}]; val: [{$model->$attribute}];");

					return $num;
				},
				'message' => "Country doesn't exist.",
			],
			[
				'province_id',
				'exist',
				'targetClass'		 => RgnProvince::className(),
				'targetAttribute'	 => 'id',
				'when'				 => function ($model, $attribute)
				{
					return is_numeric($model->$attribute);
				},
				'message' => "Province doesn't exist.",
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
			[
				'district_id',
				'exist',
				'targetClass'		 => RgnDistrict::className(),
				'targetAttribute'	 => 'id',
				'when'				 => function ($model, $attribute)
				{
					return is_numeric($model->$attribute);
				},
				'message' => "District doesn't exist.",
			],
			[
				'subdistrict_id',
				'exist',
				'targetClass'		 => RgnSubdistrict::className(),
				'targetAttribute'	 => 'id',
				'when'				 => function ($model, $attribute)
				{
					return is_numeric($model->$attribute);
				},
				'message' => "Subdistrict doesn't exist.",
			],
		];

	}

	/**
	 * search model based on number & country
	 *
	 * @param integer $number
	 * @param integer $country_id
	 * @return RgnPostcode
	 */
	static function findNumber($number, $country_id)
	{
		return static::findOne([
				'postcode'	 => $number,
				'country_id' => $country_id,
		]);

	}

	/**
	 * Revalidate and/or save postcode
	 *
	 * @param type $param
	 * @return type
	 */
	static function check($param = [])
	{
		$country_id = ArrayHelper::getValue($param, 'country_id');
		$postcode = ArrayHelper::getValue($param, 'postcode');

		if ($country_id > 0 && $postcode > 0)
		{
			$model = static::findNumber($postcode, $country_id);

			if (is_null($model))
			{
				$postcode = new RgnPostcode($param);

				return ($postcode->save(FALSE)) ? $postcode : NULL;
			}

			return $model->improveData($param);
		}

	}

	/**
	 * improve data & save it
	 *
	 * @param array $newData
	 * @return \common\models\RgnPostcode
	 */
	public function improveData($newData)
	{
		$improved = FALSE;
		$attributes = [
			'province_id',
			'city_id',
			'district_id',
			'subdistrict_id',
		];

		/*
		 * compare each attributes
		 */

		foreach ($attributes as $attr)
		{
			$oldValue = $this->getAttribute($attr);
			$newValue = ArrayHelper::getValue($newData, $attr);

			/*
			 * if old value is empty but new value exist, improve it
			 */

			if (empty($oldValue) && $newValue > 0)
			{
				$this->setAttribute($attr, $newValue);

				$improved = TRUE;
			}

			/*
			 * if old & new value exist but they are different, don't change any data, just leave it that way. improvement done.
			 */
			else if ($oldValue > 0 && $newValue > 0 && $oldValue != $newValue)
			{
				break;
			}
		}

		/*
		 * if data improved, save it
		 */

		if ($improved)
		{
			$this->save(FALSE);
		}

		return $this;

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
			'postcode'			 => 'Postcode',
			'subdistrict_id'	 => 'Subdistrict',
			'district_id'		 => 'District',
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
