import './rSlider.min.js';
import { delegate } from 'tippy.js';
import 'tippy.js/dist/tippy.css'; // optional for styling
import '@fortawesome/fontawesome-free/js/all.js';
import Validator from './Validator';

let $xml;
let lastVal = '';

const validator = new Validator();
window.validator = validator;

$(document).ready(() => {
    delegate('#softrules-form', {target: '.sr-tooltip'});

    getXML_HTML(config.routes.firstPage, decodeURIComponent(config.initialXml));
});

function showWaitScreen() {
    $('#waitScreen').show();
}

function hideWaitScreen() {
    $('#waitScreen').hide();
}

function parseXML(xml) {
    try {
        return $.parseXML(xml)
    } catch (error) {
        console.log(error);
    }

    return null;
}

$(document)
    .on('click', '.updateButton, .processButton', e => {
        if (validator.validatePage($('#page'))) {
            updateUserInterface($(e.currentTarget));
        }
    })
    .on('click', '.nextButton', e => {
        // if all visible page controls are valid
        if (validator.validatePage($('#page'))) {
            nextPage($(e.currentTarget));
        }
    })
    .on('click', '.previousButton', e => {
        // no validation check needed
        previousPage($(e.currentTarget));
    })
    .on('focus click', 'input', e => {
        if ($(e.currentTarget).data('toggle') !== 'toggle') {
            lastVal = $(e.currentTarget).val();
        } else {
            lastVal = '#';
        }
    });

function nextPage($item) {
    const xmlText = new XMLSerializer().serializeToString($xml);
    const id = $item.data('id').replace('_', '|');

    getXML_HTML(config.routes.nextPage, xmlText, id);
}

function previousPage($item) {
    const xmlText = new XMLSerializer().serializeToString($xml);
    const id = $item.data('id').replace('_', '|');

    getXML_HTML(config.routes.previousPage, xmlText, id);
}

window.updateUserInterface = function ($item) {
    // only if current control is valid
    if (! validator.fieldPassesValidation($item)) {
        return;
    }

    const value = $item.val();

    if (($item.is('img')) || ($item.is('button')) || (value != lastVal)) { //indien een control van waarde veranderd is of een update op een image/button
        const name = $item.attr('name');
        const path = $item.data('elementpath');

        $($xml).find(`Question > Name:contains("${ name }")`).parent().find(`Question > ElementPath:contains("${ path }")`).parent().children('value').text(value);

        const xmlText = new XMLSerializer().serializeToString($xml);
        const id = $item.data('id').replace('_', '|');

        getXML_HTML(config.routes.updateUserInterface, xmlText, id);
    }
}

window.updateControls = function ($item) {
    const value = $item.val();
    const name = $item.attr('name');
    const path = $item.data('elementpath');

    if (value !== typeof undefined) {
        if ((value != lastVal) || (value === '')) { //indien een control van waarde veranderd is
            $($xml).find(`Question > Name:contains("${ name }")`).parent().find(`Question > ElementPath:contains("${ path }")`).parent().children('value').text(value);

            scriptActions()
                .then(() => validate($item))
                .catch((error) => {
                    // vooralsnog geen actie nodig
                });
        }
    }
}

function validate($item) {
    if (validator.pageValidated) {
        validator.validatePage($('#page'));
    } else {
        validator.fieldPassesValidation($item);
    }
}

function objectToFormData(object) {
    const formData = new FormData();

    Object.entries(object).forEach(([key, value]) => {
        formData.append(key, String(value));
    });

    return formData;
}

function getXML_HTML(methodUrl, xml, id = undefined) {
    showWaitScreen();

    fetch(methodUrl, {
        method: 'POST',
        headers: {
            'Accept': 'application/xml',
        },
        body: objectToFormData({
            product: config.product,
            id,
            xml,
        }),
    })
        .then(async (response) => {
            if (response.ok) {
                return response.text();
            }

            throw new Error('Foutmelding: ' + await response.text());
        })
        .then((data) => {
            $('#softrules-form-content').empty();

            $xml = parseXML(data);

            const xmlText = new XMLSerializer().serializeToString($xml);

            getHTML(xmlText); //generate HTML in html.php
        })
        .catch((error) => {
            console.error(error);

            alert(error);
        })
        .finally(async () => {
            await scriptActions();
            hideWaitScreen();
            validator.pageValidated = false;
        });
}

