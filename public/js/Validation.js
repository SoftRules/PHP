//NOG STRIPPEN EN NAKIJKEN1

var SuccesFields = [];
var FailedFields = [];
var SuccesSelects = [];
var FailedSelects = [];
var lastVal = "";

$(document).on('focus click', 'input', function (e) {
    lastVal = $(this).val();
});

function restoreValidation() {
    SuccesFields.forEach(fieldRestoreSucces);
    SuccesSelects.forEach(selectRestoreSucces);
    FailedFields.forEach(fieldRestoreFailed);
    FailedSelects.forEach(fieldRestoreFailed);
}

function fieldRestoreSucces($item, index) {
    ValidationSucces($item, 0);
}

function selectRestoreSucces($item, index) {
    SelectSucces($item);
}

function fieldRestoreFailed($item, index) {
    ValidationFail($item);
}

function isReadonly($item) {
    var isReadonly = false;
    var attr = $item.attr('readonly');

    // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
    if (typeof attr !== typeof undefined && attr !== false) {
        return true;
    }
    return false;
}

function checkPattern($item) {
    var patt = $item.attr('pattern');

    // For some browsers, `patt` is undefined; for others, `patt` is false. Check for both.
    if (typeof patt !== typeof undefined && patt !== false) {
        var Regex = $item.attr('pattern');
        var pattern = new RegExp(Regex);
        if (pattern.test($item.val())) {
            return true;
        }
        else {
            return false;
        }
    }
    return true;
}

function checkFractionDigits(objectID) {
    var patt = $(objectID).data('fractiondigits');

    // For some browsers, `fractiondigits` is undefined; for others, `fractiondigits` is false. Check for both.
    if (typeof patt !== typeof undefined && patt !== false) {
        var digits = $(objectID).data('fractiondigits');
        var currentDigits = retr_dec($(objectID).val());

        if (currentDigits <= digits) {
            return true;
        }
        else {
            return false;
        }
    }
    return true;
}

function checkMaxExclusive(objectID) {
    var patt = $(objectID).data('maxexclusive');

    // For some browsers, `maxexclusive` is undefined; for others, `maxexclusive` is false. Check for both.
    if (typeof patt !== typeof undefined && patt !== false) {
        var maxExclusive = $(objectID).data('maxexclusive');
        var currentVal = $(objectID).val();

        if (currentVal < maxExclusive) {
            return true;
        }
        else {
            return false;
        }
    }
    return true;
}

function checkMaxInclusive(objectID) {
    var patt = $(objectID).data('maxinclusive');

    // For some browsers, `maxexclusive` is undefined; for others, `maxexclusive` is false. Check for both.
    if (typeof patt !== typeof undefined && patt !== false) {
        var maxInclusive = $(objectID).data('maxinclusive');
        var currentVal = $(objectID).val();

        if (currentVal <= maxInclusive) {
            return true;
        }
        else {
            return false;
        }
    }
    return true;
}

function checkMinExclusive(objectID) {
    var patt = $(objectID).data('minexclusive');

    // For some browsers, `minexclusive` is undefined; for others, `minexclusive` is false. Check for both.
    if (typeof patt !== typeof undefined && patt !== false) {
        var minExclusive = $(objectID).data('minexclusive');
        var currentVal = $(objectID).val();

        if (currentVal > minExclusive) {
            return true;
        }
        else {
            return false;
        }
    }
    return true;
}

function checkMinInclusive(objectID) {
    var patt = $(objectID).data('mininclusive');

    // For some browsers, `mininclusive` is undefined; for others, `mininclusive` is false. Check for both.
    if (typeof patt !== typeof undefined && patt !== false) {
        var minInclusive = $(objectID).data('mininclusive');
        var currentVal = $(objectID).val();

        if (currentVal >= minInclusive) {
            return true;
        }
        else {
            return false;
        }
    }
    return true;
}

function checkWhiteSpace(objectID) {
    var patt = $(objectID).data('whitespace');

    // For some browsers, `mininclusive` is undefined; for others, `mininclusive` is false. Check for both.
    if (typeof patt !== typeof undefined && patt !== false) {
        var whiteSpace = $(objectID).data('whitespace');
        var currentVal = $(objectID).val();

        if (currentVal >= minInclusive) {
            return true;
        }
        else {
            return false;
        }
    }
    return true;
}

function hasInvalidMessage($item) {
    var invalidMSG = $item.data('invalidmessage');

    // For some browsers, `invalidMSG` is undefined; for others, `invalidMSG` is false. Check for both.
    if (typeof invalidMSG !== typeof undefined && invalidMSG !== false) {
        return true;
    }
    return false;
}

