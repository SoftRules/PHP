let $xml;

$(document).ready(() => {
    getXML_HTML(config.routes.firstPage, decodeURIComponent(config.initialXml));
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

function updateUserInterface($item) {
    const value = $item.val();
    const name = $item.attr('id');
    const path = $item.data('elementpath');

    //check if control is valid to update

    $($xml).find(`Question > Name:contains("${ name }")`).parent().find(`Question > ElementPath:contains("${ path }")`).parent().children('value').text(value);

    const xmlText = new XMLSerializer().serializeToString($xml);
    const id = $item.data('id').replace('_', '|');

    getXML_HTML(config.routes.updateUserInterface, xmlText, id);
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
            $('#userinterfaceForm').empty();

            $xml = parseXML(data);

            const xmlText = new XMLSerializer().serializeToString($xml);

            getHTML(xmlText); //generate HTML in html.php
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => hideWaitCursor());
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
            $('#userinterfaceForm').html(data);
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => hideWaitCursor());
}
