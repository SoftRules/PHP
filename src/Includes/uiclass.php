<?php
class UIClass implements IUIClass
{
    private $SoftRulesXml = "";
    private $State = "";
    private $Page = 1;
    private $Pages = 1;
    private $UserinterfaceData;
    private $SessionID;
    private $ConfigID;
    private $UserInterfaceID;
    private $Items = [];

    function __construct()
    {
        $this->UserinterfaceData = new DOMDocument();
        $this->SoftRulesXml = new DOMDocument();
    }
    public function setSoftRulesXml($SoftRulesXml)
    {
        if ($SoftRulesXml != "") {
            $this->SoftRulesXml = new DOMDocument();
            $this->SoftRulesXml->loadXML($SoftRulesXml);
        }
    }

    public function getSoftRulesXml()
    {
        return $this->SoftRulesXml;
    }

    public function setConfigID($ConfigID)
    {
        $this->ConfigID = $ConfigID;
    }
    public function getConfigID()
    {
        return $this->ConfigID;
    }

    public function setUserInterfaceID($UserInterfaceID)
    {
        $this->UserInterfaceID = $UserInterfaceID;
    }
    public function getUserInterfaceID()
    {
        return $this->UserInterfaceID;
    }

    public function setPage($Page)
    {
        $this->Page = $Page;
    }
    public function getPage()
    {
        return $this->Page;
    }
    public function setPages($Pages)
    {
        $this->Pages = $Pages;
    }
    public function getPages()
    {
        return $this->Pages;
    }
    public function setUserinterfaceData($UserinterfaceData)
    {
        if ($UserinterfaceData != "") {
            $this->UserinterfaceData = new DOMDocument();
            $this->UserinterfaceData->loadXML($UserinterfaceData);
        }
    }
    public function getUserinterfaceData()
    {
        return $this->UserinterfaceData;
    }
    public function setSessionID($SessionID)
    {
        $this->SessionID = $SessionID;
    }
    public function getSessionID()
    {
        return $this->SessionID;
    }
    public function setState($State)
    {
        $this->State = $State;
    }
    public function getState()
    {
        return $this->State;
    }
    public function setItems($Items)
    {
        $this->Items = $Items;
    }
    public function getItems()
    {
        return $this->Items;
    }

    function AddItem($item)
    {
        $this->Items[] = $item;
    }

    function ItemVisible($Items, $item, $userinterfaceData)
    {
        return true;
    }
    function ItemValid($Items, $item, $userinterfaceData)
    {
        return true;
    }
    public function ItemRequired($Items, $item, $userinterfaceData)
    {
        return true;
    }
    public function ItemEnabled($Items, $item, $userinterfaceData)
    {
        return true;
    }
    public function ParseUIXML($xml)
    {
        $x = $xml->documentElement;
        foreach ($x->childNodes as $child) {

            switch ($child->nodeName) {
                case 'Page':
                    $this->setPage($child->nodeValue);
                    break;
                case 'Pages':
                    $this->setPages($child->nodeValue);
                    break;
                case 'SessionID':
                    $this->setSessionID($child->nodeValue);
                    break;
                case 'SoftRulesXml':
                    $this->setSoftRulesXml($child->nodeValue);
                    break;
                case 'ConfigID':
                    $this->setConfigID($child->nodeValue);
                    break;
                case 'UserInterfaceID':
                    $this->setUserInterfaceID($child->nodeValue);
                    break;
                case 'State':
                    $this->setState($child->nodeValue);
                    break;
                case 'SoftRulesXML':
                    break;
                case 'UserinterfaceData':
                    if ($child->childNodes->length > 0) {
                        $innerHTML = "";
                        $children = $child->childNodes;

                        foreach ($children as $child) {
                            $innerHTML .= $child->ownerDocument->saveHTML($child);
                        }

                        $this->setUserinterfaceData($innerHTML);
                    }

                    break;
                case 'Questions': //this tag contains child elements, of which we only want one.
                {
                    foreach ($child->childNodes as $Item) {
                        switch ($Item->nodeName) {
                            case 'Group': {
                                $newGroup = new Group();
                                $newGroup->parse($Item);
                                $this->AddItem($newGroup);
                                break;
                            }
                            case 'Question': {
                                $newQuestion = new Question();
                                $newQuestion->parse($Item);
                                $this->AddItem($newQuestion);
                                break;
                            }
                            case 'Label': {
                                $newLabel = new Label();
                                $newLabel->parse($Item);
                                $this->AddItem($newLabel);
                                break;
                            }
                            case 'Button': {
                                $newButton = new Button();
                                $newButton->parse($Item);
                                $this->AddItem($newButton);
                                break;
                            }
                            default: {
                                break;
                            }
                        }
                    }

                    break;
                }
                default: {
                    echo "ParseUI Not implemented yet:" . $child->nodeName . "<br>";
                    break;
                }
            }
        }
    }
}

class Group implements IGroup
{
    private $GroupID;
    private $Name;
    private $Type;
    private $UpdateUserinterface;
    private $CustomProperties = [];
    private $PageID;
    private $SuppressItemsWhenInvisible;
    private $Items = [];
    private $HeaderItems = [];
    private $Description;
    private $VisibleExpression;
    private $Parameter;

