let $xml;

$(document).ready(() => {
    $xml = parseXML(config.initialXml);
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

    getXML_HTML(config.routes.nextPage, id, xmlText);
}

function previousPage($item) {
    const xmlText = new XMLSerializer().serializeToString($xml);
    const id = $item.data('id').replace('_', '|');

    getXML_HTML(config.routes.previousPage, id, xmlText);
}

function updateUserInterface($item) {
    const value = $item.val();
    const name = $item.attr('id');
    const path = $item.data('elementpath');

    //check if control is valid to update

    $($xml).find(`Question > Name:contains("${ name }")`).parent().find(`Question > ElementPath:contains("${ path }")`).parent().children('value').text(value);

    const xmlText = new XMLSerializer().serializeToString($xml);
    const id = $item.data('id').replace('_', '|');

    getXML_HTML(config.routes.updateUserInterface, id, xmlText);
}

function getXML_HTML(methodUrl, id, xml) {
    showWaitCursor();

    fetch(methodUrl, {
        method: 'POST',
        headers: {
            'Accept': 'text/html',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
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
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
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