function getHTML(xml) {
    showWaitScreen();

    fetch(config.routes.renderXml, {
        method: 'POST',
        headers: {
            'Accept': 'text/html',
        },
        body: objectToFormData({
            product: config.product,
            xml,
        }),
    })
        .then(async (response) => {
            if (response.ok) {
                return response.text();
            }

            throw new Error('Foutmelding: ' + await response.text());
        })
        .then((data) => {
            $('#softrules-form-content').html(data);
        })
        .catch((error) => {
            console.error(error);

            alert(error);
        })
        .finally(() => hideWaitScreen());
}

function scriptActions() {
    return new Promise((resolve, reject) => {
        const xml = new XMLSerializer().serializeToString($xml);

        fetch(config.routes.scriptactions, {
            method: 'POST',
            headers: {
                'Accept': 'application/xml',
            },
            body: objectToFormData({
                xml,
            }),
        })
            .then(async (response) => {
                if (response.ok) {
                    return response.text();
                }

                throw new Error('Foutmelding: ' + await response.text());
            })
            .then((data) => {
                const obj = JSON.parse(data);

                for (let i = 0; i < obj.length; i++) {
                    if (obj[i].Command === 'Hide') {
                        $('[data-id=' + obj[i].ItemID + ']').hide();
                        $('#' + obj[i].ItemID + '-row').hide();
                    } else if (obj[i].Command === 'Show') {
                        $('[data-id=' + obj[i].ItemID + ']').show();
                        $('#' + obj[i].ItemID + '-row').show();
                    } else if (obj[i].Command === 'Valid') {
                        $('[data-id=' + obj[i].ItemID + ']').attr('data-isvalid', true);
                    } else if (obj[i].Command === 'Invalid') {
                        $('[data-id=' + obj[i].ItemID + ']').attr('data-isvalid', false);
                    } else if (obj[i].Command === 'Required') {
                        $('[data-id=' + obj[i].ItemID + ']').prop('required', true);
                    } else if (obj[i].Command === 'NotRequired') {
                        $('[data-id=' + obj[i].ItemID + ']').prop('required', false);
                    } else if (obj[i].Command === 'Enabled') {
                        $('[data-id=' + obj[i].ItemID + ']').prop('disabled', false);
                        if ($('[data-id=' + obj[i].ItemID + ']').parent().hasClass('toggle-switch')) {
                            $('[data-id=' + obj[i].ItemID + ']').parent().removeClass('disabled');
                        }
                    } else if (obj[i].Command === 'Disabled') {
                        $('[data-id=' + obj[i].ItemID + ']').prop('disabled', true);
                        if ($('[data-id=' + obj[i].ItemID + ']').parent().hasClass('toggle-switch')) {
                            $('[data-id=' + obj[i].ItemID + ']').parent().addClass('disabled');
                        }
                    }
                }

                resolve();
            })
            .catch((error) => {
                console.log(error);

                alert(error);

                reject(error);
            })
            .finally(() => hideWaitScreen());
    });
}

window.toggleClick = function (item) {
    const name = $(item).attr('id');
    $('#' + name).val($(item).data('value'));
    $(item).siblings().removeClass('active');
    $(item).addClass('active');

    if ($('#' + name).data('updateinterface')) {
        updateUserInterface($(item));
    } else {
        updateControls($(item));
    }
}

window.setSwitchValue = function (item) {
    const name = $(item).attr('id');

    if ($(item).prop('checked')) {
        $(item).val($(item).data('onvalue'));
    } else {
        $(item).val($(item).data('offvalue'));
    }

    if ($('#' + name).data('updateinterface')) {
        updateUserInterface($(item));
    } else {
        updateControls($(item));
    }
}

window.expandClick = function (item) {
    const $target = $($(item).data('target'));
    if ($target.hasClass('show')) {
        $target.removeClass('show');
    } else {
        $target.addClass('show');
    }
}