    function __construct()
    {
        $this->setVisibleExpression(new Expression());
    }
    public function setGroupID($GroupID)
    {
        $this->GroupID = $GroupID;
    }
    public function getGroupID()
    {
        return str_replace('|', '_', $this->GroupID);
    }
    public function setName($Name)
    {
        $this->Name = $Name;
    }
    public function getName()
    {
        return $this->Name;
    }
    public function setType($Type)
    {
        $this->Type = $Type;
    }
    public function getType()
    {
        return $this->Type;
    }
    public function setUpdateUserinterface($UpdateUserinterface)
    {
        $this->UpdateUserinterface = $UpdateUserinterface;
    }
    public function getUpdateUserinterface()
    {
        return $this->UpdateUserinterface;
    }
    public function setPageID($pageID)
    {
        $this->PageID = $pageID;
    }
    public function getPageID()
    {
        return $this->PageID;
    }
    public function setSuppressItemsWhenInvisible($SuppressItemsWhenInvisible)
    {
        $this->SuppressItemsWhenInvisible = $SuppressItemsWhenInvisible;
    }
    public function getSuppressItemsWhenInvisible()
    {
        return $this->SuppressItemsWhenInvisible;
    }

    public function setItems($Items)
    {
        $this->funcionItems = $Items;
    }
    public function getItems()
    {
        return $this->Items;
    }

    public function setHeaderItems($HeaderItems)
    {
        $this->HeaderItems = $HeaderItems;
    }
    public function getHeaderItems()
    {
        return $this->HeaderItems;
    }

    public function AddItem($Item)
    {
        $this->Items[] = $Item;
    }
    public function AddHeaderItem($Item)
    {
        $this->HeaderItems[] = $Item;
    }
    public function setVisibleExpression(IExpression $VisibleExpression)
    {
        $this->VisibleExpression = $VisibleExpression;
    }
    public function getVisibleExpression()
    {
        return $this->VisibleExpression;
    }

    public function setDescription($Description)
    {
        $this->Description = $Description;
    }
    public function getDescription()
    {
        return $this->Description;
    }
    public function setCustomProperties($CustomProperties)
    {
        $this->CustomProperties = $CustomProperties;
    }
    public function getCustomProperties()
    {
        return $this->CustomProperties;
    }
    public function setParameter($Parameter)
    {
        $this->Parameter = $Parameter;
    }
    public function getParameter()
    {
        return $this->Parameter;
    }

    public function parse($node)
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case "GroupID": {
                    $this->setGroupID($item->nodeValue);
                    break;
                }
                case "Name": {
                    $this->setName($item->nodeValue);
                    break;
                }
                case "Type": {
                    $this->setType($item->nodeValue);
                    break;
                }
                case "UpdateUserInterface": {
                    $this->setUpdateUserinterface($item->nodeValue);
                    break;
                }
                case 'PageID': {
                    $this->setPageID($item->nodeValue);
                    break;
                }
                case 'SuppressItemsWhenInvisible': {
                    $this->setSuppressItemsWhenInvisible($item->nodeValue);
                    break;
                }
                case 'CustomProperties': {
                    foreach ($item->childNodes as $cp) {
                        $customProperty = new CustomProperty();
                        $customProperty->parse($cp);
                        $this->CustomProperties[] = $customProperty;
                    }
                    break;
                }
                case 'VisibleExpression': {
                    $expression = new Expression();
                    $expression->parse($item);
                    $this->setVisibleExpression($expression);
                    break;
                }
                case 'Parameter': {
                    $parameter = new Parameter();
                    $parameter->parse($item);
                    $this->setParameter($parameter);
                    break;
                }
                case 'ParameterList': {
                    //no need to parse
                    break;
                }
                case 'RepeatingPath': {
                    //no need to parse
                    break;
                }
                case 'HeaderItems': {
                    foreach ($item->childNodes as $item) {
                        switch ($item->nodeName) {
                            case 'Group': {
                                $newGroup = new Group();
                                $newGroup->parse($item);
                                $this->AddHeaderItem($newGroup);
                                break;
                            }
                            case 'Question': {
                                $newQuestion = new Question();
                                $newQuestion->parse($item);
                                $this->AddHeaderItem($newQuestion);
                                break;
                            }
                            default: {
                                break;
                            }
                        }
                    }
                    break;
                }
                case 'Items': {
                    foreach ($item->childNodes as $item) {
                        switch ($item->nodeName) {
                            case 'Group': {
                                $newGroup = new Group();
                                $newGroup->parse($item);
                                $this->AddItem($newGroup);
                                break;
                            }
                            case 'Question': {
                                $newQuestion = new Question();
                                $newQuestion->parse($item);
                                $this->AddItem($newQuestion);
                                break;
                            }
                            case 'Label': {
                                $newLabel = new Label();
                                $newLabel->parse($item);
                                $this->AddItem($newLabel);
                                break;
                            }
                            case 'Button': {
                                $newButton = new Button();
                                $newButton->parse($item);
                                $this->AddItem($newButton);
                                break;
                            }
                            default: {
                                break;
                            }
                        }
                    }
                    break;
                }
                default: {
                    echo "Group Not implemented yet:" . $item->nodeName . "<br>";
                    break;
                }
            }
        }
    }
}

class Question implements IQuestion
{
    private $QuestionID;
    private $Name;
    private $Value;
    private $Description;
    private $Placeholder;
    private $ToolTip;
    private $HelpText;
    private $DefaultState;
    private $IncludeInvisibleQuestion;
    private $DataType;
    private $DisplayType;
    private $DisplayOnly;
    private IRestrictions $Restrictions;
    private $Parameter;
    private $ElementPath;
    private $UpdateUserinterface;
    private $InvalidMessage;
    private $CustomProperties;
    private $ReadyForProcess;
    private $CoreValue;
    private $TextValues = [];
    private IExpression $ValidExpression;
    private IExpression $DefaultStateExpression;
    private IExpression $RequiredExpression;
    private IExpression $UpdateExpression;
    private IExpression $VisibleExpression;