function ValidationSucces($item, isGroup) {
    var row = $item.data('id') + '_row';
    $(row).removeClass('has-error');

    $(messageAlert).html('');
    $(messageAlert).hide(); 
}

function SelectSucces(objectID) {
    name = $(objectID).attr('name');
    id = $(objectID).data('id') + "-Validation";
    validationid = $(objectID).data('id') + "ValidationMessage";

    if (FailedFields.indexOf("#" + $(objectID).attr('id') != -1)) {
        index = FailedFields.indexOf("#" + $(objectID).attr('id'));
        FailedFields.splice(index, 1);
    }

    if (SuccesSelects.indexOf("#" + $(objectID).attr('id')) == -1) {
        SuccesSelects.push("#" + $(objectID).attr('id'));
    }

    $("#" + id).html("");
    if ($("#" + validationid).length != 0 && $(objectID).data('hiddenfield') == false || $(objectID).data('hiddenfield') == 0) {
        $('#' + validationid).html('');
    }
}

function ValidationFail($item, failMessage, isGroup) {
    var invalidMessage = "Question " + $item.attr('id') + " is invalid.";
	var row = '#'+ $item.data('id') + '_row';
	
    if (hasInvalidMessage($item)) {
        invalidMessage = $item.data('invalidmessage')
    }

    if (failMessage.length != 0) {
        invalidMessage = failMessage;
    }

    if (isGroup) {
        if ($(messageAlert).html() == '')
        {
            $(messageAlert).html(invalidMessage);	
        }
        else
        {
            $(messageAlert).append('<br>');
            $(messageAlert).append(invalidMessage);
        }
    } else {
        $(messageAlert).html(invalidMessage);	
    }
    
    $(messageAlert).show();    
}

function ProcessValidation() {
    return ValidateGroup(currentPage);
}

function ValidateGroup(GroupID) {
    fail = false;
    var errorMessage = "";
	var failedIDs = "";
    var SingleFieldFail = false;
    fail_log = '';
    var id = "#" + GroupID;
	
	$(messageAlert).html('');
	$(messageAlert).hide();
	
    $('#' + GroupID).find('textarea, input, select').each(function () {
        var hasInvisibleParent = $(this).parents(':hidden').length;
        if (hasInvisibleParent == 0) {
            SingleFieldFail = false;
            errorMessage = "";
			
            if ($(this).is('textarea, input')) {

                if (!$(this).prop('required') || $(this).data('activehiddenfield') == false || isReadonly($(this))) {

                    if (!$(this).data('activehiddenfield') == false && !isReadonly($(this)) && $(this).attr('type') == 'number') {
                        if (!$(this).val()) {
                            fail = true;
							failedIDs += $(this).attr('id') + ' ';
                            SingleFieldFail = true;
                        }
                    }
                }
                else {
                    if (!$(this).val() || $(this).attr('data-isvalid') == "false") {
                        fail = true;
						failedIDs += $(this).attr('id') + ' ';
                        SingleFieldFail = true;
                    }
                    if ($(this).val()) {

                        if (!checkFractionDigits($(this))) {
                            var digits = $(this).data('fractiondigits');
                            var errorString = "The value can have only up to " + digits + " digits in the fractional portion. <br/>";

                            if (digits == 1) {
                                errorString = "The value can have only up to one digit in the fractional portion. <br/>";
                            }

                            if (digits == 0) {
                                errorString = "The value cannot have digits in the fractional portion. <br/>";
                            }
                            errorMessage += errorString;
                            fail = true;
							failedIDs += $(this).attr('id') + ' ';
                            SingleFieldFail = true;
                        }

                        if (!checkMinExclusive($(this))) {
                            var value = $(this).data('minexclusive');
                            errorMessage += "Please enter a value greater than " + value + ". <br/>";
                            fail = true;
							failedIDs += $(this).attr('id') + ' ';
                            SingleFieldFail = true;
                        }

                        if (!checkMinInclusive($(this))) {
                            var value = $(this).data('mininclusive');
                            errorMessage += "Please enter a value greater than or equal to " + value + ". <br/>";
                            fail = true;
							failedIDs += $(this).attr('id') + ' ';
                            SingleFieldFail = true;
                        }

                        if (!checkMaxExclusive($(this))) {
                            var value = $(this).data('maxexclusive');
                            errorMessage += "Please enter a value less than " + value + ". <br/>";
                            fail = true;
							failedIDs += $(this).attr('id') + ' ';
                            SingleFieldFail = true;
                        }

                        if (!checkMaxInclusive($(this))) {
                            var value = $(this).data('maxinclusive');
                            errorMessage += "Please enter a value less than or equal to " + value + ". <br/>";
                            fail = true;
							failedIDs += $(this).attr('id') + ' ';
                            SingleFieldFail = true;
                        }

                        if (!checkPattern($(this))) {
                            if ($(this).attr('data-isvalid') == "false") {
                                fail = true;
								failedIDs += $(this).attr('id') + ' ';
								errorMessage += "Pattern? " + value + ". <br/>";
                                SingleFieldFail = true;
                            }
                        }
                    }
                }
            }
            if ($(this).is('select')) {
                if (!$(this).prop('required') || $(this).data('activehiddenfield') == false || $(this).css('display') == 'none' || isReadonly($(this))) {
                } else {
                    if ($(this).val() == '' || $(this).val() == null) {
                        fail = true;
						failedIDs += $(this).attr('id') + ' ';
						ValidationFail($(this), errorMessage, 1);
                    }
                    else {
                        if ($(this).attr('data-isvalid') == "false") {
                            fail = true;
							failedIDs += $(this).attr('id') + ' ';
							ValidationFail($(this), errorMessage, 1);
                        }
                        else {
                            SelectSucces($(this));
                        }
                    }
                }
            }
            if (!$(this).is('select')) {
                if (!SingleFieldFail || IgnoreValidation === true) {
                    ValidationSucces($(this),1);
                }

                else {					
                    ValidationFail($(this), errorMessage, 1);
                }
            }
        }
    });

    //submit if fail never got set to true
    if (!fail || IgnoreValidation === true) {
        return true;
    }

    else {		
		console.log('Failed labels: ' + failedIDs);
        return false;
    }
}

