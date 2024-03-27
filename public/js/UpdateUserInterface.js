let $xml;

$(document).ready(() => {
    ParseXML();
});

function ParseXML() {
    try {
        $xml = $.parseXML(SoftRules_XML)
    } catch (error) {
        console.log(error);
    }
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
        UpdateUserInterface($(e.currentTarget));
    })
    .on('click', '.nextButton', e => {
        // if all visible page controls are valid
        NextPage($(e.currentTarget));
    })
    .on('click', '.previousButton', e => {
        // no validation check needed
        PreviousPage($(e.currentTarget));
    });

function NextPage($item) {
    const sc = {
        ID: $item.data("id").replace("_", "|"),
        XML: new XMLSerializer().serializeToString($xml)
    };

    getXML_HTML('/nextpage.php', sc);
}

function PreviousPage($item) {
    const sc = {
        ID: $item.data("id").replace("_", "|"),
        XML: new XMLSerializer().serializeToString($xml)
    };

    getXML_HTML('/previouspage.php', sc);
}

function UpdateUserInterface($item) {
    const value = $item.val();
    const name = $item.attr('id');
    const path = $item.data('elementpath');

    //check if control is valid to update

    const id = $item.data("id").replace("_", "|");
    $($xml).find(`Question > Name:contains("${name}")`).parent().find(`Question > ElementPath:contains("${path}")`).parent().children('value').text(value);

    const xmlText = new XMLSerializer().serializeToString($xml);
    const sc = {
        ID: id,
        XML: xmlText
    };

    getXML_HTML('/updateUserInterface.php', sc);
}

function getXML_HTML(MethodUrl, sc) {
    const form = new FormData();
    form.append('data', JSON.stringify(sc));

    showWaitCursor();

    fetch(MethodUrl, {
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
            SoftRules_XML = data;

            ParseXML();

            const xmlText = new XMLSerializer().serializeToString($xml);

            const sc = {
                XML: xmlText,
            };

            getHTML(sc); //generate HTML in html.php

        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => hideWaitCursor());
}

function getHTML(sc) {
    const form = new FormData();
    form.append('data', JSON.stringify(sc));

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
