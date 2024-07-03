//versie 03-07-2024 
import './rSlider.min.js';
import { delegate } from 'tippy.js';
import 'tippy.js/dist/tippy.css'; // optional for styling
import '@fortawesome/fontawesome-free/js/all.js';
import Validator from './Validator';

let $xml;

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
    var id = $item.data('id')
    var value = $item.val();    
    const lastvalue = $item.data('value');
    const name = $item.attr('name');
    const path = $item.data('elementpath');
    
    if ($item.attr('type') == "date") { //set date in correct format
        value = toDate(value);      
    }    

    if (($item.is('img')) || ($item.is('button')) || valuechanged(value, lastvalue)) { //indien een control van waarde veranderd is of een update op een image/button
        $($xml).find(`Question > Name:contains("${ name }")`).parent().find(`Question > ElementPath:contains("${ path }")`).parent().children('value').text(value);
                    
        scriptActions(id)
            .finally(() => {                 
                validate($item);
                $item.data('value', value); //bijwerken value attribute

                // only if current control is valid or data-novalidate = true;
                if (validator.canValidate($item)) {
                    if (! validator.fieldPassesValidation($item)) {
                        return;
                    }                
                }
                
                const xmlText = new XMLSerializer().serializeToString($xml);
        
                id = $item.data('id').replace('_', '|'); // waarom hier een replacement?
        
                getXML_HTML(config.routes.updateUserInterface, xmlText, id);
            })
            .catch((error) => {
                // vooralsnog geen actie nodig
            });
    }
}

function valuechanged(value, lastvalue) {
    const fvalue = parseFloat(value);
    const lvalue = parseFloat(lastvalue);  

    if ((fvalue !== NaN) && (lvalue !== NaN)) {
        if (fvalue != lvalue) { 
            return true;
        } else {
            return false;
        }
    }
    
    if (value != lastvalue) {
        return true;
    }

    return false;
}

window.updateControls = function ($item) {
    const id = $item.data('id')
    var value = $item.val();    
    const lastvalue = $item.data('value');
    const name = $item.attr('name');
    const path = $item.data('elementpath');
    
    if ($item.attr('type') == "date") { //set date in correct format
        value = toDate(value);      
    }

    if (value !== typeof undefined) {
        if ((valuechanged(value, lastvalue)) && (value !== '') && (value !== undefined)) { //indien een control van waarde veranderd is
            $($xml).find(`Question > Name:contains("${ name }")`).parent().find(`Question > ElementPath:contains("${ path }")`).parent().children('value').text(value);
            
            scriptActions(id)
                .then(() => validate($item))
                .then(() => { 
                    $item.data('value', value); //bijwerken value attribute
                })
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

function toDate(value) {
    const newDate = new Date(value);
    const day = String(newDate.getDate()).padStart(2, '0');
    const month = String(newDate.getMonth() + 1).padStart(2, '0');
    const year = newDate.getFullYear();
    return `${day}-${month}-${year}`;  
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
            await scriptActions(null);//skip id after UpdateUserinterface
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

function scriptActions(id) {
    return new Promise((resolve, reject) => {
        const xml = new XMLSerializer().serializeToString($xml);

        fetch(config.routes.scriptactions, {
            method: 'POST',
            headers: {
                'Accept': 'application/xml',
            },
            body: objectToFormData({
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
                const obj = JSON.parse(data);

                for (let i = 0; i < obj.length; i++) {
                    if (obj[i].Command === 'Hide') {
                        console.log('Hide: ' + obj[i].ItemID + ' class:' + $('[data-id=' + obj[i].ItemID + ']').attr('class') + ' name:' + $('[data-id=' + obj[i].ItemID + ']').attr('name'));
                        $('[data-id=' + obj[i].ItemID + ']').hide();
                        $('#' + obj[i].ItemID + '-row').hide();
                    } else if (obj[i].Command === 'Show') {
                        $('[data-id=' + obj[i].ItemID + ']').show();
                        $('#' + obj[i].ItemID + '-row').show();
                    } else if (obj[i].Command === 'Valid') {
                        console.log('Valid: ' + obj[i].ItemID + ' class:' + $('[data-id=' + obj[i].ItemID + ']').attr('class') + ' name:' + $('[data-id=' + obj[i].ItemID + ']').attr('name'));
                        $('[data-id=' + obj[i].ItemID + ']').attr('data-isvalid', true);
                    } else if (obj[i].Command === 'Invalid') {
                        console.log('Invalid: ' + obj[i].ItemID + ' class:' + $('[data-id=' + obj[i].ItemID + ']').attr('class') + ' name:' + $('[data-id=' + obj[i].ItemID + ']').attr('name'));
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
                    } else if (obj[i].Command === 'HideButton') {                        
                        $('[data-id=' + obj[i].ItemID + ']').hide();
                    }
                }

                resolve();
            })
            .catch((error) => {
                console.log(error);

                alert(error);

                reject(error);
            })
            .finally(() => { 
            });
    });
}

window.toggleClick = function (item) {
    const name = $(item).data('id');
    $('#' + name).val($(item).data('value'));
    $(item).siblings().removeClass('active');
    $(item).addClass('active');

    if ($('#' + name).data('updateinterface')) {
        updateUserInterface($('#' + name));
    } else {
        updateControls($('#' + name));
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
