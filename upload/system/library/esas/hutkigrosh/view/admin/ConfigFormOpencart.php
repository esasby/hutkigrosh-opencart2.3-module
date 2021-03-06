<?php

/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 30.09.2018
 * Time: 15:19
 */

namespace esas\hutkigrosh\view\admin;

use esas\hutkigrosh\utils\htmlbuilder\Attributes as attribute;
use esas\hutkigrosh\utils\htmlbuilder\Elements as element;
use esas\hutkigrosh\view\admin\fields\ConfigField;
use esas\hutkigrosh\view\admin\fields\ConfigFieldCheckbox;
use esas\hutkigrosh\view\admin\fields\ConfigFieldList;
use esas\hutkigrosh\view\admin\fields\ConfigFieldPassword;
use esas\hutkigrosh\view\admin\fields\ConfigFieldTextarea;
use esas\hutkigrosh\view\admin\fields\ListOption;

class ConfigFormOpencart extends ConfigFormHtml
{
    private $orderStatuses;

    /**
     * ConfigFieldsRenderOpencart constructor.
     */
    public function __construct($registry)
    {
        parent::__construct();
        $loader = $registry->get("load");
        $loader->model('localisation/order_status');
        $orderStatuses = $registry->get("model_localisation_order_status")->getOrderStatuses();
        foreach ($orderStatuses as $orderStatus) {
            $this->orderStatuses[] = new ListOption($orderStatus['order_status_id'], $orderStatus['name']);
        }
    }

    private static function elementValidationError(ConfigField $configField)
    {
        $validationResult = $configField->getValidationResult();
        if ($validationResult != null && !$validationResult->isValid())
            return
                element::div(
                    attribute::clazz("alert alert-danger"),
                    element::content($validationResult->getErrorTextSimple())
                );
        else
            return "";
    }


    private static function elementLabel(ConfigField $configField)
    {
        return
            element::label(
                attribute::clazz("col-sm-2 control-label"),
                attribute::forr("input-" . $configField->getKey()),
                element::span(
                    attribute::data_toggle("tooltip"),
                    attribute::title($configField->getDescription()),
                    element::content($configField->getName())
                )
            );
    }

    private static function attributeFormClass(ConfigField $configField)
    {
        return attribute::clazz("form-group" . ($configField->isRequired() ? ' required' : ''));
    }

    private static function elementInput(ConfigField $configField, $type)
    {
        return
            element::input(
                attribute::clazz("form-control"),
                attribute::name($configField->getKey()),
                attribute::type($type),
                attribute::placeholder($configField->getName()),
                self::attributeInputId($configField),
                attribute::value($configField->getValue())
            );
    }

    private static function attributeInputId(ConfigField $configField)
    {
        return attribute::id("input-" . $configField->getKey());
    }

    function generateTextField(ConfigField $configField)
    {
        return
            element::div(
                self::attributeFormClass($configField),
                self::elementValidationError($configField),
                self::elementLabel($configField),
                element::div(
                    attribute::clazz("col-sm-10"),
                    self::elementInput($configField, "text")
                )
            );
    }

    function generateTextAreaField(ConfigFieldTextarea $configField)
    {
        return
            element::div(
                self::attributeFormClass($configField),
                self::elementLabel($configField),
                self::elementValidationError($configField),
                element::div(
                    attribute::clazz("col-sm-10"),
                    element::textarea(
                        self::attributeInputId($configField),
                        attribute::name($configField->getKey()),
                        attribute::clazz("form-control"),
                        attribute::rows($configField->getRows()),
                        attribute::cols($configField->getCols()),
                        attribute::placeholder($configField->getName()),
                        element::content($configField->getValue())
                    )
                )
            );
    }


    public function generatePasswordField(ConfigFieldPassword $configField)
    {
        return
            element::div(
                self::attributeFormClass($configField),
                self::elementLabel($configField),
                self::elementValidationError($configField),
                element::div(
                    attribute::clazz("col-sm-10"),
                    self::elementInput($configField, "password")
                )
            );
    }


    function generateCheckboxField(ConfigFieldCheckbox $configField)
    {
        return
            element::div(
                self::attributeFormClass($configField),
                self::elementLabel($configField),
                self::elementValidationError($configField),
                element::div(
                    attribute::clazz("col-sm-10"),
                    element::input(
                        attribute::type("checkbox"),
                        attribute::name($configField->getKey()),
                        self::attributeInputId($configField),
                        attribute::value("1"),
                        attribute::checked($configField->isChecked()),
                        attribute::placeholder($configField->getName()),
                        attribute::clazz("form-control")
                    )
                )
            );
    }

    function generateListField(ConfigFieldList $configField)
    {
        return
            element::div(
                self::attributeFormClass($configField),
                self::elementLabel($configField),
                self::elementValidationError($configField),
                element::div(
                    attribute::clazz("col-sm-10"),
                    element::select(
                        attribute::clazz("form-control"),
                        attribute::name($configField->getKey()),
                        self::attributeInputId($configField),
                        parent::elementOptions($configField)
                    )
                )
            );
    }

    /**
     * @return ListOption[]
     */
    public function createStatusListOptions()
    {
        return $this->orderStatuses;
    }
}