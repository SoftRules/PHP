//NOG STRIPPEN EN NAKIJKEN!

const successFields = [];
const failedFields = [];
const successSelects = [];
const failedSelects = [];
let lastVal = '';

$(document).on('focus click', 'input', e => {
    lastVal = $(e.currentTarget).val();
});

function restoreValidation() {
    successFields.forEach(fieldRestoreSucces);
    successSelects.forEach(selectRestoreSucces);
    failedFields.forEach(fieldRestoreFailed);
    failedSelects.forEach(fieldRestoreFailed);
}

function fieldRestoreSucces($item, index) {
    validationSuccess($item, 0);
}

function selectRestoreSucces($item, index) {
    selectSuccess($item);
}

function fieldRestoreFailed($item, index) {
    validationFail($item);
}

function isReadonly($item) {
    const attr = $item.attr('disabled');
    //const attr = $item.disabled;
    // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
    return typeof attr !== typeof undefined && attr !== false;
}

function checkPattern($item) {
    const pattern = $item.attr('pattern');

    // For some browsers, `patt` is undefined; for others, `patt` is false. Check for both.
    if (typeof pattern !== typeof undefined && pattern !== false) {
        return new RegExp(pattern).test($item.val());
    }

    return true;
}

function checkFractionDigits(item) {
    const fractionDigits = $(item).data('fractiondigits');

    // For some browsers, `fractiondigits` is undefined; for others, `fractiondigits` is false. Check for both.
    if (typeof fractionDigits !== typeof undefined && fractionDigits !== false) {
        const currentDecimalPlaces = decimalPlaces($(item).val());

        return currentDecimalPlaces <= fractionDigits;
    }

    return true;
}

function decimalPlaces(num) {
    const match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);

    if (! match) {
        return 0;
    }

    return Math.max(
        0,
        // Number of digits right of decimal point.
        (match[1] ? match[1].length : 0)
        // Adjust for scientific notation.
        - (match[2] ? +match[2] : 0));
}

function checkMaxExclusive(objectID) {
    const maxExclusive = $(objectID).data('maxexclusive');

    // For some browsers, `maxexclusive` is undefined; for others, `maxexclusive` is false. Check for both.
    if (typeof maxExclusive !== typeof undefined && maxExclusive !== false) {
        const currentVal = $(objectID).val();

        return currentVal < maxExclusive;
    }

    return true;
}

function checkMaxInclusive(objectID) {
    const maxInclusive = $(objectID).data('maxinclusive');

    // For some browsers, `maxexclusive` is undefined; for others, `maxexclusive` is false. Check for both.
    if (typeof maxInclusive !== typeof undefined && maxInclusive !== false) {
        const currentVal = $(objectID).val();

        return currentVal <= maxInclusive;
    }

    return true;
}

function checkMinExclusive(objectID) {
    const minExclusive = $(objectID).data('minexclusive');

    // For some browsers, `minexclusive` is undefined; for others, `minexclusive` is false. Check for both.
    if (typeof minExclusive !== typeof undefined && minExclusive !== false) {
        const currentVal = $(objectID).val();

        return currentVal > minExclusive;
    }

    return true;
}

function checkMinInclusive(objectID) {
    const minInclusive = $(objectID).data('mininclusive');

    // For some browsers, `mininclusive` is undefined; for others, `mininclusive` is false. Check for both.
    if (typeof minInclusive !== typeof undefined && minInclusive !== false) {
        const currentVal = $(objectID).val();

        return currentVal >= minInclusive;
    }

    return true;
}

function checkWhiteSpace(objectID) {
    const whiteSpace = $(objectID).data('whitespace');

    // For some browsers, `mininclusive` is undefined; for others, `mininclusive` is false. Check for both.
    if (typeof whiteSpace !== typeof undefined && whiteSpace !== false) {
        const currentVal = $(objectID).val();

        return currentVal >= minInclusive;
    }

    return true;
}

function hasInvalidMessage($item) {
    const invalidMSG = $item.data('invalidmessage');

    // For some browsers, `invalidMSG` is undefined; for others, `invalidMSG` is false. Check for both.
    return typeof invalidMSG !== typeof undefined && invalidMSG !== false;
}

function validationSuccess($item, isGroup) {
    var row = $('#' + $item.attr('id')+'-row')
    $(row).removeClass('has-error');

    $(messageAlert).html('');
    $(messageAlert).hide();
}

