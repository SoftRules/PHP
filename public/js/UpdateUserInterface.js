import './rSlider.min.js';

let $xml;

$(document).ready(() => {
    getXML_HTML(config.routes.firstPage, decodeURIComponent(config.initialXml));
    //scriptActions();
});

function parseXML(xml) {
    try {
        return $.parseXML(xml)
    } catch (error) {
        console.log(error);
    }

    return null;
}

function showWaitCursor() {
    document.body.style.cursor = 'wait';
}

function hideWaitCursor() {
    document.body.style.cursor = 'default';
}

$(document)
    .on('click', '.updateButton, .processButton', e => {
        // if current control is valid
        updateUserInterface($(e.currentTarget));
    })
    .on('click', '.nextButton', e => {
        // if all visible page controls are valid
        nextPage($(e.currentTarget));
    })
    .on('click', '.previousButton', e => {
        // no validation check needed
        previousPage($(e.currentTarget));
    })
    .on('focus', ':input', e => {
        $(e.currentTarget).data('prevValue', $(e.currentTarget).val());
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
    const value = $item.val();

    if (! $item.is('button') && $item.data('prevValue') === value) {
        return;
    }

    $item.data('prevValue', value);

    const name = $item.attr('id');
    const path = $item.data('elementpath');

    //check if control is valid to update

    $($xml).find(`Question > Name:contains("${ name }")`).parent().find(`Question > ElementPath:contains("${ path }")`).parent().children('value').text(value);

    const xmlText = new XMLSerializer().serializeToString($xml);
    const id = $item.data('id').replace('_', '|');

    getXML_HTML(config.routes.updateUserInterface, xmlText, id);
}

window.updateControls = function ($item) {
    const value = $item.val();
    const name = $item.attr('id');
    const path = $item.data('elementpath');

    $($xml).find(`Question > Name:contains("${ name }")`).parent().find(`Question > ElementPath:contains("${ path }")`).parent().children('value').text(value);

    scriptActions();
    validateField($item);
}

function objectToFormData(object) {
    const formData = new FormData();

    Object.entries(object).forEach(([key, value]) => {
        formData.append(key, String(value));
    });

    return formData;
}

function getXML_HTML(methodUrl, xml, id = undefined) {
    showWaitCursor();

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
        .finally(() => {
            scriptActions();
            hideWaitCursor();
        });
}

function getHTML(xml) {
    showWaitCursor();

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

            tippy('.sr-tooltip');
        })
        .catch((error) => {
            console.error(error);

            alert(error);
        })
        .finally(() => hideWaitCursor());
}

function scriptActions() {
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
                } else if (obj[i].Command === 'Show') {
                    $('[data-id=' + obj[i].ItemID + ']').show();
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
        })
        .catch((error) => {
            console.log(error);

            alert(error);
        })
        .finally(() => hideWaitCursor());
}

function toggleClick(item) {
    var value = $(item).data('value');
    var name = $(item).attr('id');
    $('#' + name).val(value);
    $(item).siblings().removeClass('active');
    $(item).addClass('active');

    if ($('#' + name).data('updateinterface')) {
        updateUserInterface($(item));
    } else {
        updateControls($(item));
    }
}

window.setSwitchValue = function (item) {
    var name = $(item).attr('id');

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
