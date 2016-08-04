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
 * Description of ModelOperation
 *
 * @author fredy
 *
 * @property Model $model
 * @property String $linkView
 *
 * @property Bool $allowView
 * @property Bool $allowUpdate
 * @property Bool $allowDelete
 *
 * @property Array $paramsView
 * @property Array $paramsUpdate
 * @property Array $paramsDelete
 */
class ModelOperation extends \yii\base\Object
{

	const ERROR_CODE = 423;

	const MENU_DIVIDER = '<li role="presentation" class="divider"></li>';

	const TYPE_DROPDOWN = 'dropdown';

	const TYPE_LINK = 'link';

	const TYPE_BUTTON = 'button';

	/**
	 * model tro control
	 *
	 * @var Model
	 */
	public $model;

	/**
	 * store permission for checked operation
	 *
	 * @var array
	 */
	public $allowed = [];

	/**
	 * error messages generated while checking permission
	 *
	 * @var type
	 */
	public $errorMessages = [];

	/**
	 * separator when generating regular link list
	 *
	 * @var string
	 */
	public $link_separator = ' &centerdot; ';

	/**
	 * widget type to generate
	 *
	 * @var string
	 */
	public $type = 'dropdown';

	/**
	 * default operation to show
	 *
	 * @var array
	 */
	public $items = ['view', 'update', 'delete'];

	/**
	 * widget align
	 *
	 * @var string : left|right
	 */
	public $align = 'right';

	//* ================ general ================ *//

