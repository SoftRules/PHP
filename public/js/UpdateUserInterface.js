let $xml;

$(document).ready(() => {
    $xml = parseXML(SoftRules_XML);
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
    const sc = {
        ID: $item.data("id").replace("_", "|"),
        XML: new XMLSerializer().serializeToString($xml),
    };

    getXML_HTML('/nextpage.php', sc);
}

function previousPage($item) {
    const sc = {
        ID: $item.data("id").replace("_", "|"),
        XML: new XMLSerializer().serializeToString($xml),
    };

    getXML_HTML('/previouspage.php', sc);
}

function updateUserInterface($item) {
    const value = $item.val();
    const name = $item.attr('id');
    const path = $item.data('elementpath');

    //check if control is valid to update

    $($xml).find(`Question > Name:contains("${name}")`).parent().find(`Question > ElementPath:contains("${path}")`).parent().children('value').text(value);

    const xmlText = new XMLSerializer().serializeToString($xml);
    const sc = {
        ID: $item.data("id").replace("_", "|"),
        XML: xmlText,
    };

    getXML_HTML('/updateUserInterface.php', sc);
}

function getXML_HTML(methodUrl, sc) {
    const form = new FormData();
    form.append('data', JSON.stringify({
        ...sc,
        product,
    }));

    showWaitCursor();

    fetch(methodUrl, {
        method: 'POST',
        body: form, //Waarom via een form? Liever direct JSON.stringify(sc)
        dataType: "html",
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

function getHTML(XML) {
    const form = new FormData();
    form.append('data', JSON.stringify({
        XML,
        product,
    }));

    showWaitCursor();

    fetch('/page.php', {
        method: 'POST',
        body: form, //Waarom via een form? Liever direct JSON.stringify(sc)
        dataType: "html",
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
