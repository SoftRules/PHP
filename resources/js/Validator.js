//versie 14-06-2024 16:03
export default class Validator {
    constructor() {
        this.pageValidated = false;
    }

    /**
     * @private
     * @param $item
     * @returns {boolean}
     */
    isReadonly($item) {
        const attr = $item.attr('disabled');
        // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
        return typeof attr !== typeof undefined && attr !== false;
    }

    /**
     * @private
     * @param $item
     * @returns {boolean}
     */
    checkPattern($item) {
        const pattern = $item.data('pattern');

        // For some browsers, `patt` is undefined; for others, `patt` is false. Check for both.
        if (typeof pattern !== typeof undefined && pattern !== false) {
            return new RegExp(pattern).test($item.val());
        }

        return true;
    }

    /**
     * @private
     * @param item
     * @returns {boolean}
     */
    checkFractionDigits($item) {
        const fractionDigits = $item.data('fractiondigits');

        // For some browsers, `fractiondigits` is undefined; for others, `fractiondigits` is false. Check for both.
        if (typeof fractionDigits !== typeof undefined && fractionDigits !== false) {
            const currentDecimalPlaces = this.decimalPlaces($item.val());

            return currentDecimalPlaces <= fractionDigits;
        }

        return true;
    }

    /**
     * @private
     * @returns {number}
     */
    decimalPlaces(num) {
        const match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);

        if (! match) {
            return 0;
        }

