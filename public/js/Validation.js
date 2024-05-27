let PageValidated = false;

function isReadonly($item) {
    const attr = $item.attr('disabled');
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
    return typeof invalidMSG !== typeof undefined && invalidMSG !== false && invalidMSG !== '';
}

function validationSuccess($item) {
    var row = $('#' + $item.attr('id')+'-row')
    $(row).removeClass('has-error');

    if (!PageValidated) {
        $(messageAlert).html('');
        $(messageAlert).hide();
    }
}

function selectSuccess($item) {    
    validationSuccess($item);  
}

function validationFail($item, failMessage) {
    var invalidMessage = ' is ongeldig.';
    var row = $('#' + $item.attr('id')+'-row')
    $(row).addClass('has-error');

    var label = $('#' + $item.attr('id')+'-label').text();

    if (hasInvalidMessage($item)) {
        invalidMessage = $item.data('invalidmessage')
    }
    
    if ((invalidMessage === '') && (failMessage !== typeof undefined) && (failMessage.length != 0)) {
        invalidMessage = failMessage;
    }

    invalidMessage = label + ': ' + invalidMessage;

    if (PageValidated) {
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
    PageValidated = true; 
    let fail = false;
    
    $(messageAlert).html('');
    $(messageAlert).hide();

    $($page).find('textarea, input, select').each(function () {
        var hasInvisibleParent = $(this).parents(':hidden').length;
        if (hasInvisibleParent == 0) {
           
            errorMessage = '';

            if (! validateField($(this))) {
                fail = true;
            }
        }
    });

    return ! fail;
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
        }
    }
    if ($item.is('select')) {
        if ($item.val() == '' || $item.val() == null || $item.val() == '#') {
            errorMessage = 'kies een optie';
            fail = true;
        } else {
            selectSuccess($item);
        }
    }

    //submit if fail never got set to true
    if (! fail) {
        validationSuccess($item);
        return true;
    } else {
        console.log(`ValidateField: ${ $item.attr('name') } failed`);
        validationFail($item, errorMessage);
        return false;
    }
}