    function __construct()
    {
        $this->setVisibleExpression(new Expression());
        $this->setValidExpression(new Expression());
        $this->setRequiredExpression(new Expression());
        $this->setUpdateExpression(new Expression());
        $this->setDefaultStateExpression(new Expression());
    }
    public function setQuestionID($QuestionID)
    {
        $this->QuestionID = $QuestionID;
    }
    public function getQuestionID()
    {
        return $this->QuestionID;
    }
    public function setName($Name)
    {
        $this->Name = $Name;
    }
    public function getName()
    {
        return $this->Name;
    }
    public function setValue($Value)
    {
        $this->Value = $Value;
    }
    public function getValue()
    {
        return $this->Value;
    }
    public function setDescription($Description)
    {
        $this->Description = $Description;
    }
    public function getDescription()
    {
        return $this->Description;
    }
    public function setPlaceholder($Placeholder)
    {
        $this->Placeholder = $Placeholder;
    }
    public function getPlaceholder()
    {
        return $this->Placeholder;
    }
    public function setToolTip($ToolTip)
    {
        $this->ToolTip = $ToolTip;
    }
    public function getToolTip()
    {
        return $this->ToolTip;
    }
    public function setHelpText($HelpText)
    {
        $this->HelpText = $HelpText;
    }
    public function getHelpText()
    {
        return $this->HelpText;
    }
    public function setDefaultState($DefaultState)
    {
        $this->DefaultState = $DefaultState;
    }
    public function getDefaultState()
    {
        return $this->DefaultState;
    }
    public function setCoreValue($CoreValue)
    {
        $this->CoreValue = $CoreValue;
    }
    public function getCoreValue()
    {
        return $this->CoreValue;
    }
    public function setIncludeInvisibleQuestion($IncludeInvisibleQuestion)
    {
        $this->IncludeInvisibleQuestion = $IncludeInvisibleQuestion;
    }
    public function getIncludeInvisibleQuestion()
    {
        return $this->IncludeInvisibleQuestion;
    }
    public function setDataType($DataType)
    {
        $this->DataType = $DataType;
    }
    public function getDataType()
    {
        return $this->DataType;
    }
    public function setDisplayType($DisplayType)
    {
        $this->DisplayType = $DisplayType;
    }
    public function getDisplayType()
    {
        return $this->DisplayType;
    }
    public function setDisplayOnly($DisplayOnly)
    {
        $this->DisplayOnly = $DisplayOnly;
    }
    public function getDisplayOnly()
    {
        return $this->DisplayOnly;
    }
    public function setRestrictions(IRestrictions $Restrictions)
    {
        $this->Restrictions = $Restrictions;
    }
    public function getRestrictions()
    {
        return $this->Restrictions;
    }
    public function setParameter($Parameter)
    {
        $this->Parameter = $Parameter;
    }
    public function getParameter()
    {
        return $this->Parameter;
    }
    public function setElementPath($ElementPath)
    {
        $this->ElementPath = $ElementPath;
    }
    public function getElementPath()
    {
        return $this->ElementPath;
    }
    public function setUpdateUserinterface($UpdateUserinterface)
    {
        $this->UpdateUserinterface = $UpdateUserinterface;
    }
    public function getUpdateUserinterface()
    {
        return $this->UpdateUserinterface;
    }
    public function setInvalidMessage($InvalidMessage)
    {
        $this->InvalidMessage = $InvalidMessage;
    }
    public function getInvalidMessage()
    {
        return $this->InvalidMessage;
    }
    public function setCustomProperties($CustomProperties)
    {
        $this->CustomProperties = $CustomProperties;
    }
    public function getCustomProperties()
    {
        return $this->CustomProperties;
    }

    public function setDefaultStateExpression(IExpression $DefaultStateExpression)
    {
        $this->DefaultStateExpression = $DefaultStateExpression;
    }
    public function getDefaultStateExpression()
    {
        return $this->DefaultStateExpression;
    }
    public function setRequiredExpression(IExpression $RequiredExpression)
    {
        $this->RequiredExpression = $RequiredExpression;
    }
    public function getRequiredExpression()
    {
        return $this->RequiredExpression;
    }
    public function setUpdateExpression(IExpression $UpdateExpression)
    {
        $this->UpdateExpression = $UpdateExpression;
    }
    public function getUpdateExpression()
    {
        return $this->UpdateExpression;
    }
    public function setValidExpression(IExpression $ValidExpression)
    {
        $this->ValidExpression = $ValidExpression;
    }
    public function getValidExpression()
    {
        return $this->ValidExpression;
    }
    public function setVisibleExpression(IExpression $VisibleExpression)
    {
        $this->VisibleExpression = $VisibleExpression;
    }
    public function getVisibleExpression()
    {
        return $this->VisibleExpression;
    }
    public function setReadyForProcess($ReadyForProcess)
    {
        $this->ReadyForProcess = $ReadyForProcess;
    }
    public function getReadyForProcess()
    {
        return $this->ReadyForProcess;
    }
    public function setTextValues($TextValues)
    {
        $this->TextValues = $TextValues;
    }
    public function getTextValues()
    {
        return $this->TextValues;
    }

