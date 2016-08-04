<?php

namespace common\widgets;

use yii\helpers\Html;
use cebe\gravatar\Gravatar as BaseGravatar;

/**
 * Implements gravatar profile image
 *
 * To use this widget, you may insert the following code in a view:
 *
 * ```
 * echo \common\widget\Gravatar::widget([
 *     'email'			=> 'mail@cebe.cc',
 *     'size'			=> 128,
 *     'defaultImage'	=> 'monsterid',
 * //  'secure'			=> false, // will be autodetected
 *     'rating'			=> 'r',
 *     'options'		=> [
 *         'alt'	=>'Gravatar image',
 *         'title'	=>'Gravatar image',
 *     ],
 *     'linkUrl'		=> 'http://en.gravatar.com/connect/',
 *     'linkOptions'	=>[
 *         'alt'	=>'Change Gravatar image',
 *         'title'	=>'Change Gravatar image',
 *     ]
 * ]);
 *
 * @author fredy
 */
class Gravatar extends BaseGravatar
{

	public $linkUrl = "http://en.gravatar.com/connect/";

	public $linkOptions = [
		'target' => '_blank',
	];

	/**
	 * run widget
	 */
	public function run()
	{
		$output = $this->img();

		if ($this->linkUrl)
		{
			$output = Html::a($output, $this->linkUrl, $this->linkOptions);
		}

		return $output;

	}

	/**
	 * generating image tag
	 *
	 * @return string
	 */
	public function img()
	{
		if (!isset($this->options['alt']))
		{
			$this->options['alt'] = 'Gravatar image';
		}

		return Html::img($this->getImageUrl(), $this->options);

	}

}
