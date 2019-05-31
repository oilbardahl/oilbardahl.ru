<?php

namespace Bitrix\Report\VisualConstructor\Handler;

use Bitrix\Main\Localization\Loc;
use Bitrix\Report\VisualConstructor\Entity\Widget;
use Bitrix\Report\VisualConstructor\Fields\Base as BaseFormField;
use Bitrix\Report\VisualConstructor\Fields\Container;
use Bitrix\Report\VisualConstructor\Fields\Div;
use Bitrix\Report\VisualConstructor\Fields\Valuable\BaseValuable;
use Bitrix\Report\VisualConstructor\Fields\Valuable\ColorPicker;
use Bitrix\Report\VisualConstructor\Fields\Valuable\LabelField;
use Bitrix\Report\VisualConstructor\Fields\Valuable\PreviewBlock;
use Bitrix\Report\VisualConstructor\Fields\Valuable\TimePeriod;
use Bitrix\Report\VisualConstructor\Handler\Base as BaseHandler;
use Bitrix\Report\VisualConstructor\RuntimeProvider\ViewProvider;

/**
 * Class BaseWidget class for extending to create preset widget classes
 * @package Bitrix\Report\VisualConstructor\Handler
 */
class BaseWidget extends BaseHandler
{
	private $widget;
	private $reportHandlerList = array();

	/**
	 * @return string
	 */
	public static function getClassName()
	{
		return get_called_class();
	}

	/**
	 * BaseWidgetHandler constructor.
	 */
	public function __construct()
	{
		$widget = new Widget();
		$widget->setWidgetClass(static::getClassName());
		$this->setWidget($widget);
	}

	/**
	 * @return \Bitrix\Report\VisualConstructor\Fields\Base[]
	 */
	public function getCollectedFormElements()
	{
		parent::getCollectedFormElements();
		$this->getView()->collectWidgetHandlerFormElements($this);
		return $this->getFormElements();
	}

	/**
	 * Collecting form elements for configuratyion form.
	 *
	 * @return void
	 */
	protected function collectFormElements()
	{
		$label = new LabelField('label', 'big');
		$label->setDefaultValue(Loc::getMessage('REPORT_WIDGET_DEFAULT_TITLE'));
		$label->addAssets(array(
			'js' => array('/bitrix/js/report/js/visualconstructor/fields/reporttitle.js')
		));
		$label->setIsDisplayLabel(false);


		$timePeriod = new TimePeriod('time_period', $this->getWidget()->getFilterId());
		$timePeriod->setLabel(Loc::getMessage('REPORT_CALCULATION_PERIOD'));


		$colorPicker = new ColorPicker('color');
		$colorPicker->setLabel(Loc::getMessage('BACKGROUND_COLOR_OF_WIDGET'));
		$colorPicker->setDefaultValue('#ffffff');

		$previewBlockField = new PreviewBlock('view_type');
		$previewBlockField->setWidget($this->getWidget());
		$previewBlockField->addJsEventListener($previewBlockField, $previewBlockField::JS_EVENT_ON_VIEW_SELECT, array(
			'class' => 'BX.Report.VisualConstructor.FieldEventHandlers.PreviewBlock',
			'action' => 'viewTypeSelect'
		));

		$previewBlockField->addJsEventListener($label, $label::JS_EVENT_ON_CHANGE, array(
			'class' => 'BX.Report.VisualConstructor.FieldEventHandlers.PreviewBlock',
			'action' => 'reloadWidgetPreview'
		));
		$previewBlockField->addJsEventListener($timePeriod, $timePeriod::JS_EVENT_ON_SELECT, array(
			'class' => 'BX.Report.VisualConstructor.FieldEventHandlers.PreviewBlock',
			'action' => 'reloadWidgetPreview'
		));

		$previewBlockField->addAssets(array(
			'js' => array('/bitrix/js/report/js/visualconstructor/fields/previewblock.js')
		));
		$titleContainer = new Div();
		$titleContainer->addClass('report-configuration-row');
		$titleContainer->addClass('report-configuration-no-padding-bottom');
		$titleContainer->addClass('report-configuration-row-white-background');
		$titleContainer->addClass('report-configuration-row-margin-bottom');
		$this->addFormElement($titleContainer->start());
		$this->addFormElement($label);
		$this->addFormElement($colorPicker);
		$this->addFormElement($titleContainer->end());

		$timePeriodContainer = new Div();
		$timePeriodContainer->addClass('report-configuration-row');
		$timePeriodContainer->addClass('report-configuration-row-white-background');
		$this->addFormElement($timePeriodContainer->start());
		$this->addFormElement($timePeriod);
		$this->addFormElement($timePeriodContainer->end());

		$previewBlockContainer = new Div();
		$previewBlockContainer->addClass('report-configuration-row');
		$previewBlockContainer->addClass('report-configuration-row-margin-top-big');
		$previewBlockContainer->addClass('report-configuration-row-white-background');
		$this->addFormElement($previewBlockContainer->start());
		$this->addFormElement($previewBlockField);
		$this->addFormElement($previewBlockContainer->end());

	}