    public function parse($node)
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case "QuestionID": {
                    $this->setQuestionID($item->nodeValue);
                    break;
                }
                case "Name": {
                    $this->setName($item->nodeValue);
                    break;
                }
                case "Value": {
                    $this->setValue($item->nodeValue);
                    break;
                }
                case "Description": {
                    $this->setDescription($item->nodeValue);
                    break;
                }
                case "Placeholder": {
                    $this->setPlaceholder($item->nodeValue);
                    break;
                }
                case "ToolTip": {
                    $this->setToolTip($item->nodeValue);
                    break;
                }
                case "HelpText": {
                    $this->setHelpText($item->nodeValue);
                    break;
                }
                case "DefaultState": {
                    $this->setDefaultState($item->nodeValue);
                    break;
                }
                case "IncludeInvisibleQuestion": {
                    $this->setIncludeInvisibleQuestion($item->nodeValue);
                    break;
                }
                case "DataType": {
                    $this->setDataType($item->nodeValue);
                    break;
                }
                case "DisplayType": {
                    $this->setDisplayType($item->nodeValue);
                    break;
                }
                case "DisplayOnly": {
                    $this->setDisplayOnly($item->nodeValue);
                    break;
                }
                case "CoreValue": {
                    $this->setCoreValue($item->nodeValue);
                    break;
                }
                case "Restrictions": {
                    $this->Restrictions = new Restrictions();

                    foreach ($item->childNodes as $restriction) {
                        switch ($restriction->nodeName) {
                            case "enumerationValues": {
                                $this->Restrictions->setEnumerationValues($restriction->nodeValue);
                                break;
                            }
                            case "fractionDigits": {
                                $this->Restrictions->setFractionDigits($restriction->nodeValue);
                                break;
                            }
                            case 'length': {
                                $this->Restrictions->setLength($restriction->nodeValue);
                                break;
                            }
                            case 'maxExclusive': {
                                $this->Restrictions->setMaxExclusive($restriction->nodeValue);
                                break;
                            }
                            case 'minExclusive': {
                                $this->Restrictions->setMinExclusive($restriction->nodeValue);
                                break;
                            }
                            case 'maxInclusive': {
                                $this->Restrictions->setMaxInclusive($restriction->nodeValue);
                                break;
                            }
                            case 'minInclusive': {
                                $this->Restrictions->setMinInclusive($restriction->nodeValue);
                                break;
                            }
                            case 'maxLength': {
                                $this->Restrictions->setMaxLength($restriction->nodeValue);
                            }
                                break;
                            case 'minLength': {
                                $this->Restrictions->setMinLength($restriction->nodeValue);
                                break;
                            }
                            case 'pattern': {
                                $this->Restrictions->setPattern($restriction->nodeValue);
                                break;
                            }
                            case 'totalDigits': {
                                $this->Restrictions->setTotalDigits($restriction->nodeValue);
                                break;
                            }
                            case 'whiteSpace': {
                                $this->Restrictions->setWhiteSpace($restriction->nodeValue);
                                break;
                            }
                            default: {
                                echo "restriction Not implemented yet:" . $restriction->nodeName . "<br>";
                                break;
                            }
                        }
                    }
                    break;
                }
                case "Parameter": {
                    $Parameter = new Parameter();
                    $Parameter->parse($item);
                    $this->setParameter($Parameter);
                    break;
                }
                case "ElementPath": {
                    $this->setElementPath($item->nodeValue);
                    break;
                }
                case "UpdateUserInterface": {
                    $this->setUpdateUserinterface($item->nodeValue);
                    break;
                }
                case "InvalidMessage": {
                    $this->setInvalidMessage($item->nodeValue);
                    break;
                }
                case "CustomProperties": {

                    foreach ($item->childNodes as $cp) {
                        $customProperty = new CustomProperty();
                        $customProperty->parse($cp);
                        $this->CustomProperties[] = $customProperty;
                    }
                    break;
                }
                case 'VisibleExpression': {
                    $expression = new Expression();
                    $expression->parse($item);
                    $this->setVisibleExpression($expression);
                    break;
                }
                case 'RequiredExpression': {
                    $expression = new Expression();
                    $expression->parse($item);
                    $this->setRequiredExpression($expression);
                    break;
                }
                case 'ValidExpression': {
                    $expression = new Expression();
                    $expression->parse($item);
                    $this->setValidExpression($expression);
                    break;
                }
                case 'DefaultStateExpression': {
                    $expression = new Expression();
                    $expression->parse($item);
                    $this->setDefaultStateExpression($expression);
                    break;
                }
                case 'UpdateExpression': {
                    $expression = new Expression();
                    $expression->parse($item);
                    $this->setUpdateExpression($expression);
                    break;
                }
                case 'ReadyForProcess': {
                    $this->setReadyForProcess($item->nodeValue);
                    break;
                }
                case 'TextValues': {
                    foreach ($item->childNodes as $textValueItem) {
                        $newItem = new TextValueItem();
                        $newItem->parse($textValueItem);
                        $this->TextValues[] = $newItem;
                    }
                    break;
                }
                default: {
                    echo "Question Not implemented yet:" . $item->nodeName . "<br>";
                    break;
                }
            }
        }
    }
}