function ValidateField($item) {
    fail = false;
    fail_log = '';
    var errorMessage = "";

    if ($item.is('textarea, input')) {

        if (!$item.prop('required') || isReadonly($item)) {

            if ($item.attr('data-isvalid') == "false") {
                fail = true;
            }
        }
        else {
            if (!$item.val()) {
                fail = true;
            }

            if ($item.attr('type') == 'integer') {
                if (isNaN(parseInt($item.val())))
                {
                    errorMessage = "Vul een geheel getal in";
                    fail = true;
                }
            }

            if ($item.attr('data-isvalid') == "false") {
                fail = true;
            }

            if ($item.val()) {
                if (!checkFractionDigits($item)) {
                    var digits = $item.data('fractiondigits');
                    var errorString = "The value can have only up to " + digits + " digits in the fractional portion. <br/>";

                    if (digits == 1) {
                        errorString = "The value can have only up to one digit in the fractional portion. <br/>";
                    }

                    if (digits == 0) {
                        errorString = "The value cannot have digits in the fractional portion. <br/>";
                    }
                    errorMessage += errorString;
                    fail = true;
                }

                if (!checkPattern($item)) {
                    //fail = true;
                }

                if (!checkMinExclusive($item)) {
                    var value = $item.data('minexclusive');
                    errorMessage += "Please enter a value greater than " + value + ". <br/>";
                    fail = true
                }

                if (!checkMinInclusive($item)) {
                    var value = $item.data('mininclusive');
                    errorMessage += "Please enter a value greater than or equal to " + value + ". <br/>";
                    fail = true
                }

                if (!checkMaxExclusive($item)) {
                    var value = $item.data('maxexclusive');
                    errorMessage += "Please enter a value less than " + value + ". <br/>";
                    fail = true
                }

                if (!checkMaxInclusive($item)) {
                    var value = $item.data('maxinclusive');
                    errorMessage += "Please enter a value less than or equal to " + value + ". <br/>";
                    fail = true
                }
            }

            if (!$(this).prop('required') || $(this).data('activehiddenfield') === false || isReadonly($item)) {

            }
        }
    }
    if ($item.is('select')) {
        if ($item.attr('data-isvalid') == "false") {
            fail = true;
        }

        else {
            //console.log($item.attr('id') + ' is succesvol')
            SelectSucces($item);
        }
    }

    //submit if fail never got set to true
    if (!fail) {
        ValidationSucces($item, 0);
        return true;
    } else {
        console.log('ValidateField: ' + $item.attr('id')) + ' failed';
        ValidationFail($item, errorMessage, 0);
        return false;
    }
    
}