function selectSuccess($item) {    
    var row = $('#' + $item.attr('id')+'-row')
    $(row).removeClass('has-error');
    
    var name = $item.attr('name');
    id = $item.data('id') + '-Validation';
    validationid = $item.data('id') + 'ValidationMessage';

    if (failedFields.indexOf('#' + $item.attr('id') != -1)) {
        index = failedFields.indexOf('#' + $item.attr('id'));
        failedFields.splice(index, 1);
    }

    if (successSelects.indexOf('#' + $item.attr('id')) == -1) {
        successSelects.push('#' + $item.attr('id'));
    }

    $('#' + id).html('');
    if ($('#' + validationid).length != 0 && $item.data('hiddenfield') == false || $item.data('hiddenfield') == 0) {
        $('#' + validationid).html('');
    }
}

function validationFail($item, failMessage, isGroup) {
    var invalidMessage = '';
    var row = $('#' + $item.attr('id')+'-row')
    $(row).addClass('has-error');

    var label = $('#' + $item.attr('id')+'-label').text();
    var invalidMessage = label + ' is ongeldig.';

    if (hasInvalidMessage($item)) {
        invalidMessage = $item.data('invalidmessage')
    }
    
    if ((invalidMessage == '') && (failMessage.length != 0)) {
        invalidMessage = failMessage;
    }

    invalidMessage = label + ': ' + invalidMessage;

    if (isGroup) {
        if ($(messageAlert).html() == '') {
            $(messageAlert).html(invalidMessage);
        } else {
            $(messageAlert).append('<br>');
            $(messageAlert).append(invalidMessage);
        }
    } else {
        $(messageAlert).html(invalidMessage);
    }

    $(messageAlert).show();
}


function ValidatePage($page) {
    fail = false;
    var errorMessage = '';
    var failedIDs = '';
    var SingleFieldFail = false;
    fail_log = '';
    
    $(messageAlert).html('');
    $(messageAlert).hide();

    $($page).find('textarea, input, select').each(function () {
        var hasInvisibleParent = $(this).parents(':hidden').length;
        if (hasInvisibleParent == 0) {
            SingleFieldFail = false;
            errorMessage = '';

            if ($(this).is('textarea, input')) {

                if (! $(this).prop('required') || isReadonly($(this))) {

                    if (! isReadonly($(this)) && $(this).attr('type') == 'number') {
                        if (! $(this).val()) {
                            fail = true;
                            failedIDs += $(this).attr('id') + ' ';
                            SingleFieldFail = true;
                        }
                    }
                } else {
                    if (! $(this).val() || $(this).attr('data-isvalid') == 'false') {
                        fail = true;
                        failedIDs += $(this).attr('id') + ' ';
                        SingleFieldFail = true;
                    }
                    if ($(this).val()) {

                        if (! checkFractionDigits($(this))) {
                            var digits = $(this).data('fractiondigits');
                            var errorString = 'The value can have only up to ' + digits + ' digits in the fractional portion.';

                            if (digits == 1) {
                                errorString = 'The value can have only up to one digit in the fractional portion.';
                            }

                            if (digits == 0) {
                                errorString = 'The value cannot have digits in the fractional portion. ';
                            }
                            errorMessage += errorString;
                            fail = true;
                            failedIDs += $(this).attr('id') + ' ';
                            SingleFieldFail = true;
                        }

                        if (! checkMinExclusive($(this))) {
                            var value = $(this).data('minexclusive');
                            errorMessage += 'Please enter a value greater than ' + value ;
                            fail = true;
                            failedIDs += $(this).attr('id') + ' ';
                            SingleFieldFail = true;
                        }

                        if (! checkMinInclusive($(this))) {
                            var value = $(this).data('mininclusive');
                            errorMessage += 'Please enter a value greater than or equal to ' + value + '. <br/>';
                            fail = true;
                            failedIDs += $(this).attr('id') + ' ';
                            SingleFieldFail = true;
                        }

                        if (! checkMaxExclusive($(this))) {
                            var value = $(this).data('maxexclusive');
                            errorMessage += 'Please enter a value less than ' + value + '. <br/>';
                            fail = true;
                            failedIDs += $(this).attr('id') + ' ';
                            SingleFieldFail = true;
                        }

                        if (! checkMaxInclusive($(this))) {
                            var value = $(this).data('maxinclusive');
                            errorMessage += 'Please enter a value less than or equal to ' + value + '. <br/>';
                            fail = true;
                            failedIDs += $(this).attr('id') + ' ';
                            SingleFieldFail = true;
                        }

                        if (! checkPattern($(this))) {
                            if ($(this).attr('data-isvalid') == 'false') {
                                fail = true;
                                failedIDs += $(this).attr('id') + ' ';
                                errorMessage += 'Pattern? ' + value + '. <br/>';
                                SingleFieldFail = true;
                            }
                        }
                    }
                }
            }
            if ($(this).is('select')) {
                if (! $(this).prop('required') || $(this).css('display') == 'none' || isReadonly($(this))) {
                } else {
                    if ($(this).val() == '' || $(this).val() == null || $(this).val() == '#') {
                        fail = true;
                        failedIDs += $(this).attr('id') + ' ';
                        validationFail($(this), errorMessage, 1);
                    } else {
                        if ($(this).attr('data-isvalid') == 'false') {
                            fail = true;
                            failedIDs += $(this).attr('id') + ' ';
                            validationFail($(this), errorMessage, 1);
                        } else {
                            selectSuccess($(this));
                        }
                    }
                }
            }
            if (! $(this).is('select')) {
                if (! SingleFieldFail) {
                    validationSuccess($(this), 1);
                } else {
                    validationFail($(this), errorMessage, 1);
                }
            }
        }
    });

    //submit if fail never got set to true
    if (! fail) {
        return true;
    } else {
        console.log('Failed labels: ' + failedIDs);
        return false;
    }
}

