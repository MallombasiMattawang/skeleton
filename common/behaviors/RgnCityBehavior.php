<?php

namespace common\behaviors;

use Yii;
use yii\base\Event;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use common\models\RgnCity;

/**
 * handling city property
 * when typing a name instead of selecting, it will be inserted as new city
 *
 * @author fredy
 */
class RgnCityBehavior extends AttributeBehavior
{

	/**
	 * name of province property where the city belong
	 *
	 * @var string
	 */
	public $provinceAttribute = 'province_id';

	/**
	 * name of city property to handle
	 *
	 * @var string
	 */
	public $cityAttribute = 'city_id';

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
				BaseActiveRecord::EVENT_BEFORE_INSERT	 => $this->cityAttribute,
				BaseActiveRecord::EVENT_BEFORE_UPDATE	 => $this->cityAttribute,
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
		$attribute = $this->cityAttribute;
		$value = $this->owner->$attribute;

		$parentAttribute = $this->provinceAttribute;
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
			$model = new RgnCity([
				'name'			 => $value,
				'province_id'	 => $parent_id,
				'recordStatus'	 => RgnCity::RECORDSTATUS_USED,
			]);

			return $model->save(FALSE) ? $model->id : 0;
		}

	}

}
