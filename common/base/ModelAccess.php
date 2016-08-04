<?php

namespace common\base;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\web\HttpException;
use cornernote\returnurl\ReturnUrl;
use common\widgets\Icon;

/**
 * ModelAccess
 * providing access control for model
 *
 * @author fredy
 */
class ModelAccess extends \yii\base\Object
{

	const ERROR_CODE = 423;

	const MENU_DIVIDER = '<li role="presentation" class="divider"></li>';

	/**
	 * cached access permission
	 *
	 * @var Array
	 */
	static $allowed = [];

	/**
	 * generated error when checking permission
	 *
	 * @var Array
	 */
	static $errorMessages = [];

	//* ================ general ================ */

	/**
	 * check named access
	 * first check cached permission
	 * or call related function
	 *
	 * @param String $name
	 * @param Array $params parameter for called function
	 * @return boolean
	 */
	static function allow($name = '', $params = [])
	{
		$method = 'allow' . Inflector::camelize($name);

		if (array_key_exists($name, static::$allowed))
		{
			return static::$allowed[$name];
		}
		else if (method_exists(static::className(), $method))
		{
			return call_user_func_array([static::className(), $method], [$params]);
		}
		else
		{
			return FALSE;
		}

	}

	/**
	 * get link parameters for menu access
	 *
	 * @param String $name
	 * @param Array $options
	 * @return Array
	 */
	static function params($name = '', $options = ['url' => '#'])
	{
		$method = 'params' . Inflector::camelize($name);

		if (method_exists(static::className(), $method))
		{
			return ArrayHelper::merge(static::$method(), $options);
		}

		return $options;

	}

	/**
	 * prepare parameter for regular link or button
	 *
	 * @param Array $param
	 * @param Array $use used feature: icon|button
	 * @return Array
	 */
	static function prepareParam($param, $use = [])
	{
		$icon = ArrayHelper::remove($param, 'icon');
		$buttonOptions = ArrayHelper::remove($param, 'buttonOptions');

		if (in_array('icon', $use) && $icon)
		{
			$label = ArrayHelper::getValue($param, 'label', '');
			$param['label'] = $icon . ' ' . $label;
		}

		if (in_array('button', $use) && $buttonOptions)
		{
			$linkOptions = ArrayHelper::getValue($param, 'linkOptions', []);
			$param['linkOptions'] = ArrayHelper::merge($linkOptions, $buttonOptions);
		}

		return $param;

	}

	/**
	 * get url parameter for particular access
	 *
	 * @param String $name
	 * @param Array $options
	 * @return Array
	 */
	static function url($name = '', $options = [])
	{
		$params = static::params($name);

		return ArrayHelper::merge($params['url'], $options);

	}

	//* ================ error messages ================ *//

	/**
	 * store error message for particular access
	 *
	 * @param String $name
	 * @param String $message
	 */
	static function setError($name = '', $message = '')
	{
		if ($name != '' && $message != '')
		{
			static::$errorMessages[$name] = $message;
		}

	}

	/**
	 * get error message for particular access
	 *
	 * @param String $name
	 * @return String
	 */
	static function getError($name = '')
	{
		return array_key_exists($name, static::$errorMessages) ? static::$errorMessages[$name] : NULL;

	}

	/**
	 * throw exception for controller
	 *
	 * @param String $name access name
	 * @return HttpException
	 */
	static function exception($name = '')
	{
		return new HttpException(static::ERROR_CODE, static::getError($name));

	}

	//* ================ link ================ */

	/**
	 * generate regular link
	 *
	 * @param String $name
	 * @param Array $options
	 * @return String
	 */
	static function a($name, $options = [])
	{
		if (is_string($options))
		{
			$options = ['label' => $options];
		}

		$params = static::params($name, $options);

		$label = ArrayHelper::getValue($params, 'label');
		$linkOptions = ArrayHelper::getValue($params, 'linkOptions', []);
		$urlOptions = ArrayHelper::getValue($params, 'urlOptions', []);

		$allow = static::allow($name);

		if ($allow)
		{
			$url = ArrayHelper::merge($params['url'], $urlOptions);
		}
		else
		{
			$url = '#';
			$linkOptions['title'] = static::getError($name);
		}

		return Html::a($label, $url, $linkOptions);

	}

	/**
	 * generate link if allowed
	 *
	 * @param String $name
	 * @param Array $options
	 * @return String
	 */
	static function link($name, $options = [])
	{
		$allow = static::allow($name);

		if ($allow)
		{
			return static::a($name, $options);
		}

		return NULL;

	}

	/**
	 * generate button link
	 *
	 * @param String $name
	 * @param Array $options
	 * @return String
	 */
	static function btn($name, $options = [])
	{
		if (is_string($options))
		{
			$options = ['label' => $options];
		}

		$params = static::params($name, $options);
		$params = static::prepareParam($params, ['icon', 'button']);

		$label = ArrayHelper::getValue($params, 'label');
		$linkOptions = ArrayHelper::getValue($params, 'linkOptions', []);
		$urlOptions = ArrayHelper::getValue($params, 'urlOptions', []);

		$allow = static::allow($name);

		if ($allow)
		{
			$url = ArrayHelper::merge($params['url'], $urlOptions);
		}
		else
		{
			$url = '#';
			$linkOptions['title'] = static::getError($name);
		}

		return Html::a($label, $url, $linkOptions);

	}