class Expression implements IExpression
{
    private $description;
    private $startValue = true;
    private $conditions = [];

    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function setStartValue($startValue)
    {
        if (strtolower($startValue) == "true") {
            $this->startValue = true;
        } else {
            $this->startValue = false;
        }
    }
    public function getStartValue()
    {
        return $this->startValue;
    }
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }
    public function getConditions()
    {
        return $this->conditions;
    }
    public function Clean()
    {
    }
    public function Value($Items, $UserinterfaceData)
    {
        $res = $this->getStartValue();
        foreach ($this->getConditions() as $condition) {
            $res = $condition->Value($res, $Items, $UserinterfaceData);
        }
        return $res;
    }
    public function WriteXml($writer)
    {

    }
    public function parse($node)
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case 'StartValue': {
                    $this->setStartValue($item->nodeValue);
                    break;
                }
                case 'Description': {
                    $this->setDescription($item->nodeValue);
                    break;
                }
                case 'Conditions': {
                    foreach ($item->childNodes as $conditionNode) {
                        $condition = new Condition();
                        $condition->parse($conditionNode);
                        $this->conditions[] = $condition;
                    }
                    break;
                }
            }
        }
    }
}

class Condition implements ICondition
{
    private $logoperator;
    private $operator;
    private IOperand $left_operand;
    private IOperand $right_operand;

    public function setLogoperator($logoperator)
    {
        $this->logoperator = $logoperator;
    }
    public function getLogoperator()
    {
        return $this->logoperator;
    }
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }
    public function getOperator()
    {
        return $this->operator;
    }
    public function setLeft_operand($left_operand)
    {
        $this->left_operand = $left_operand;
    }
    public function getLeft_operand()
    {
        return $this->left_operand;
    }
    public function setRight_operand($right_operand)
    {
        $this->right_operand = $right_operand;
    }
    public function getRight_operand()
    {
        return $this->right_operand;
    }
    public function CopyFrom($sourceCondition)
    {
    }

    public function getElement($Items, $operand, $UserinterfaceData)
    {
        $operandPath = $operand->getElementPath();

        foreach ($Items as $item) {
            if ($item instanceof Question) {
                if ($item->getName() == $operand->getValue()) 
                {
                    //Tussen accolades staat de instantie van de node in het xml. Staat er niets, dan impliceert dat de eerste instantie zijnde {1}
                    //Door {1} te vervangen door niets, kunnen de strings met elkaar vergeleken worden.
                    $itempath = str_replace("{1}", "", $item->getElementPath());
                    $operandpath = str_replace("{1}", "", $operand->getElementPath());
                    
                    if ($itempath == $operandpath)
                    {
                        return $item->getValue();
                    }
                }
            } else if ($item instanceof Group) {
                $return = $this->getElement($item->getItems(), $operand, $UserinterfaceData);
                if ($return !== "") {
                    return $return;
                }
            }
        }

        //xpath op userinterfaceData
        if ($UserinterfaceData instanceof DOMDocument)
        {
            $xpath = new DOMXpath($UserinterfaceData);
            $operandPath = str_replace('{', '[', $operandPath);
            $operandPath = str_replace('}', ']', $operandPath);

            $nodes = $xpath->query("/". $operandPath . "/". $operand->getValue());
            if ($nodes->length > 0)
            {
                return $nodes->item(0)->nodeValue;
            }
        }

        return "";
    }
    public function Value($status, $Items, $UserinterfaceData)
    {
        $res = false;
        $processed = false;
        $lfs = "";
        $rfs = "";

        if ($this->getLeft_operand()->getValueType() == eValueType::ELEMENTNAME) {
            $lfs = $this->getElement($Items, $this->getLeft_operand(), $UserinterfaceData);
        } else {
            $lfs = $this->getLeft_operand()->getValue();
        }

        if ($this->getRight_operand()->getValueType() == eValueType::ELEMENTNAME) {
            $rfs = $this->getElement($Items, $this->getRight_operand(), $UserinterfaceData);
        } else {
            $rfs = $this->getRight_operand()->getValue();
        }

        $lf = $this->convertInt($lfs);
        $rf = $this->convertInt($rfs);

        if (isset ($lf) && isset ($rf)) {
            $processed = true;
            switch ($this->getOperator()) {
                case "EQUAL": {
                    $res = ($lf == $rf);
                    break;
                }
                case "GREATER_EQUAL": {
                    $res = ($lf >= $rf);
                    break;
                }
                case "LESS_EQUAL": {
                    $res = ($lf <= $rf);
                    break;
                }
                case "NOT_EQUAL": {
                    $res = ($lf != $rf);
                    break;
                }
                case "GREATER": {
                    $res = ($lf > $rf);
                    break;
                }
                case "LESS": {
                    $res = ($lf < $rf);
                    break;
                }
                case "IN": {
                    $res = false;
                    $options = str_split($rfs);
                    foreach ($options as $option) {
                        if ($option == $lfs) {
                            $res = true;
                            break;
                        }
                    }
                    break;
                }
                default: {
                    echo "Operator not implemented yet:" . $this->getOperator() . "<br>";
                    break;
                }
            }
        }

        if (!$processed) {

            $ld = $this->convertDate($lfs);
            $rd = $this->convertDate($rfs);
            if ((isset ($ld)) && (isset ($rd))) {
                $processed = true;
                switch ($this->getOperator()) {
                    case "EQUAL": {
                        $res = ($lf == $rf);
                        break;
                    }
                    case "GREATER_EQUAL": {
                        $res = ($lf >= $rf);
                        break;
                    }
                    case "LESS_EQUAL": {
                        $res = ($lf <= $rf);
                        break;
                    }
                    case "NOT_EQUAL": {
                        $res = ($lf != $rf);
                        break;
                    }
                    case "GREATER": {
                        $res = ($lf > $rf);
                        break;
                    }
                    case "LESS": {
                        $res = ($lf < $rf);
                        break;
                    }
                    case "IN": {
                        $res = false;
                        $options = str_split($rfs);
                        foreach ($options as $option) {
                            if ($option == $lfs) {
                                $res = true;
                                break;
                            }
                        }
                        break;
                    }
                    default: {
                        echo "Operator not implemented yet:" . $this->getOperator() . "<br>";
                        break;
                    }
                }
            }
        }

        if (!$processed) {
            // treat both operands as strings
            switch ($this->getOperator()) {
                case "EQUAL": {
                    $res = ($lfs == $rfs);
                    break;
                }
                case "GREATER_EQUAL": {
                    $res = ($lfs >= $rfs);
                    break;
                }
                case "LESS_EQUAL": {
                    $res = ($lfs <= $rfs);
                    break;
                }
                case "NOT_EQUAL": {
                    $res = ($lfs != $rfs);
                    break;
                }
                case "IN": {
                    $res = false;
                    $options = str_split($rfs);
                    foreach ($options as $option) {
                        if ($option == $lfs) {
                            $res = true;
                            break;
                        }
                    }
                    break;
                }
                default: {
                    
                    break;
                }
            }
        }

        switch ($this->getLogoperator()) {
            case "AND": {
                return $res && $status;
            }
            case "OR": {
                return $res || $status;
            }
            default: {
                return $status;
            }
        }
    }

    public function convertInt($value)
    {
        if ($value == "0") // alleen deze waarde mag 0 zijn
        {
            return 0;
        } else {
            $int = intval($value);
            if ($int == 0) {
                return null; // 
            } else {
                return $int;
            }

        }
    }
    public function convertDate($value)
    {
        $formats = ['dd-MM-yyyy'];
        foreach ($formats as $f) {
            $d = DateTime::createFromFormat($f, $value);
            $is_date = $d && $d->format($f) === $value;

            if ($is_date == true) {
                return $d;
            }
        }

        return null;
    }
    public function WriteXml($writer)
    {

    }
    public function parse($node)
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case 'LogOperator': {
                    $this->setLogoperator($item->nodeValue);
                    break;
                }
                case 'Operator': {
                    $this->setOperator($item->nodeValue);
                    break;
                }
                case 'LeftOperand': {
                    $operand = new Operand();
                    $operand->parse($item);
                    $this->setLeft_operand($operand);
                    break;
                }
                case 'RightOperand': {
                    $operand = new Operand();
                    $operand->parse($item);
                    $this->setRight_operand($operand);
                    break;
                }
            }
        }
    }
}