	/**
	 * check for particular data access permission
	 * first check cached permission
	 * then execute related filter function
	 * if fail, deny permission
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function allow($name = '', $params = [])
	{
		$method = 'getAllow' . Inflector::camelize($name);

		if (array_key_exists($name, $this->allowed))
		{
			return $this->allowed[$name];
		}
		else if (method_exists($this, $method))
		{
			return call_user_func_array([$this, $method], [$params]);
		}
		else
		{
			return FALSE;
		}

	}

	/**
	 * get link parameters for operation menu
	 *
	 * @param String $name
	 * @param Array $options
	 * @return Array
	 */
	public function params($name = '', $options = [])
	{
		$method = 'getParams' . Inflector::camelize($name);

		if (method_exists($this, $method))
		{
			return ArrayHelper::merge(['url' => '#'], $this->$method(), $options);
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
	public function prepareParam($param, $use = [])
	{
		$icon = ArrayHelper::remove($param, 'icon');
		$buttonOptions = ArrayHelper::remove($param, 'buttonOptions');

		if (in_array('icon', $use) && $icon && isset($param['label']))
		{
			$param['label'] = $icon . ' ' . $param['label'];
		}

		if (in_array('button', $use) && $buttonOptions && isset($param['linkOptions']))
		{
			$param['linkOptions'] = ArrayHelper::merge($param['linkOptions'], $buttonOptions);
		}

		return $param;

	}

	/**
	 * get url parameter for particular operation
	 *
	 * @param String $name
	 * @param Array $options
	 * @return Array
	 */
	public function url($name = '', $options = [])
	{
		$params = $this->params($name);

		return ArrayHelper::merge($params['url'], $options);

	}

	//* ================ error messages ================ *//

	/**
	 * store operation error messages
	 *
	 * @param string $name
	 * @param string $message
	 */
	public function setError($name = '', $message = '')
	{
		if ($name != '' && $message != '')
		{
			$this->errorMessages[$name] = $message;
		}

	}

	/**
	 * get error message for particular operation
	 *
	 * @param string $name
	 * @return string
	 */
	public function getError($name = '')
	{
		return array_key_exists($name, $this->errorMessages) ? $this->errorMessages[$name] : NULL;

	}

	/**
	 * throw exception for controller
	 *
	 * @param string $name
	 * @return HttpException
	 */
	public function exception($name = '')
	{
		return new HttpException(static::ERROR_CODE, $this->getError($name));

	}

	//* ================ hyperlink ================ *//

	/**
	 * generate regular link
	 *
	 * @param String $name
	 * @param Array $options
	 * @return String
	 */
	public function a($name, $options = [])
	{
		if (is_string($options))
		{
			$options = ['label' => $options];
		}

		$params = $this->params($name, $options);

		$label = ArrayHelper::getValue($params, 'label');
		$linkOptions = ArrayHelper::getValue($params, 'linkOptions', []);
		$urlOptions = ArrayHelper::getValue($params, 'urlOptions', []);

		$allow = $this->allow($name);

		if ($allow)
		{
			$url = ArrayHelper::merge($params['url'], $urlOptions);
		}
		else
		{
			$url = '#';
			$linkOptions['title'] = $this->getError($name);
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
	public function link($name, $options = [])
	{
		$allow = $this->allow($name);

		if ($allow)
		{
			return $this->a($name, $options);
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
	public function btn($name, $options = [])
	{
		if (is_string($options))
		{
			$options = ['label' => $options];
		}

		$params = $this->params($name, $options);

		$label = ArrayHelper::getValue($params, 'label');
		$linkOptions = ArrayHelper::getValue($params, 'linkOptions', []);
		$urlOptions = ArrayHelper::getValue($params, 'urlOptions', []);

		$icon = ArrayHelper::getValue($params, 'icon');
		$buttonOptions = ArrayHelper::getValue($params, 'buttonOptions', []);

		$label = $icon . ' ' . $label;
		$linkOptions = ArrayHelper::merge($linkOptions, $buttonOptions);

		$allow = $this->allow($name);

		if ($allow)
		{
			$url = ArrayHelper::merge($params['url'], $urlOptions);
		}
		else
		{
			$url = '#';
			$linkOptions['title'] = $this->getError($name);
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
	public function button($name, $options = [])
	{
		$allow = $this->allow($name);

		if ($allow)
		{
			return $this->btn($name, $options);
		}

		return NULL;

	}

	/**
	 * generate link to page that show model detail
	 *
	 * @param string $label
	 * @param array $linkOptions
	 * @return string
	 */
	public function getLinkView($label = '', $linkOptions = ['title' => 'view detail'])
	{
		if ($label === '')
		{
			if ($this->model->hasAttribute('name'))
			{
				$label = $this->model->getAttribute('name');
			}
			else
			{
				$label = 'view';
			}
		}

		$url = $this->url('view');

		return Html::a($label, $url, $linkOptions);

	}

	//* ================ routing ================ *//

	/**
	 * return route to controller
	 *
	 * @return string
	 */
	public function controllerRoute()
	{
		return '';

	}

	/**
	 * return route to access added with oontroller route
	 *
	 * @param string $action
	 * @return string
	 */
	public function actionRoute($action)
	{
		$controllerRoute = $this->controllerRoute();

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
				$param = $this->params($item);
				$allowed = $this->allow($item);

				if ($param && $allowed)
				{
					$params[] = $this->prepareParam($param, ['icon']);
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
		if (!$items)
		{
			$items = $this->items;
		}

		$primaryKey = $this->model->primaryKey()[0];

		$buttonConfig = [
			'id'			 => Inflector::camel2id($this->model->tableName()) . '_' . $this->model->getAttribute($primaryKey),
			'encodeLabel'	 => false,
			'label'			 => 'Action',
			'dropdown'		 => [
				'options'		 => [
					'class' => 'dropdown-menu-' . $this->align,
				],
				'encodeLabels'	 => false,
				'items'			 => $this->dropdownItems($items),
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
	public function widgetLink($items = [])
	{
		if (!$items)
		{
			$items = $this->items;
		}

		$links = [];

		foreach ($items as $item => $options)
		{
			if (is_int($item))
			{
				$item = $options;
				$options = [];
			}

			if ($this->allow($item))
			{
				$links[] = $this->a($item, $options);
			}
		}

		return ($links) ? implode($this->link_separator, $links) : '';

	}

	/**
	 * generate button widget
	 *
	 * @param array $items
	 * @param string $align : left|right
	 * @return string
	 */
	public function widgetButton($items = [])
	{
		if (!$items)
		{
			$items = $this->items;
		}

		$links = [];

		foreach ($items as $item => $options)
		{
			if (is_int($item))
			{
				$item = $options;
				$options = [];
			}

			if ($this->allow($item))
			{
				$links[] = $this->btn($item, $options);
			}
		}

		if ($links)
		{
			$output = "<p class=\"pull-{$this->align}\">\n";

			$output .= implode("\n", $links);

			return $output . "\n</p>";
		}

		return '';

	}

	//* ================ arbitary access control ================ *//

	/**
	 * return permision to view model detail
	 *
	 * @return boolean
	 */
	public function getAllowView()
	{
		// some serious permission control
		if (array_key_exists('view', $this->allowed) == FALSE)
		{
			// default permission
			$this->allowed['view'] = FALSE;

			// prerequisites check
			if ($this->model->isNewRecord)
			{
				$this->setError('view', "Cann't view unsaved Data.");
			}
			// action whitelist
			else
			{
				$this->allowed['view'] = TRUE;
			}
		}

		// final result
		return $this->allowed['view'];

	}

	/**
	 * return permision to open model update form
	 *
	 * @return boolean
	 */
	public function getAllowUpdate()
	{
		// some serious permission control
		if (array_key_exists('update', $this->allowed) == FALSE)
		{
			// default permission
			$this->allowed['update'] = FALSE;

			// prerequisites check
			if ($this->model->isNewRecord)
			{
				$this->setError('update', "Cann't view unsaved Data.");
			}
			// action whitelist
			else
			{
				$this->allowed['update'] = TRUE;
			}
		}

		// final result
		return $this->allowed['update'];

	}

	/**
	 * return permision to delete model
	 *
	 * @return boolean
	 */
	public function getAllowDelete()
	{
		// some serious permission control
		if (array_key_exists('delete', $this->allowed) == FALSE)
		{
			// default permission
			$this->allowed['delete'] = FALSE;

			// prerequisites check
			if ($this->model->isNewRecord)
			{
				$this->setError('delete', "Cann't delete unsaved data.");
			}
			else if ($this->model->isSoftDeleteEnabled && $this->model->getAttribute('deleted_at') > 0)
			{
				$this->setError('delete', 'Data already (soft) deleted.');
			}
			// action whitelist
			else
			{
				$this->allowed['delete'] = TRUE;
			}
		}

		// final result
		return $this->allowed['delete'];

	}

	//* ================ arbitary params ================ *//

	/**
	 * return 'view' link parameter
	 *
	 * @return array
	 */
	public function getParamsView()
	{
		$primaryKey = $this->model->primaryKey()[0];

		return [
			'url'			 => [
				$this->actionRoute('view'),
				$primaryKey	 => $this->model->getAttribute($primaryKey),
				'ru'		 => ReturnUrl::getToken(),
			],
			'label'			 => 'View',
			'icon'			 => Icon::create('eye-open'),
			'buttonOptions'	 => [
				'class' => 'btn btn-primary',
			],
		];

	}

	/**
	 * return 'update' link parameter
	 *
	 * @return array
	 */
	public function getParamsUpdate()
	{
		$primaryKey = $this->model->primaryKey()[0];

		return [
			'url'			 => [
				$this->actionRoute('update'),
				$primaryKey	 => $this->model->getAttribute($primaryKey),
				'ru'		 => ReturnUrl::getToken(),
			],
			'label'			 => 'Update',
			'icon'			 => Icon::create('pencil'),
			'buttonOptions'	 => [
				'class' => 'btn btn-primary',
			],
		];

	}

	/**
	 * return 'delete' link parameter
	 *
	 * @return array
	 */
	public function getParamsDelete()
	{
		$primaryKey = $this->model->primaryKey()[0];

		return [
			'url'			 => [
				$this->actionRoute('delete'),
				$primaryKey	 => $this->model->getAttribute($primaryKey),
				'ru'		 => ReturnUrl::getToken(),
			],
			'label'			 => 'Delete',
			'icon'			 => Icon::create('trash'),
			/* basic link options */
			'linkOptions'	 => [
				'data-confirm'	 => 'Are you sure to delete this item?',
				'data-method'	 => 'post',
				'class'			 => 'text text-danger',
			],
			/* button option, will overwrite link-options when rendering button */
			'buttonOptions'	 => [
				'class' => 'btn btn-danger',
			],
		];

	}

}