	/**
	 * @return Widget
	 */
	public function getWidget()
	{
		return $this->widget;
	}

	/**
	 * @param Widget $widget Widget entity.
	 * @return void
	 */
	public function setWidget($widget)
	{
		$this->widget = $widget;
	}

	/**
	 * @return BaseFormField[]
	 */
	public function getFormElements()
	{
		$result = array();
		foreach ($this->formElementsList as $key => $element)
		{
			$viewModesWhereFieldAvailable = $element->getCompatibleViewTypes();
			if ($viewModesWhereFieldAvailable != null)
			{
				$viewKey = $this->getWidget()->getViewKey();;
				$viewProvider = new ViewProvider();
				$viewProvider->addFilter('primary', $viewKey);
				$viewProvider->addFilter('dataType', $viewModesWhereFieldAvailable);
				$views = $viewProvider->execute()->getResults();
				if (!empty($views))
				{
					$result[$key] = $element;
				}
			}
			else
			{
				$result[$key] = $element;
			}
			if (($element instanceof BaseValuable) || ($element instanceof Container))
			{
				$element->setName($this->getNameForFormElement($element));
			}
		}
		return $result;
	}

	/**
	 * Construct and return form element name.
	 *
	 * @param BaseValuable $element Form element.
	 * @return string
	 */
	protected function getNameForFormElement(BaseValuable $element)
	{
		$name = '';
		if ($this->getWidget())
		{
			$name = 'widget[' . $this->getWidget()->getGId() . ']';
		}
		$name .= parent::getNameForFormElement($element);
		return $name;
	}

	/**
	 * @return BaseReport[]
	 */
	public function getReportHandlers()
	{
		return $this->reportHandlerList;
	}

	/**
	 * Attach report hadnler to widget handler.
	 *
	 * @param BaseReport $reportHandler Report handler.
	 * @return $this
	 */
	public function addReportHandler(BaseReport $reportHandler)
	{
		$reportHandler->setWidgetHandler($this);
		$this->getWidget()->addReportHandler($reportHandler);
		$this->reportHandlerList[] = $reportHandler;
		return $this;
	}

	/**
	 * Fill Widget handler entity with parameters from Widget entity.
	 *
	 * @param Widget $widget Widget handler.
	 * @return void
	 */
	public function fillWidget(Widget $widget)
	{
		$this->setWidget($widget);
		$viewHandler = ViewProvider::getViewByViewKey($widget->getViewKey());
		if ($viewHandler)
		{
			$this->setView($viewHandler);
		}
		$this->setConfigurations($widget->getConfigurations());
		$this->fillFormElementValues();
	}

	private function fillFormElementValues()
	{
		$formElements = $this->getCollectedFormElements();
		$configurations = $this->getConfigurations();
		if (!empty($configurations))
		{
			foreach ($configurations as $configuration)
			{
				if (isset($formElements[$configuration->getKey()]) && ($formElements[$configuration->getKey()] instanceof BaseValuable))
				{
					/** @var BaseValuable[] $formElements */
					$formElements[$configuration->getKey()]->setValue($configuration->getValue());
				}
			}
		}
	}
}