class Operand implements IOperand
{
    private $value;
    private $elementPath;
    private $attributes;
    private $valueType;

    public function setValue($value)
    {
        $this->value = $value;
    }
    public function getValue()
    {
        return $this->value;
    }

    public function setElementPath($elementPath)
    {
        $this->elementPath = $elementPath;
    }
    public function getElementPath()
    {
        return $this->elementPath;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setValueType($valueType) //eValueType
    {
        switch ($valueType) {
            case "CONSTANT":
                $this->valueType = eValueType::CONSTANT;
                break;
            case "ELEMENTNAME":
                $this->valueType = eValueType::ELEMENTNAME;
                break;
            case "FORMULA":
                $this->valueType = eValueType::FORMULA;
                break;
            default: {
                break;
            }
        }
    }
    public function getValueType()
    {
        return $this->valueType;
    }

    public function writeXml($writer)
    {

    }

    public function parse($node)
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case 'Value': {
                    $this->setValue($item->nodeValue);
                    break;
                }
                case 'Attributes': {
                    $this->setAttributes($item->nodeValue);
                    break;
                }
                case 'ElementPath': {
                    $this->setElementPath($item->nodeValue);
                    break;
                }
                case 'ValueType': {
                    $this->setValueType($item->nodeValue);
                    break;
                }
            }
        }
    }
}

class Label implements ILabel
{
    private $LabelID;
    private $Text;
    private $Description;
    private $DisplayType;
    private $Parameter;
    private $CustomProperties = [];
    private IExpression $VisibleExpression;

    function __construct()
    {
        $this->setVisibleExpression(new Expression());
    }
    public function setLabelID($LabelID)
    {
        $this->LabelID = $LabelID;
    }
    public function getLabelID()
    {
        return $this->LabelID;
    }
    public function setText($Text)
    {
        $this->Text = $Text;
    }
    public function getText()
    {
        return $this->Text;
    }
    public function setDisplayType($DisplayType)
    {
        $this->DisplayType = $DisplayType;
    }
    public function getDisplayType()
    {
        return $this->DisplayType;
    }
    public function setCustomProperties($CustomProperties)
    {
        $this->CustomProperties = $CustomProperties;
    }
    public function getCustomProperties()
    {
        return $this->CustomProperties;
    }
    public function setDescription($Description)
    {
        $this->Description = $Description;
    }
    public function getDescription()
    {
        return $this->Description;
    }
    public function setParameter($Parameter)
    {
        $this->Parameter = $Parameter;
    }
    public function getParameter()
    {
        return $this->Parameter;
    }
    public function setVisibleExpression(IExpression $VisibleExpression)
    {
        $this->VisibleExpression = $VisibleExpression;
    }
    public function getVisibleExpression()
    {
        return $this->VisibleExpression;
    }
    public function parse($node)
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case "LabelID": {
                    $this->setLabelID($item->nodeValue);
                    break;
                }
                case "Text": {
                    $this->setText($item->nodeValue);
                    break;
                }
                case "Description": {
                    $this->setDescription($item->nodeValue);
                    break;
                }
                case "DisplayType": {
                    $this->setDisplayType($item->nodeValue);
                    break;
                }
                case 'CustomProperties': {
                    foreach ($item->childNodes as $cp) {
                        $customProperty = new CustomProperty();
                        $customProperty->parse($cp);
                        $this->CustomProperties[] = $customProperty;
                    }
                    break;
                }
                case "Parameter": {
                    $Parameter = new Parameter();
                    $Parameter->parse($item);
                    $this->setParameter($Parameter);
                    break;
                }
                case 'VisibleExpression': {
                    $expression = new Expression();
                    $expression->parse($item);
                    $this->setVisibleExpression($expression);
                    break;
                }
                default: {
                    echo "Label Not implemented yet:" . $item->nodeName . "<br>";
                    break;
                }
            }
        }
    }
}

