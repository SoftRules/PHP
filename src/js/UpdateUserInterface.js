var xml;
var $xml;
var MethodUrl;
var date;

$(document).ready(function () {
    ParseXML();
});

function ParseXML() {
    try {
        $xml = $.parseXML(SoftRules_XML)
    }
    catch (error) {
        console.log(error);
    }
}

function showWaitCursor() {
    document.body.style.cursor = 'wait';
}
function hideWaitCursor() {
    document.body.style.cursor = 'default';
}

$(document).on('click', '#updateButton', function (e) {
    // if current control is valid
    UpdateUserInterface($(this));
});

$(document).on('click', '#nextButton', function (e) {
    // if all visible page controls are valid
    NextPage($(this));
});

$(document).on('click', '#previousButton', function (e) {
    // no validation check needed
    PreviousPage($(this));
});

function NextPage(item) {
    var id = $(item).data("id").replace("_", "|");

    MethodUrl = '/nextpage.php';
    var xmlText = new XMLSerializer().serializeToString($xml);
    var sc = {
        "ID": id,
        "XML": xmlText
    };

    getXML_HTML(sc);
}

function PreviousPage(item) {
    var id = $(item).data("id").replace("_", "|");

    MethodUrl = '/previouspage.php';
    var xmlText = new XMLSerializer().serializeToString($xml);
    var sc = {
        "ID": id,
        "XML": xmlText
    };

    getXML_HTML(sc);
}

function UpdateUserInterface(item) {
    var value;
    var name = $(item).attr('id');
    var path = $(item).data('elementpath');

    value = $(item).val();

    //check if control is valid to update

    var id = $(item).data("id").replace("_", "|");
    $($xml).find('Question>Name:contains("' + name + '")').parent().find('Question>ElementPath:contains("' + path + '")').parent().children('value').text(value);

    MethodUrl = '/updateUserInterface.php';
    var xmlText = new XMLSerializer().serializeToString($xml);
    var sc = {
        "ID": id,
        "XML": xmlText
    };

    getXML_HTML(sc);
}

function getXML_HTML(sc) {
    const form = new FormData();
    form.append('data', JSON.stringify(sc));

    showWaitCursor();

    fetch(MethodUrl, {
        method: 'POST',
        body: form, //Waarom via een form? Liever direct JSON.stringify(sc)
        dataType: "html",
    })
        .then(async (response) => {
            return await response.text();
        })
        .then((data) => {
            $(userinterfaceForm).empty();
            SoftRules_XML = data;

            ParseXML();

            var xmlText = new XMLSerializer().serializeToString($xml);

            var sc = {
                "XML": xmlText
            };

            getHTML(sc); //generate HTML in html.php

        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => hideWaitCursor());
}

function getHTML(sc) {
    MethodUrl = '/html.php';

    const form = new FormData();
    form.append('data', JSON.stringify(sc));

    showWaitCursor();

    fetch(MethodUrl, {
        method: 'POST',
        body: form, //Waarom via een form? Liever direct JSON.stringify(sc)
        dataType: "html",
    })
        .then((response) => {
            return response.text();
        })
        .then((data) => {
            $(userinterfaceForm).html(data);
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => hideWaitCursor());
}