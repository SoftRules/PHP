<?php
$PageNumber = 0;
$totalPages = 0;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Origin: GET, POST');
header('Access-Control-Allow-Headers: *');

if (isset ($_POST['data'])) {
    $json = json_decode($_POST['data'], true);
    if (isset ($json['XML'])) {
        require_once ("Includes/softrules.php");
        require_once ("Includes/interfaces.php");
        require_once ("Includes/uiclass.php");

        $UIClass = new UIClass();
        $xml = new DOMDocument();
        $xml->loadXML($json['XML']);
        $UIClass->ParseUIXML($xml);
        echo getHTML($UIClass, $xml);
    }
}

function getHTML($UIClass, $xml)
{
    global $PageNumber, $totalPages;
    $PageNumber = $UIClass->getPage();
    $totalPages = $UIClass->getPages();

    $return = UserinterfaceItems($UIClass->getPage(), $UIClass->getPages(), $UIClass->getItems(), $UIClass->getItems(), $UIClass->getSoftRulesXml(), false, $UIClass->getUserinterfaceData(), $return_value);

    return $return;
}

function UserinterfaceItems($Page, $Pages, $Items, $allItems, $SR_XML, bool $isUpdate, $userinterfaceData, &$return_value) // reference to return_value
{
    global $PageNumber, $totalPages;
    foreach ($Items as $item) {
        if ($item instanceof Group) {
            $id = $item->getGroupID();
            $VisibleText = "";
            $nextButtonVisible = true;
            $previousButtonVisible = true;

            $custompropertyDescription = getCustomPropertyDescriptions($item);

            foreach ($item->getCustomProperties() as $customproperty) {
                switch (strtolower($customproperty->getName())) {
                    case "nextbuttonvisible": {
                        $nextButtonVisible = $customproperty->getValue();
                        break;
                    }
                    case "previousbuttonvisible": {
                        $previousButtonVisible = $customproperty->getValue();
                        break;
                    }
                    case "styletype": {
                        $StyleType = " data-styleType='" . $customproperty->getValue() . "'";
                        break;
                    }
                    case "pagenum": {
                        $PageNum = " data-pagenum='" . $customproperty->getValue() . "'";
                        break;
                    }
                    case "numpages": {
                        $NumPages = " data-numpages='" . $customproperty->getValue() . "'";
                        break;
                    }
                    case "hidebutton": {
                        $HideButton = " data-hidebutton='" . $customproperty->getValue() . "'";
                        break;
                    }
                    case "noborder": {
                        break;
                    }
                    case "headertext": {
                        break;
                    }
                    case "defaultexpandablebehaviour": {
                        break;
                    }
                    case "rowselect": {
                        break;
                    }
                    case "tabelsorteeropties": {
                        break;
                    }
                    default: {
                        echo "Group customproperty not implemented " . $customproperty->getName() . "<br>";
                        break;
                    }
                }
            }

            if (itemVisible($allItems, $item, $userinterfaceData) == false) {
                $VisibleText = " Niet tonen (invisible)";
            }

            $return_value .= "Group Type: <b>" . $item->getType() . "</b> " . $item->getName() . $custompropertyDescription . $VisibleText . "<br>";

            switch (strtolower($item->getType())) {
                case "page": {
                    $nextPageID = $PageNumber;
                    $previousPageID = $PageNumber;

                    $PageNumber = $Page;

                    $return_value .= "<ul>";
                    UserinterfaceItems($Page, $Pages, $item->getItems(), $allItems, $SR_XML, false, $userinterfaceData, $return_value);
                    $return_value .= "</ul>";

                    $return_value .= "<p><div class='row pagination-row'>";

                    if ($PageNumber <= 1 && $PageNumber != $totalPages) {
                        if ($nextButtonVisible == "True") {
                            getNextButtonHtml($id, $nextPageID, $return_value);
                        }
                    }
                    if ($PageNumber >= 2 && $PageNumber <= $totalPages - 1) {
                        if ($previousButtonVisible == "True") {
                            getPreviousButtonHtml($id, $previousPageID, $return_value);
                        }

                        if ($nextButtonVisible == "True") {
                            getNextButtonHtml($id, $nextPageID, $return_value);
                        }
                    }

                    if ($PageNumber == $totalPages && $totalPages != 1) {
                        if ($previousButtonVisible == "True") {
                            getPreviousButtonHtml($id, $previousPageID, $return_value);
                        }

                        if ($nextButtonVisible == "True") {
                            getGoButtonHtml($return_value);
                        }
                    }

                    if ($PageNumber == $totalPages && $totalPages == 1) {
                        if ($nextButtonVisible == "True") {
                            getGoButtonHtml($return_value);
                        }
                    }

                    break;
                }
                case "box": {
                    $return_value .= "<ul>";
                    UserinterfaceItems($Page, $Pages, $item->getItems(), $allItems, $SR_XML, false, $userinterfaceData, $return_value);
                    $return_value .= "</ul>";
                    break;
                }
                case "expandable": {
                    $return_value .= "<ul>";
                    UserinterfaceItems($Page, $Pages, $item->getItems(), $allItems, $SR_XML, false, $userinterfaceData, $return_value);
                    $return_value .= "</ul>";
                    break;
                }
                case "table": {
                    $return_value .= "<ul>";
                    UserinterfaceItems($Page, $Pages, $item->getItems(), $allItems, $SR_XML, false, $userinterfaceData, $return_value);
                    $return_value .= "</ul>";
                    break;
                }
                case "grid": {
                    $return_value .= "<ul>";
                    UserinterfaceItems($Page, $Pages, $item->getItems(), $allItems, $SR_XML, false, $userinterfaceData, $return_value);
                    $return_value .= "</ul>";
                    break;
                }
                case "gridrow": {
                    $return_value .= "<ul>";
                    UserinterfaceItems($Page, $Pages, $item->getItems(), $allItems, $SR_XML, false, $userinterfaceData, $return_value);
                    $return_value .= "</ul>";
                    break;
                }
                case "gridcolumn": {
                    $return_value .= "<ul>";
                    UserinterfaceItems($Page, $Pages, $item->getItems(), $allItems, $SR_XML, false, $userinterfaceData, $return_value);
                    $return_value .= "</ul>";
                    break;
                }
                case "row": {
                    $return_value .= "<ul>";
                    UserinterfaceItems($Page, $Pages, $item->getItems(), $allItems, $SR_XML, false, $userinterfaceData, $return_value);
                    $return_value .= "</ul>";
                    break;
                }
                default: {
                    echo "Group Type not implemented " . $item->getType() . "<br>";
                    break;
                }
            }

        } else if ($item instanceof Question) {
            $id = $item->getQuestionID();
            $VisibleText = "";
            $textvalues = getTextValuesDescription($item);

            if (itemVisible($allItems, $item, $userinterfaceData) == false) {
                $VisibleText = " Niet tonen (invisible)";
            }

            $return_value .= "<li>";
            $return_value .= "Question: " . $item->getDescription() . "(" . $item->getName() . ") Value: " . $item->getValue() . " UpdateUserinterface: " . $item->getUpdateUserinterface() . $textvalues . $VisibleText;
            $return_value .= "</li>";

        } else if ($item instanceof Label) {
            $VisibleText = "";
           
            if (itemVisible($allItems, $item, $userinterfaceData) == false) {
                $VisibleText = " Niet tonen (invisible)";
            }

            $return_value .= "Label Text: " . $item->getText() . $VisibleText;

        } else if ($item instanceof Button) {
            $id = $item->getButtonID();
            $StyleType = "";
            $nextPageID = $PageNumber;
            $VisibleText = "";
            $custompropertyDescription = getCustomPropertyDescriptions($item);

            //displayTypes
            if (strtolower($item->getDisplayType()) == "tile") {
                $tile = true;
            }

            if (itemVisible($allItems, $item, $userinterfaceData) == false) {
                $VisibleText = " Niet tonen (invisible)";
            }

            foreach ($item->getCustomProperties() as $customproperty) {
                switch (strtolower($customproperty->getName())) {
                    case "nextpage": {
                        $name = $customproperty->getValue();
                        break;
                    }
                    case "pictureurl": {
                        $PictureUrl = $customproperty->getValue();
                        break;
                    }
                    case "width": {
                        $width = $customproperty->getValue();
                        break;
                    }
                    case "height": {
                        $height = $customproperty->getValue();
                        break;
                    }
                    case "styletype": {
                        $StyleType = " data-styleType='" . $customproperty->getValue() . "'";
                        break;
                    }
                    case "align": {
                        $Align = " data-align='" . $customproperty->getValue() . "'";
                        break;
                    }
                    default: {
                        break;
                    }
                }
            }

            //type
            switch ($item->getType()) {
                case "update": {
                    $buttonfunction = " id='updateButton' data-type='button' ";
                    break;
                }
                case "submit": {
                    $buttonfunction = " id='processButton' data-type='button' ";
                    break;
                }
                case "navigate": {
                    $buttonfunction = " id='updateButton' data-type='button' ";
                    break;
                }
            }
            $return_value .= "<li>";
            $return_value .= "Button Text: " . $item->getText() . $custompropertyDescription . $VisibleText . "<br>";
            $return_value .= "</li>";
            $return_value .= "<ul><li>";
            $return_value .= "<input type=\"button\" id=\"updateButton\" value='" . $item->getText() . "' data-type='button' data-id=" . $id . ">";
            $return_value .= "</li></ul>";
        }
    }

    return $return_value;
}

