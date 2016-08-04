<?php

namespace common\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Icon generate icon tag
 *
 * @author fredy
 */
class Icon
{

	static $prefix = [
		'fa'	 => 'fa fa-',
		'glyph'	 => 'glyphicon glyphicon-',
	];

	static function create($params = [])
	{
		if (is_scalar($params))
		{
			return static::iGlyph($params);
		}

		$tag = ArrayHelper::remove($params, 'tag', 'i');
		$type = ArrayHelper::remove($params, 'type', 'glyph');
		$icon = ArrayHelper::remove($params, 'icon');
		$text = ArrayHelper::remove($params, 'text', '');

		if ($icon)
		{
			self::addClass($params, $type, $icon);

			$html = Html::tag($tag, '', $params);

			if ($text)
			{
				$html .= ' ' . $text;
			}

			return $html;
		}

		return NULL;

	}

	static function cssClass($type, $icon)
	{
		return self::$prefix[$type] . $icon;

	}

	static function addClass(&$options, $type, $icon)
	{
		return Html::addCssClass($options, self::cssClass($type, $icon));

	}

	static function fa($icon = '', $tag = 'i', $options = [])
	{
		$options['tag'] = $tag;
		$options['type'] = 'fa';
		$options['icon'] = $icon;

		return self::create($options);

	}

	static function glyph($icon = '', $tag = 'i', $options = [])
	{
		$options['tag'] = $tag;
		$options['type'] = 'glyph';
		$options['icon'] = $icon;

		return self::create($options);

	}

	static function i($icon = '', $type = 'fa', $options = [])
	{
		$options['tag'] = 'i';
		$options['type'] = $type;
		$options['icon'] = $icon;

		return self::create($options);

	}

	static function iFa($icon = '', $options = [])
	{
		$options['tag'] = 'i';
		$options['type'] = 'fa';
		$options['icon'] = $icon;

		return self::create($options);

	}

	static function iGlyph($icon = '', $options = [])
	{
		$options['tag'] = 'i';
		$options['type'] = 'glyph';
		$options['icon'] = $icon;

		return self::create($options);

	}

	static function span($icon = '', $type = 'fa', $options = [])
	{
		$options['tag'] = 'span';
		$options['type'] = $type;
		$options['icon'] = $icon;

		return self::create($options);

	}

	static function spanFa($icon = '', $options = [])
	{
		$options['tag'] = 'span';
		$options['type'] = 'fa';
		$options['icon'] = $icon;

		return self::create($options);

	}

	static function spanGlyph($icon = '', $options = [])
	{
		$options['tag'] = 'span';
		$options['type'] = 'glyph';
		$options['icon'] = $icon;

		return self::create($options);

	}

	static function a($icon, $label, $url = NULL, $linkOptions = [])
	{
		return Html::a(self::spanFa($icon) . ' ' . $label, $url, $linkOptions);

	}

}
