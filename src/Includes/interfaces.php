<?php

interface IUIClass
{
    public function setItems($Items);
    public function getItems();
    public function AddItem($item);
    public function setConfigID($ConfigID);
    public function getConfigID();
    public function setUserinterfaceID($UserInterfaceID);
    public function getUserinterfaceID();
    public function setSoftRulesXml($SoftRulesXml);
    public function getSoftRulesXml();
    public function setState($State);
    public function getState();
    public function setPage($Page);
    public function getPage();
    public function setPages($Pages);
    public function getPages();
    public function setUserinterfaceData($UserinterfaceData);
    public function getUserinterfaceData();
    public function setSessionID($SessionID);
    public function getSessionID();

    public function ItemVisible($Items, $item, $userinterfaceData);
    public function ItemValid($Items, $item, $userinterfaceData);
    public function ItemRequired($Items, $item, $userinterfaceData);
    public function ItemEnabled($Items, $item, $userinterfaceData);
}

interface IExpression
{
    public function setDescription($description);
    public function getDescription();
    public function setStartValue($startValue);
    public function getStartValue();
    public function setConditions($conditions);
    public function getConditions();
    public function Clean();
    public function Value($Items, $UserinterfaceData);
    public function WriteXml($writer);
}

interface ICondition
{
    public function setLogoperator($logoperator);
    public function getLogoperator();
    public function setOperator($operator);
    public function getOperator();
    public function setLeft_operand($left_operant);
    public function getLeft_operand();
    public function setRight_operand($right_operant);
    public function getRight_operand();
    public function CopyFrom($sourceCondition);
    public function Value($status, $Items, $UserinterfaceData);
    public function WriteXml($writer);
}

interface ISoftRules_Base
{
    public function setDescription($Description);
    public function getDescription();
    public function setVisibleExpression(IExpression $VisibleExpression);
    public function getVisibleExpression();
}

interface IOperand
{
    public function setValue($value);
    public function getValue();

    public function setElementPath($elementPath);
    public function getElementPath();

    public function setAttributes($attributes);
    public function getAttributes();

    public function setValueType($valueType); //eValueType
    public function getValueType();

    public function writeXml($writer);
}

interface ITextValues
{
    public function setTextValuesID($textValuesID);
    public function getTextValuesID();
    public function setTextQuestionID($textQuestionID);
    public function getTextQuestionID();
    public function setTextValuesList($textValuesList);
}

interface ITextValueItem
{
    public function setValue($Value);
    public function getValue();    
    public function setText($Text);
    public function getText();
    public function getImageUrl();
    public function setImageUrl($ImageUrl);
    public function writeXml($writer);
}

interface IRange
{
    public function setHighValue($HighValue);
    public function getHighValue();
    public function setLowValue($LowValue);
    public function getLowValue();        
}

interface IGroup extends ISoftRules_Base
{
    public function setGroupID($GroupID);
    public function getGroupID();
    public function setName($Name);
    public function getName();
    public function setType($Type);
    public function getType();
    public function setUpdateUserinterface($UpdateUserinterface);
    public function getUpdateUserinterface();
    public function setPageID($pageID);
    public function getPageID();
    public function setSuppressItemsWhenInvisible($SuppressItemsWhenInvisible);
    public function getSuppressItemsWhenInvisible();
    public function setItems($Items);
    public function getItems();
    public function setHeaderItems($HeaderItems);
    public function getHeaderItems();
    public function setCustomProperties($CustomProperties);
    public function getCustomProperties();    
    public function setParameter($Parameter);
    public function getParameter();
}

