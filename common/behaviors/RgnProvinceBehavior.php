<?php

namespace common\behaviors;

use Yii;
use yii\base\Event;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use common\models\RgnProvince;

/**
 * handling province property
 * when typing a name instead of selecting, it will be inserted as new province
 *
 * @author fredy
 */
class RgnProvinceBehavior extends AttributeBehavior
{

	/**
	 * name of country property where the province belong
	 *
	 * @var string
	 */
	public $countryAttribute = 'country_id';

	/**
	 * name of province property to handle
	 *
	 * @var string
	 */
	public $provinceAttribute = 'province_id';

	public $value;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		if (empty($this->attributes))
		{
			$this->attributes = [
				BaseActiveRecord::EVENT_BEFORE_INSERT	 => $this->provinceAttribute,
				BaseActiveRecord::EVENT_BEFORE_UPDATE	 => $this->provinceAttribute,
			];
		}

	}

	/**
	 * Evaluates the value of the user.
	 * The return result of this method will be assigned to the current attribute(s).
	 * @param Event $event
	 * @return mixed the value of the user.
	 */
	protected function getValue($event)
	{
		$attribute = $this->provinceAttribute;
		$value = $this->owner->$attribute;

		$parentAttribute = $this->countryAttribute;
		$parent_id = $this->owner->$parentAttribute;
		$parent_valid = ($parent_id > 0);

		if (is_numeric($value))
		{
			return $value;
		}
		else if (empty($value) OR $parent_valid == FALSE)
		{
			return NULL;
		}
		else
		{
			$model = new RgnProvince([
				'name'			 => $value,
				'country_id'	 => $parent_id,
				'recordStatus'	 => RgnProvince::RECORDSTATUS_USED,
			]);

			return $model->save(FALSE) ? $model->id : 0;
		}

	}

}
