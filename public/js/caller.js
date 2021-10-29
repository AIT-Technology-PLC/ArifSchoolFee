window.addEventListener("beforeinstallprompt", function (e) {
    e.preventDefault();
});

if (d.getElementById("addNewForm")) {
    d.getElementById("addNewForm").addEventListener(
        "click",
        addKeyValueInputFields
    );
}

if (d.getElementById("addNewPurchaseForm")) {
    d.getElementById("addNewPurchaseForm").addEventListener(
        "click",
        addPurchaseDetail
    );
}

if (d.getElementById("addNewSaleForm")) {
    d.getElementById("addNewSaleForm").addEventListener("click", addSaleDetail);
}

window.addEventListener("load", jumpToCurrentPageMenuTitle);

if (d.getElementById("addToInventoryModal")) {
    d.getElementById("openAddToInventoryModal").addEventListener(
        "click",
        openAddToInventoryModal
    );

    d.getElementById("closeModal").addEventListener(
        "click",
        openAddToInventoryModal
    );

    d.getElementById("addToInventoryNotNow").addEventListener(
        "click",
        openAddToInventoryModal
    );
}

if (d.getElementById("formOne")) {
    d.getElementById("formOne").addEventListener("submit", disableSaveButton);
}

if (d.getElementById("warehouseId")) {
    d.getElementById("warehouseId").addEventListener("change", changeWarehouse);
}

if (d.getElementById("burger-menu")) {
    d.getElementById("burger-menu").addEventListener(
        "click",
        toggleLeftMenuOnMobile
    );
}

if (d.getElementById("addNewGdnForm")) {
    d.getElementById("addNewGdnForm").addEventListener("click", addGdnDetail);
}

if (d.getElementById("addNewTransferForm")) {
    d.getElementById("addNewTransferForm").addEventListener(
        "click",
        addTransferDetail
    );
}

if (d.getElementById("addNewPurchaseOrderForm")) {
    d.getElementById("addNewPurchaseOrderForm").addEventListener(
        "click",
        addPurchaseOrderDetail
    );
}

if (d.getElementById("addNewGrnForm")) {
    d.getElementById("addNewGrnForm").addEventListener("click", addGrnForm);
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

window.addEventListener("online", showOnlineBox);

window.addEventListener("offline", showOfflineBox);

window.addEventListener("load", showOfflineBoxPermanent);

if (d.getElementById("markAllNotificationsAsRead")) {
    d.getElementById("markAllNotificationsAsRead").addEventListener(
        "click",
        openMarkAllNotificationsAsReadModal
    );
}

if (d.getElementsByClassName("showRowDetails").length) {
    for (let element of d.getElementsByClassName("showRowDetails")) {
        element.addEventListener("click", showRowDetailsPage);
    }
}

if (d.getElementById("selectAllCheckboxes")) {
    d.getElementById("selectAllCheckboxes").addEventListener(
        "click",
        selectAllCheckboxes
    );
}

if (d.getElementsByClassName("summernote").length) {
    initializeSummernote();
}

if (d.getElementById("addNewSivForm")) {
    d.getElementById("addNewSivForm").addEventListener("click", addSivDetail);
}

if (d.getElementById("addNewProformaInvoiceForm")) {
    d.getElementById("addNewProformaInvoiceForm").addEventListener(
        "click",
        addProformaInvoiceDetail
    );
}

if (d.querySelectorAll(".swal").length) {
    d.querySelectorAll(".swal").forEach((element) => {
        element.addEventListener("click", openSwalModal);
    });
}

if (d.getElementById("addNewDamageForm")) {
    d.getElementById("addNewDamageForm").addEventListener(
        "click",
        addDamageDetail
    );
}

if (d.getElementById("addNewAdjustmentForm")) {
    d.getElementById("addNewAdjustmentForm").addEventListener(
        "click",
        addAdjustmentDetail
    );
}

if (d.getElementById("addNewReturnForm")) {
    d.getElementById("addNewReturnForm").addEventListener(
        "click",
        addReturnDetail
    );
}

if (d.getElementById("addNewReservationForm")) {
    d.getElementById("addNewReservationForm").addEventListener(
        "click",
        addReservationDetail
    );
}

if (d.getElementsByClassName("summernote-table").length) {
    modifySummernoteTableClass();
}

if (d.getElementsByClassName("select2-products").length) {
    initializeSelect2Products();
}

if (d.querySelectorAll("input[type=number]").length) {
    document.addEventListener("wheel", disableInputTypeNumberMouseWheel);
}
