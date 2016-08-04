<?php

namespace common\behaviors;

use Yii;
use yii\base\Event;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use common\models\RgnSubdistrict;

/**
 * handling subdistrict property
 * when typing a name instead of selecting, it will be inserted as new subdistrict
 *
 * @author fredy
 */
class RgnSubdistrictBehavior extends AttributeBehavior
{

	/**
	 * name of district property where the subdistrict belong
	 *
	 * @var string
	 */
	public $districtAttribute = 'district_id';

	/**
	 * name of district property to handle
	 *
	 * @var string
	 */
	public $subdistrictAttribute = 'subdistrict_id';

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
				BaseActiveRecord::EVENT_BEFORE_INSERT	 => $this->subdistrictAttribute,
				BaseActiveRecord::EVENT_BEFORE_UPDATE	 => $this->subdistrictAttribute,
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
		$attribute = $this->subdistrictAttribute;
		$value = $this->owner->$attribute;

		$parentAttribute = $this->districtAttribute;
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
			$model = new RgnSubdistrict([
				'name'			 => $value,
				'district_id'	 => $parent_id,
				'recordStatus'	 => RgnSubdistrict::RECORDSTATUS_USED,
			]);

			return $model->save(FALSE) ? $model->id : 0;
		}

	}

}