class Button implements IButton
{
    private $ButtonID;
    private $Text;
    private $Hint;
    private $Type;
    private $Description;
    private $DisplayType;
    private $SkipFormValidation;
    private $CustomProperties = [];
    private $VisibleExpression;
    private $Parameter;

    function __construct()
    {
        $this->setVisibleExpression(new Expression());
    }
    public function setButtonID($ButtonID)
    {
        $this->ButtonID = $ButtonID;
    }
    public function getButtonID()
    {
        return $this->ButtonID;
    }
    public function setText($Text)
    {
        $this->Text = $Text;
    }
    public function getText()
    {
        return $this->Text;
    }
    public function setHint($Hint)
    {
        $this->Hint = $Hint;
    }
    public function getHint()
    {
        return $this->Hint;
    }
    public function setType($Type)
    {
        $this->Type = $Type;
    }
    public function getType()
    {
        return $this->Type;
    }
    public function setDisplayType($DisplayType)
    {
        $this->DisplayType = $DisplayType;
    }
    public function getDisplayType()
    {
        return $this->DisplayType;
    }
    public function setSkipFormValidation($SkipFormValidation)
    {
        $this->SkipFormValidation = $SkipFormValidation;
    }
    public function getSkipFormValidation()
    {
        return $this->SkipFormValidation;
    }
    public function setCustomProperties($CustomProperties)
    {
        $this->CustomProperties = $CustomProperties;
    }
    public function getCustomProperties()
    {
        return $this->CustomProperties;
    }
    public function setDescription($Description)
    {
        $this->Description = $Description;
    }
    public function getDescription()
    {
        return $this->Description;
    }
    public function setVisibleExpression(IExpression $VisibleExpression)
    {
        $this->VisibleExpression = $VisibleExpression;
    }
    public function getVisibleExpression()
    {
        return $this->VisibleExpression;
    }
    public function setParameter($Parameter)
    {
        $this->Parameter = $Parameter;
    }
    public function getParameter()
    {
        return $this->Parameter;
    }
    public function parse($node)
    {
        foreach ($node->childNodes as $item) {
            switch ($item->nodeName) {
                case "ButtonID": {
                    $this->setButtonID($item->nodeValue);
                    break;
                }
                case "Hint": {
                    $this->setHint($item->nodeValue);
                    break;
                }
                case "Text": {
                    $this->setText($item->nodeValue);
                    break;
                }
                case "Type": {
                    $this->setType($item->nodeValue);
                    break;
                }
                case "Description": {
                    $this->setDescription($item->nodeValue);
                    break;
                }
                case "DisplayType": {
                    $this->setDisplayType($item->nodeValue);
                    break;
                }
                case "SkipFormValidation": {
                    $this->setSkipFormValidation($item->nodeValue);
                    break;
                }
                case "CustomProperties": {
                    foreach ($item->childNodes as $cp) {
                        $customProperty = new CustomProperty();
                        $customProperty->parse($cp);
                        $this->CustomProperties[] = $customProperty;
                    }
                    break;
                }
                case "Parameter": {
                    $Parameter = new Parameter();
                    $Parameter->parse($item);
                    $this->setParameter($Parameter);
                    break;
                }
                case 'VisibleExpression': {
                    $expression = new Expression();
                    $expression->parse($item);
                    $this->setVisibleExpression($expression);
                    break;
                }
                default: {
                    echo "Button Not implemented yet:" . $item->nodeName . "<br>";
                    break;
                }
            }
        }
    }
}

class CustomProperty implements ICustomProperty
{
    private $Name;
    private $Value;
    public function setName($Name)
    {
        $this->Name = $Name;
    }
    public function getName()
    {
        return $this->Name;
    }
    public function setValue($Value)
    {
        $this->Value = $Value;
    }
    public function getValue()
    {
        return $this->Value;
    }

    public function parse($item)
    {
        foreach ($item->childNodes as $values) {
            switch ($values->nodeName) {
                case 'Name':
                    $this->setName($values->nodeValue);
                    break;
                case 'Value':
                    $this->setValue($values->nodeValue);
                    break;
            }
        }
    }
}

class Restrictions implements IRestrictions
{
    private $enumerationValues;
    private $fractionDigits;
    private $length;
    private $maxExclusive;
    private $minExclusive;
    private $maxInclusive;
    private $minInclusive;
    private $maxLength;
    private $minLength;
    private $pattern;
    private $totalDigits;
    private $whiteSpace;

