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

function fieldRestoreSucces(item, index) {
    ValidationSucces(item, 0);
}

function selectRestoreSucces(item, index) {
    SelectSucces(item);
}

function fieldRestoreFailed(item, index) {
    ValidationFail(item);
}

function isReadonly(ObjectID) {
    var isReadonly = false;
    var attr = $(ObjectID).attr('readonly');

    // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
    if (typeof attr !== typeof undefined && attr !== false) {
        return true;
    }
    return false;
}

function checkPattern(objectID) {
    var patt = $(objectID).attr('pattern');

    // For some browsers, `patt` is undefined; for others, `patt` is false. Check for both.
    if (typeof patt !== typeof undefined && patt !== false) {
        var Regex = $(objectID).attr('pattern');
        var pattern = new RegExp(Regex);
        if (pattern.test($(objectID).val())) {
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

function hasInvalidMessage(ObjectID) {
    var invalidMSG = $(ObjectID).data('invalidmessage');

    // For some browsers, `invalidMSG` is undefined; for others, `invalidMSG` is false. Check for both.
    if (typeof invalidMSG !== typeof undefined && invalidMSG !== false) {
        return true;
    }
    return false;
}

function ValidationSucces(objectID, isGroup) {
    var oID = "#" + $(objectID).attr('id');
	var row = '#sr_'+ $(objectID).data('id') + 'Row';
	
    if (FailedFields.indexOf(oID) != -1) {
        //console.log(oID + ' zit in failed fields');
        index = FailedFields.indexOf(oID);
        //console.log(oID + ' verwijderd van failedfields');
        FailedFields.splice(index, 1);
    }

    if (SuccesFields.indexOf(oID) == -1) {
        SuccesFields.push(oID);
        //console.log(oID + ' is succesvol');
    }

    name = $(objectID).attr('name');
    id = "#" + $(objectID).data('id') + "-Validation";
    validationid = "#" + $(objectID).data('id') + "ValidationMessage";
    if ($(objectID).data('activehiddenfield') == true || $(objectID).data('hiddenfield') == 0) {
        //$(id).html("<i class='fa fa-check fa-2x ValSucces' aria-hidden='true'></i>");
		
		if ((isGroup) == 0)
		{
			$(messageAlert).hide();
		}
		$(row).removeClass('has-error');
    }
    if ($(validationid).length != 0) {
        $(validationid).html('');
    }
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

function ValidationFail(objectID, failMessage, isGroup) {
    var invalidMessage = "Question " + $(objectID).attr('id') + " is invalid.";
	var row = '#sr_'+ $(objectID).data('id') + 'Row';
	
    if (hasInvalidMessage(objectID)) {
        invalidMessage = $(objectID).data('invalidmessage')
    }

    if (failMessage.length != 0) {
        invalidMessage = failMessage;
    }

    if (SuccesFields.indexOf("#" + $(objectID).attr('id')) != -1) {
        index = SuccesFields.indexOf("#" + $(objectID).attr('id'));
        SuccesFields.splice(index, 1);
    }
    if (FailedFields.indexOf("#" + $(objectID).attr('id')) == -1) {
        FailedFields.push("#" + $(objectID).attr('id'));
    }

    fail = true;
    name = $(objectID).attr('id');

    id = $(objectID).data('id') + "-Validation";
    divID = $(objectID).data('id') + "div";
    if ($(objectID).data('activehiddenfield') == true || $(objectID).data('hiddenfield') == 0) {
        //$("#" + id).html("<i class='fa fa-times fa-2x ValError' aria-hidden='true'></i>");
		$(row).addClass('has-error');
        validationid = $(objectID).data('id') + "ValidationMessage";
        //console.log($(validationid).length);
        if (invalidMessage.length != 0) {
            if ($("#" + validationid).length == 0) {
                $("#" + divID).after("<div class='invalidmessage' id='" + validationid + "'></div>");
            }

			if ($(messageAlert).html() == '')
			{
				$(messageAlert).html(invalidMessage);	
			}
			else
			{
				$(messageAlert).append('<br>');
				$(messageAlert).append(invalidMessage);
			}
			
			$(messageAlert).show();
        }
    }
    fail_log += name + " is niet succesvol \n";
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

function ValidateField(FieldID) {
    fail = false;
    fail_log = '';
    var errorMessage = "";

    if ($(FieldID).is('textarea, input')) {

        if (!$(FieldID).prop('required') || $(FieldID).data('hiddenfield') == true || isReadonly($(FieldID))) {

            if ($(FieldID).attr('type') == 'number') {
                if (!$(FieldID).val()) {
                    fail = true;
                }
            }

            if ($(FieldID).attr('data-isvalid') == "false") {
                fail = true;
            }
        }
        else {
            if (!$(FieldID).val()) {
                fail = true;
            }

            if ($(FieldID).attr('data-isvalid') == "false") {
                fail = true;
            }

            if ($(FieldID).val()) {
                if (!checkFractionDigits($(FieldID))) {
                    var digits = $(FieldID).data('fractiondigits');
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

                if (!checkPattern($(FieldID))) {
                    //fail = true;
                }

                if (!checkMinExclusive($(FieldID))) {
                    var value = $(FieldID).data('minexclusive');
                    errorMessage += "Please enter a value greater than " + value + ". <br/>";
                    fail = true
                }

                if (!checkMinInclusive($(FieldID))) {
                    var value = $(FieldID).data('mininclusive');
                    errorMessage += "Please enter a value greater than or equal to " + value + ". <br/>";
                    fail = true
                }

                if (!checkMaxExclusive($(FieldID))) {
                    var value = $(FieldID).data('maxexclusive');
                    errorMessage += "Please enter a value less than " + value + ". <br/>";
                    fail = true
                }

                if (!checkMaxInclusive($(FieldID))) {
                    var value = $(FieldID).data('maxinclusive');
                    errorMessage += "Please enter a value less than or equal to " + value + ". <br/>";
                    fail = true
                }
            }

            if (!$(this).prop('required') || $(this).data('activehiddenfield') === false || isReadonly($(FieldID))) {

            }
        }
    }
    if ($(FieldID).is('select')) {
        if ($(FieldID).attr('data-isvalid') == "false") {
            fail = true;
        }

        else {
            //console.log($(FieldID).attr('id') + ' is succesvol')
            SelectSucces($(FieldID));
        }
    }

    //submit if fail never got set to true
    if (!fail) {
        ValidationSucces($(FieldID), 0);
        return true;
    }
    if (IgnoreValidation === true) {
        ValidationSucces($(FieldID));
        return true;
    }
    else {
		console.log('ValidateField: ' + $(FieldID).attr('id')) + ' failed';
        ValidationFail($(FieldID), errorMessage, 0);
        return false;
    }
}