function getNextButtonHtml($id, $nextPageID, &$return_value)
{
    $return_value .= "<input type='button' id='nextButton' value='Volgende' class='btn btn-primary sr-nextpage' data-type='Group' data-nextid='" . $nextPageID . "' data-id=" . $id . ">";
}

function getPreviousButtonHtml($id, $previousPageID, &$return_value)
{
    $return_value .= "<input type=button' id='previousButton' value='Vorige' class='btn btn-primary sr-previouspage' data-type='Group' data-previousid='" . $previousPageID . "' data-id=" . $id . ">";
}

function getGoButtonHtml(&$return_value)
{
    $return_value .= "<input type=button' id='nextButton' value='Volgende' class='btn btn-primary sr-nextpage' data-type='Process' id='ProcessButton' onclick='ProcessValidation()'>";
}

function getCustomPropertyDescriptions($item)
{
    $description = "";
    if (count($item->getCustomProperties()) > 0) {
            foreach ($item->getCustomProperties() as $customproperty) {
                if ($description <> "") {
                    $description .= ", ";
                }
                $description .= $customproperty->getName() . "=" . $customproperty->getValue();
            }
            $description = " " . count($item->getCustomProperties()) . " Custom Properties (" . $description . ")";
        }
    return $description;
}

function getTextValuesDescription($item)
{
    $textvalues = "";
    if (count($item->getTextValues()) > 0) {
        $items = "";
        foreach ($item->getTextValues() as $textvalueItem) {
            if ($items <> "") {
                $items .= ", ";
            }
            $items .= $textvalueItem->getValue() . "-" . $textvalueItem->getText();
        }
        $textvalues = " Aantal TextValues: " . count($item->getTextValues()) . " (" . $items . ")";
    }
    return $textvalues;
}
function itemVisible($allItems, $item, $userinterfaceData)
{
    if ($item instanceof Group) {
        if ($item->getVisibleExpression()->Value($allItems, $userinterfaceData)) {
            return true;
        }
    } else if ($item instanceof Question) {
        if ($item->getVisibleExpression()->Value($allItems, $userinterfaceData)) {
            return true;
        }
    } else if ($item instanceof Label) {
        if ($item->getVisibleExpression()->Value($allItems, $userinterfaceData)) {
            return true;
        }
    } else if ($item instanceof Button) {
        if ($item->getVisibleExpression()->Value($allItems, $userinterfaceData)) {
            return true;
        }
    }
    return false;
}