    public function setEnumerationValues($enumerationValues)
    {
        $this->enumerationValues = $enumerationValues;
    }
    public function getEnumerationValues()
    {
        return $this->enumerationValues;
    }
    public function setFractionDigits($fractionDigits)
    {
        $this->fractionDigits = $fractionDigits;
    }
    public function getFractionDigits()
    {
        return $this->fractionDigits;
    }
    public function setLength($length)
    {
        $this->length = $length;
    }
    public function getLength()
    {
        return $this->length;
    }
    public function setMaxExclusive($maxExclusive)
    {
        $this->maxExclusive = $maxExclusive;
    }
    public function getMaxExclusive()
    {
        return $this->maxExclusive;
    }
    public function setMinExclusive($minExclusive)
    {
        $this->minExclusive = $minExclusive;
    }
    public function getMinExclusive()
    {
        return $this->minExclusive;
    }
    public function setMaxInclusive($maxInclusive)
    {
        $this->maxInclusive = $maxInclusive;
    }
    public function getMaxInclusive()
    {
        return $this->maxInclusive;
    }
    public function setMinInclusive($minInclusive)
    {
        $this->minInclusive = $minInclusive;
    }
    public function getMinInclusive()
    {
        return $this->minInclusive;
    }
    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;
    }
    public function getMaxLength()
    {
        return $this->maxLength;
    }
    public function setMinLength($minLength)
    {
        $this->minLength = $minLength;
    }
    public function getMinLength()
    {
        return $this->minLength;
    }
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }
    public function getPattern()
    {
        return $this->pattern;
    }
    public function setTotalDigits($totalDigits)
    {
        $this->totalDigits = $totalDigits;
    }
    public function getTotalDigits()
    {
        return $this->totalDigits;
    }
    public function setWhiteSpace($whiteSpace)
    {
        $this->whiteSpace = $whiteSpace;
    }
    public function getWhiteSpace()
    {
        return $this->whiteSpace;
    }

    public function Valid($question)
    {
        return true;
    }
}

class Parameter implements IParameter
{
    private $Name;
    private $Path;
    private $ParentGroupID;
    private $ParentUserinterfaceID;
    private $ParentConfigID;
    private $Renew;
    private $UsedByEvents;
    public function setName($Name)
    {
        $this->Name = $Name;
    }
    public function getName()
    {
        return $this->Name;
    }
    public function setPath($Path)
    {
        $this->Path = $Path;
    }
    public function getPath()
    {
        return $this->Path;
    }
    public function setParentGroupID($ParentGroupID)
    {
        $this->ParentGroupID = $ParentGroupID;
    }
    public function getParentGroupID()
    {
        return $this->ParentGroupID;
    }
    public function setParentUserinterfaceID($ParentUserinterfaceID)
    {
        $this->ParentUserinterfaceID = $ParentUserinterfaceID;
    }
    public function getParentUserinterfaceID()
    {
        return $this->ParentUserinterfaceID;
    }
    public function setParentConfigID($ParentConfigID)
    {
        $this->ParentConfigID = $ParentConfigID;
    }
    public function getParentConfigID()
    {
        return $this->ParentConfigID;
    }
    public function setRenew($Renew)
    {
        $this->Renew = $Renew;
    }
    public function getRenew()
    {
        return $this->Renew;
    }

    public function setUsedByEvents($UsedByEvents)
    {
        $this->UsedByEvents = $UsedByEvents;
    }
    public function getUsedByEvents()
    {
        return $this->UsedByEvents;
    }

    public function parse($item)
    {
        foreach ($item->childNodes as $parameter) {
            switch ($parameter->nodeName) {
                case 'Name': {
                    $this->setName($parameter->nodeValue);
                    break;
                }
                case 'Path': {
                    $this->setPath($parameter->nodeValue);
                    break;
                }
                case 'ParentGroupID': {
                    $this->setParentGroupID($parameter->nodeValue);
                    break;
                }
                case 'ParentUserinterfaceID': {
                    $this->setParentUserinterfaceID($parameter->nodeValue);
                    break;
                }
                case 'ParentConfigID': {
                    $this->setParentConfigID($parameter->nodeValue);
                    break;
                }
                case 'Renew': {
                    $this->setRenew($parameter->nodeValue);
                    break;
                }
                case 'UsedByEvents': {
                    $this->setUsedByEvents($parameter->nodeValue);
                    break;
                }
            }
        }
    }
}

class TextValueItem implements ITextValueItem
{
    private $Value;
    private $Text;
    private $ImageUrl;
    public function setText($Text)
    {
        $this->Text = $Text;
    }
    public function getText()
    {
        return $this->Text;
    }
    public function setValue($Value)
    {
        $this->Value = $Value;
    }
    public function getValue()
    {
        return $this->Value;
    }
    public function setImageUrl($ImageUrl)
    {
        $this->ImageUrl = $ImageUrl;
    }
    public function getImageUrl()
    {
        return $this->ImageUrl;
    }
    public function parse($item)
    {
        switch ($item->nodeName) {
            case 'Item': {
                foreach ($item->childNodes as $textValueItem) {
                    switch ($textValueItem->nodeName) {
                        case 'Value': {
                            $this->setValue((string) $textValueItem->nodeValue);
                            break;
                        }
                        case 'Text': {
                            $this->setText((string) $textValueItem->nodeValue);
                            break;
                        }
                        case 'ImageUrl': {
                            $this->setImageUrl((string) $textValueItem->nodeValue);
                            break;
                        }
                    }
                }
                break;
            }
        }
    }
    public function writeXml($writer)
    {

    }
}