	/**
	 * generate button link if allowed
	 *
	 * @param String $name
	 * @param Array $options
	 * @return String
	 */
	static function button($name, $options = [])
	{
		$allow = static::allow($name);

		if ($allow)
		{
			return static::btn($name, $options);
		}

		return NULL;

	}

	//* ================ routing ================ */

	/**
	 * return route to controller
	 *
	 * @return string
	 */
	static function controllerRoute()
	{
		return '';

	}

	/**
	 * return route to access added with oontroller route
	 *
	 * @param string $action
	 * @return string
	 */
	static function actionRoute($action)
	{
		$controllerRoute = static::controllerRoute();

		return ($controllerRoute !== '') ? "/{$controllerRoute}/{$action}" : $action;

	}

	//* ================ widget ================ *//

	/**
	 * generate items parameter for dropdown menu
	 *
	 * @param array $items access list to be shown
	 * @return array
	 */
	public function dropdownItems($items = [])
	{
		$params = [];
		$count = 0;
		$lastParam = NULL;

		foreach ($items as $item)
		{
			if (is_string($item) && $item !== static::MENU_DIVIDER)
			{
				$param = static::params($item);
				$allowed = static::allow($item);

				if ($param && $allowed)
				{
					$param = static::prepareParam($param, ['icon']);

					$params[] = $param;
					$lastParam = $param;
					$count++;
				}
			}
			else if (is_array($item) OR ( $count > 0 && $item !== $lastParam ))
			{
				$params[] = $param;
				$lastParam = $param;
				$count++;
			}
		}

		return $params;

	}

	/**
	 * generate dropdown widget
	 *
	 * @param array $items
	 * @param array $options
	 * @return string
	 */
	public function widgetDropdown($items = [], $options = [])
	{
		$buttonConfig = [
			'id'			 => Inflector::camel2id(get_called_class()),
			'encodeLabel'	 => false,
			'label'			 => 'Action',
			'dropdown'		 => [
				'options'		 => [
					'class' => 'dropdown-menu-' . $options['align'],
				],
				'encodeLabels'	 => false,
				'items'			 => static::dropdownItems($items),
			],
			'options'		 => [
				'class' => 'btn btn-primary',
			],
		];

		if ($options)
		{
			$buttonConfig = ArrayHelper::merge($buttonConfig, $options);
		}

		/* dropdown menu */
		return \yii\bootstrap\ButtonDropdown::widget($buttonConfig);

	}

	/**
	 * generate regular link widget
	 *
	 * @param array $items
	 * @param string $link_separator
	 * @return string
	 */
	public function widgetLink($items = [], $link_separator = ' &centerdot; ')
	{
		$links = [];

		foreach ($items as $item => $options)
		{
			if (is_int($item))
			{
				$item = $options;
				$options = [];
			}

			if (static::allow($item))
			{
				$links[] = static::a($item, $options);
			}
		}

		return ($links) ? implode($link_separator, $links) : '';

	}

	/**
	 * generate button widget
	 *
	 * @param array $items
	 * @param string $align : left|right
	 * @return string
	 */
	public function widgetButton($items = [], $align = 'left')
	{
		$links = [];

		foreach ($items as $item => $options)
		{
			if (is_int($item))
			{
				$item = $options;
				$options = [];
			}

			if (static::allow($item))
			{
				$links[] = static::btn($item, $options);
			}
		}

		if ($links)
		{
			$output = "<p class=\"pull-{$align}\">\n";

			$output .= implode("\n", $links);

			return $output . "\n</p>";
		}

		return '';

	}

	//* ================ arbitary access control ================ */

	/**
	 * check permission for accesing Index page
	 *
	 * @return boolean
	 */
	static function allowIndex()
	{
		if (array_key_exists('index', static::$allowed) == FALSE)
		{
			static::$allowed['index'] = TRUE;
		}

		return static::$allowed['index'];

	}

	/**
	 * check permission for accesing Create new model page
	 *
	 * @return boolean
	 */
	static function allowCreate()
	{
		if (array_key_exists('create', static::$allowed) == FALSE)
		{
			static::$allowed['create'] = TRUE;
		}

		return static::$allowed['create'];

	}

	//* ================ arbitary params ================ */

	/**
	 * return 'index' link parameter
	 *
	 * @return array
	 */
	static function paramsIndex()
	{
		return [
			'url'			 => [
				static::actionRoute('index'),
				'ru' => ReturnUrl::getToken(),
			],
			'label'			 => 'List',
			'icon'			 => Icon::create('list'),
			'buttonOptions'	 => [
				'class' => 'btn btn-primary',
			],
		];

	}

	/**
	 * return 'create' link parameter
	 *
	 * @return array
	 */
	static function paramsCreate()
	{
		return [
			'url'			 => [
				static::actionRoute('create'),
				'ru' => ReturnUrl::getToken(),
			],
			'label'			 => 'Create',
			'icon'			 => Icon::create('plus'),
			'buttonOptions'	 => [
				'class' => 'btn btn-primary',
			],
		];

	}

}