function validateField($item) {
    fail = false;
    fail_log = '';
    var errorMessage = '';

    if ($item.is('textarea, input')) {

        if (! $item.prop('required') || isReadonly($item)) {

            if ($item.attr('data-isvalid') == 'false') {
                fail = true;
            }
        } else {
            if (! $item.val()) {
                fail = true;
            }

            if ($item.attr('type') == 'integer') {
                if (isNaN(parseInt($item.val()))) {
                    errorMessage = 'vul een geheel getal in';
                    fail = true;
                }
            }

            if ($item.attr('data-isvalid') == 'false') {
                fail = true;
            }

            if ($item.val()) {
                if (! checkFractionDigits($item)) {
                    var digits = $item.data('fractiondigits');
                    var errorString = 'de waarde kan maximaal ' + digits + ' decimalen achter de komma bevatten. <br/>';

                    if (digits == 1) {
                        errorString = 'de waarde kan maximaal één decimaal bevatten. <br/>';
                    }

                    if (digits == 0) {
                        errorString = 'de waarde mag geen cijfers achter de komma bevatten. <br/>';
                    }
                    errorMessage += errorString;
                    fail = true;
                }

                if (! checkPattern($item)) {
                    //fail = true;
                }

                if (! checkMinExclusive($item)) {
                    var value = $item.data('minexclusive');
                    errorMessage += 'de waarde moet groter zijn dan ' + value + '. <br/>';
                    fail = true
                }

                if (! checkMinInclusive($item)) {
                    var value = $item.data('mininclusive');
                    errorMessage += 'de waarde moet groter of gelijk zijn als ' + value + '. <br/>';
                    fail = true
                }

                if (! checkMaxExclusive($item)) {
                    var value = $item.data('maxexclusive');
                    errorMessage += 'de waarde moet kleiner zijn dan ' + value + '. <br/>';
                    fail = true
                }

                if (! checkMaxInclusive($item)) {
                    var value = $item.data('maxinclusive');
                    errorMessage += 'de waarde moet kleiner of gelijk zijn als ' + value + '. <br/>';
                    fail = true
                }
            }

            if (! $(this).prop('required') || $(this).data('activehiddenfield') === false || isReadonly($item)) {

            }
        }
    }
    if ($item.is('select')) {
        if ($item.attr('data-isvalid') == 'false') {
            fail = true;
        } else {
            //console.log($item.attr('id') + ' is succesvol')
            selectSuccess($item);
        }
    }

    //submit if fail never got set to true
    if (! fail) {
        validationSuccess($item, 0);
        return true;
    } else {
        console.log(`ValidateField: ${ $item.attr('name') } failed`);
        validationFail($item, errorMessage, 0);
        return false;
    }
}