interface IQuestion extends ISoftRules_Base
{
    public function setQuestionID($QuestionID);
    public function getQuestionID();
    public function setName($Name);
    public function getName();
    public function setValue($Value);
    public function getValue();
    public function setDescription($Description);
    public function getDescription();
    public function setPlaceHolder($PlaceHolder);
    public function getPlaceHolder();
    public function setTooltip(IQuestion $Tooltip);
    public function getTooltip();
    public function setHelpText($HelpText);
    public function getHelpText();
    public function setDefaultState($DefaultState);
    public function getDefaultState();
    public function setIncludeInvisibleQuestion($IncludeInvisibleQuestion);
    public function getIncludeInvisibleQuestion();
    public function setDataType($DataType);
    public function getDisplayType();    
    public function setDisplayType($DisplayType);
    public function setDisplayOnly($DisplayOnly);
    public function getDisplayOnly();
    public function getDataType();
    public function setRestrictions(IRestrictions $Restrictions);
    public function getRestrictions();
    public function setParameter($Parameter);
    public function getParameter();
    public function setElementPath($ElementPath);
    public function getElementPath();
    public function setCoreValue($CoreValue);
    public function getCoreValue();
    public function setUpdateUserinterface($UpdateUserinterface);
    public function getUpdateUserinterface();
    public function setInvalidMessage($InvalidMessage);
    public function getInvalidMessage();
    public function setCustomProperties($CustomProperties);
    public function getCustomProperties();
    public function setDefaultStateExpression(IExpression $DefaultStateExpression);
    public function getDefaultStateExpression();
    public function setRequiredExpression(IExpression $RequiredExpression);
    public function getRequiredExpression();
    public function setValidExpression(IExpression $ValidExpression);
    public function getValidExpression();
    public function setUpdateExpression(IExpression $UpdateExpression);
    public function setReadyForProcess($ReadyForProcess);
    public function getReadyForProcess();
    public function setTextValues($TextValues);
    public function getTextValues();
}

interface IButton extends ISoftRules_Base
{
    public function setButtonID($ButtonID);
    public function getButtonID();
    public function setText($Text);
    public function getText();
    public function setHint($Hint);
    public function getHint();
    public function setType($Type);
    public function getType();
    public function setParameter($Parameter);
    public function getParameter();
}

interface ILabel extends ISoftRules_Base
{
    public function setLabelID($LabelID);
    public function getLabelID();
    public function setText($Text);
    public function getText();
    public function setParameter($Parameter);
    public function getParameter();
}

interface ICustomProperty
{
    public function setName($Name);
    public function getName();
    public function setValue($Value);
    public function getValue();
    public function parse($item);
}

interface IRestrictions
{
    public function setEnumerationValues($enumerationValues);
    public function getEnumerationValues();
    public function setFractionDigits($fractionDigits);
    public function getFractionDigits();
    public function setLength($length);
    public function getLength();
    public function setMaxExclusive($maxExclusive);
    public function getMaxExclusive();
    public function setMinExclusive($minExclusive);
    public function getMinExclusive();
    public function setMaxInclusive($maxInclusive);
    public function getMaxInclusive();
    public function setMinInclusive($minInclusive);
    public function getMinInclusive();
    public function setMaxLength($maxLength);
    public function getMaxLength();
    public function setMinLength($minLength);
    public function getMinLength();
    public function setPattern($pattern);
    public function getPattern();
    public function setTotalDigits($totalDigits);
    public function getTotalDigits();
    public function setWhiteSpace($whiteSpace);
    public function getWhiteSpace();
    public function Valid($question);
}

interface IParameter
{
    public function setName($Name);
    public function getName();
    public function setPath($Path);
    public function getPath();
    public function setParentGroupID($ParentGroupID);
    public function getParentGroupID();
    public function setParentUserinterfaceID($ParentUserinterfaceID);
    public function getParentUserinterfaceID();
    public function getParentConfigID();
    public function setParentConfigID($ParentConfigID);
    public function setUsedByEvents($UsedByEvents);
    public function getUsedByEvents();
}

class eGroupType
{
    const page = 0;
    const box = 1;
    const expandable = 2;
    const column = 3;
    const table = 4;
    const row = 5;
    const popup = 6;
    const grid = 7;
    const gridcolumn = 8;
    const gridrow = 9;
}

class eButtonType
{
    const submit = 0;
    const update = 1;
    const navigate = 2;
    const none = 3;
}

class eLogOperator
{
    const AND = 0;
    const OR = 1;
}

class eOperator
{
    const EQUAL = 0;
    const NOT_EQUAL = 1;
    const LESS_EQUAL = 2;
    const GREATER_EQUAL = 3;
    const GREATER = 4;
    const LESS = 5;
    const IN = 6;
}

class eValueType
{
    const ELEMENTNAME = 0;
    const CONSTANT = 1;
    const FORMULA = 2;
}

class eDefaultState
{
    const Editable = 0;
    const Readonly = 1;
}

class eElementType
{
    const None = 0;
    const AlphaNumeric = 1;
    const Integer = 2;
    const FloatingPoint = 3;
    const Currency = 4;
    const Date = 5;
    const Time = 6;
    const Array = 7;
    const String = 8;
}

class eState
{
    const New = 0;
    const Changed = 1;
    const Unchanged = 2;
}