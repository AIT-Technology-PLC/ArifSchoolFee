window.addEventListener("beforeinstallprompt", function (e) {
    e.preventDefault();
});

if (d.getElementById("addNewForm")) {
    d.getElementById("addNewForm").addEventListener(
        "click",
        addKeyValueInputFields
    );
}

if (d.getElementById("formOne")) {
    d.getElementById("formOne").addEventListener("submit", disableSaveButton);
}

if (d.querySelectorAll("table.regular-datatable").length) {
    window.addEventListener("load", initiateDataTables);
}

if (d.getElementById("addNewTenderForm")) {
    d.getElementById("addNewTenderForm").addEventListener(
        "click",
        addTenderDetail
    );
}

if (d.getElementsByClassName("summernote").length) {
    initializeSummernote();
}

if (d.getElementById("addNewProformaInvoiceForm")) {
    d.getElementById("addNewProformaInvoiceForm").addEventListener(
        "click",
        addProformaInvoiceDetail
    );
}

if (d.getElementById("addNewAdjustmentForm")) {
    d.getElementById("addNewAdjustmentForm").addEventListener(
        "click",
        addAdjustmentDetail
    );
}

if (d.getElementsByClassName("summernote-table").length) {
    modifySummernoteTableClass();
}

if (d.querySelectorAll("input[type=number]").length) {
    document.addEventListener("wheel", disableInputTypeNumberMouseWheel);
}

if (d.getElementsByName("remove-detail-button").length) {
    attachListenersToRemoveDetailButton();
}
