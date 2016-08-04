<?php

namespace common\base;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\User;

/**
 * @inheritdoc
 * base class for all model
 *
 * @author fredy
 *
 * @property boolean $isSoftDeleteEnabled
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $deletedBy
 */
class Model extends \yii\db\ActiveRecord
{

	/**
	 * Contain model operation control & link
	 *
	 * @var ModelOperation
	 */
	public $operation;

	/* ======================== model structure ======================== */

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->operation = new ModelOperation([
			'model' => $this
		]);

	}

	/* ======================== model helper ======================== */

	/**
	 * check soft-delete functionality
	 *
	 * @return boolean
	 */
	public function getIsSoftDeleteEnabled()
	{
		return $this->hasAttribute('deleted_at');

	}

	/**
	 * copy values from other model
	 *
	 * @param Model $source
	 * @param Array $attributes
	 */
	public function copyValues($source, $attributes)
	{
		foreach ($attributes as $targetAttribute => $sourceAttribute)
		{
			if (is_integer($targetAttribute))
			{
				$targetAttribute = $sourceAttribute;
			}

			$this->$targetAttribute = ArrayHelper::getValue($source, $sourceAttribute);
		}

	}

	/**
	 * return an attribute from hasOne-relationship
	 *
	 * @param String $relationGetter
	 * @param String $attribute
	 * @param String $default
	 * @return String
	 */
	public function getRelatedAttribute($relationGetter = NULL, $attribute = NULL, $default = NULL)
	{
		if (is_string($relationGetter) && is_string($attribute))
		{
			$relatedModel = $this->$relationGetter()->one();

			if ($relatedModel)
			{
				return ArrayHelper::getValue($relatedModel, $attribute, $default);
			}
		}

		return $default;

	}

	/* ======================== model extend ======================== */

	/**
	 * @inheritdoc
	 *
	 * adding insert time & blamable user
	 */
	public function insert($runValidation = true, $attributes = null)
	{
		if ($this->hasAttribute('created_at'))
		{
			$this->setAttribute('created_at', time());

			if ($this->hasAttribute('createdBy_id'))
			{
				$this->setAttribute('createdBy_id', Yii::$app->user->getId());
			}
		}

		return parent::insert($runValidation, $attributes);

	}

	/**
	 * @inheritdoc
	 *
	 * adding update time & blamable user
	 */
	public function update($runValidation = true, $attributeNames = null)
	{
		if ($this->hasAttribute('updated_at'))
		{
			$this->setAttribute('updated_at', time());

			if ($this->hasAttribute('updatedBy_id'))
			{
				$this->setAttribute('updatedBy_id', Yii::$app->user->getId());
			}
		}

		return parent::update($runValidation, $attributeNames);

	}

	/**
	 * prefering soft-delete instead of deleting permanently
	 * adding delete time & blamable user
	 *
	 * @return boolean
	 */
	public function softDelete()
	{
		/* simpan waktu delete (soft-delete) */

		if ($this->isSoftDeleteEnabled)
		{
			$this->setAttribute('deleted_at', time());

			if ($this->hasAttribute('deletedBy_id'))
			{
				$this->setAttribute('deletedBy_id', Yii::$app->user->getId());
			}

			return parent::update(FALSE);
		}

		/* delete record dr database (hard-delete) */

		return $this->hardDelete();

	}

	/**
	 * restore model after soft delete
	 *
	 * @return boolean
	 */
	public function restore()
	{
		if ($this->isSoftDeleteEnabled)
		{
			$this->setAttribute('deleted_at', NULL);

			if ($this->hasAttribute('deletedBy_id'))
			{
				$this->setAttribute('deletedBy_id', NULL);
			}

			return $this->update(FALSE);
		}

	}

	/**
	 * permanently delete model
	 *
	 * @return boolean
	 */
	public function hardDelete()
	{
		return parent::delete();

	}

	/* ======================== model modifiers ======================== */

	/**
	 * Getting blamable user model based on particular attribute
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getBlamedUser($attribute)
	{
		if ($this->hasAttribute($attribute))
		{
			return $this->hasOne(User::className(), ['id' => $attribute]);
		}

		return NULL;

	}

	/**
	 * Getting blamable user model based for creating model
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->getBlamedUser('createdBy_id');

	}

	/**
	 * Getting blamable user model based for updating model
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getUpdatedBy()
	{
		return $this->getBlamedUser('updatedBy_id');

	}

	/**
	 * Getting blamable user model based for deleting model
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getDeletedBy()
	{
		return $this->getBlamedUser('deletedBy_id');

	}

}