        return Math.max(
            0,
            // Number of digits right of decimal point.
            (match[1] ? match[1].length : 0)
            // Adjust for scientific notation.
            - (match[2] ? +match[2] : 0),
        );
    }

    /**
     * @private
     * @returns {boolean}
     */
    checkMaxExclusive($item) {
        const maxExclusive = $item.data('maxexclusive');

        // For some browsers, `maxexclusive` is undefined; for others, `maxexclusive` is false. Check for both.
        if (typeof maxExclusive !== typeof undefined && maxExclusive !== false) {
            const currentVal = $item.val();

            return currentVal < maxExclusive;
        }

        return true;
    }

    /**
     * @private
     * @returns {boolean}
     */
    checkMaxInclusive($item) {
        const maxInclusive = $item.data('maxinclusive');

        // For some browsers, `maxexclusive` is undefined; for others, `maxexclusive` is false. Check for both.
        if (typeof maxInclusive !== typeof undefined && maxInclusive !== false) {
            const currentVal = $item.val();

            return currentVal <= maxInclusive;
        }

        return true;
    }

    /**
     * @private
     * @returns {boolean}
     */
    checkMinExclusive($item) {
        const minExclusive = $item.data('minexclusive');

        // For some browsers, `minexclusive` is undefined; for others, `minexclusive` is false. Check for both.
        if (typeof minExclusive !== typeof undefined && minExclusive !== false) {
            const currentVal = $item.val();

            return currentVal > minExclusive;
        }

        return true;
    }

    /**
     * @private
     * @returns {boolean}
     */
    checkMinInclusive($item) {
        const minInclusive = $item.data('mininclusive');

        // For some browsers, `mininclusive` is undefined; for others, `mininclusive` is false. Check for both.
        if (typeof minInclusive !== typeof undefined && minInclusive !== false) {
            const currentVal = $item.val();

            return currentVal >= minInclusive;
        }

        return true;
    }

    /**
     * @private
     * @returns {boolean}
     */
    checkLength($item) {
        const length = $item.data('length');
        if (typeof length !== typeof undefined && length !== '') {
            return String($item.val()).length === length;       
        }

        return true;
    }

    /**
     * @private
     * @returns {boolean}
     */
    hasInvalidMessage($item) {
        const invalidMSG = $item.data('invalidmessage');

        // For some browsers, `invalidMSG` is undefined; for others, `invalidMSG` is false. Check for both.
        return typeof invalidMSG !== typeof undefined && invalidMSG !== false && invalidMSG !== '';
    }

    /**
     * @private
     */
    validationSuccess($item) {
        var row = $('#' + $item.attr('id') + '-row')
        $(row).removeClass('has-error');

        if (! this.pageValidated) {
            $(messageAlert).html('');
            $(messageAlert).hide();
        }
    }

    /**
     * @private
     */
    selectSuccess($item) {
        this.validationSuccess($item);
    }

    /**
     * @private
     */
    showValidationErrors($item, failMessage) {
        var invalidMessage = ' is ongeldig.';
        var row = $('#' + $item.attr('id') + '-row')
        $(row).addClass('has-error');

        var label = $('#' + $item.attr('id') + '-label').text();

        if (this.hasInvalidMessage($item)) {
            invalidMessage = $item.data('invalidmessage')
        }

        if (failMessage !== typeof undefined && failMessage !== '') {
            invalidMessage = failMessage;
        }

        invalidMessage = label + ': ' + invalidMessage;

        if (this.pageValidated) {
            if ($(messageAlert).html() === '') {
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

    /**
     * @public
     * @param $page
     * @returns {boolean}
     */
    validatePage($page) {
        this.pageValidated = true;

        $(messageAlert).empty().hide();

        const hasFailure = $($page).find('textarea, input, select').get()
            .filter(el => ! $(el).parents(':hidden').length)
            .some(el => ! validator.fieldPassesValidation($(el))); 

        if (hasFailure) {
            window.scrollTo({top: 0, behavior: 'smooth'});
        }

        return ! hasFailure;
    }

    /**
     * @public
     * @param $item
     * @returns {boolean}
     */
    fieldPassesValidation($item) {
        let fail = false;
        let errorMessage = '';

        if ($item.is('select')) {
            if ($item.val() === '' || $item.val() === null || $item.val() === '#') {
                errorMessage = 'kies een optie';
                fail = true;
            } else {
                this.selectSuccess($item);
            }
        } else if ($item.is('textarea, input')) {
            if ($item.attr('data-isvalid') === 'false') {
                fail = true;
            }

            if (! this.isReadonly($item)) { 
                if ($item.val()) {
                    if ($item.attr('data-type') === 'integer') {
                        if (isNaN(parseInt($item.val()))) {
                            errorMessage = 'vul een geheel getal in';
                            fail = true;
                        }
                    }

                    if (! this.checkFractionDigits($item)) {
                        var digits = $item.data('fractiondigits');
                        var errorString = 'de waarde kan maximaal ' + digits + ' decimalen achter de komma bevatten';

                        if (digits == 1) {
                            errorString = 'de waarde kan maximaal één decimaal bevatten';
                        } else if (digits == 0) {
                            errorString = 'de waarde mag geen cijfers achter de komma bevatten';
                        }
                        errorMessage += errorString;
                        fail = true;
                    }

                    if (! this.checkPattern($item)) {
                        fail = true;
                    }

                    if (! this.checkMinExclusive($item)) {
                        var value = $item.data('minexclusive');
                        errorMessage += 'de waarde moet groter zijn dan ' + value;
                        fail = true
                    }

                    if (! this.checkMinInclusive($item)) {
                        var value = $item.data('mininclusive');
                        errorMessage += 'de waarde moet groter of gelijk zijn als ' + value;
                        fail = true
                    }

                    if (! this.checkMaxExclusive($item)) {
                        var value = $item.data('maxexclusive');
                        errorMessage += 'de waarde moet kleiner zijn dan ' + value;
                        fail = true
                    }

                    if (! this.checkMaxInclusive($item)) {
                        var value = $item.data('maxinclusive');
                        errorMessage += 'de waarde moet kleiner of gelijk zijn als ' + value;
                        fail = true
                    }

                    if (! this.checkLength($item)) {
                        var value = $item.data('length');
                        errorMessage += 'de waarde moet ' + value + ' lang zijn';
                        fail = true
                    }
                } else {
                    if ($item.prop('required')) {
                        fail = true;
                    }
                }
            }
        }

        //submit if fail never got set to true
        if (! fail) {
            this.validationSuccess($item);

            return true;
        }

        this.showValidationErrors($item, errorMessage);

        return false;
    }

    canValidate($item) {
        if ($item.is('button')) {
            const novalidate = $item.data('novalidate');
            
            if (typeof novalidate !== typeof undefined && novalidate !== false) {
                return false;
            }        
        }

        return true;
    }
}
