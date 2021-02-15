window.addEventListener("beforeinstallprompt", function (e) {
    e.preventDefault();
});

window.addEventListener("load", showTablesAfterCompleteLoad);

if (d.getElementById("addNewForm")) {
    d.getElementById("addNewForm").addEventListener(
        "click",
        addKeyValueInputFields
    );
}

if (d.getElementById("createMenuButton")) {
    d.getElementById("createMenuButton").addEventListener(
        "click",
        toggleCreateMenu
    );
}

if (d.getElementById("createMenu")) {
    d.getElementById("createMenu").addEventListener(
        "mouseleave",
        toggleCreateMenu
    );
}

if (d.getElementById("addNewPurchaseForm")) {
    d.getElementById("addNewPurchaseForm").addEventListener(
        "click",
        addPurchaseForm
    );
}

if (d.getElementById("addNewSaleForm")) {
    d.getElementById("addNewSaleForm").addEventListener("click", addSaleForm);
}

d.addEventListener("readystatechange", jumpToCurrentPageMenuTitle);

if (d.getElementById("backButton")) {
    d.getElementById("backButton").addEventListener("click", goToPreviousPage);
}

if (d.getElementById("forwardButton")) {
    d.getElementById("forwardButton").addEventListener("click", goToNextPage);
}

if (d.getElementById("refreshButton")) {
    d.getElementById("refreshButton").addEventListener("click", refreshPage);
}

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

if (d.getElementById("onHandTab")) {
    d.getElementById("onHandTab").addEventListener(
        "click",
        showOnHandMerchandise
    );
}

if (d.getElementById("historyTab")) {
    d.getElementById("historyTab").addEventListener(
        "click",
        showHistoryMerchandise
    );
}

if (d.getElementById("outOfTab")) {
    d.getElementById("outOfTab").addEventListener(
        "click",
        showOutofMerchandise
    );
}

if (d.getElementById("formOne")) {
    d.getElementById("formOne").addEventListener("submit", disableSaveButton);
}

if (d.getElementById("openCloseSaleModal")) {
    d.getElementById("openCloseSaleModal").addEventListener(
        "click",
        openCloseSaleModal
    );
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
    d.getElementById("addNewGdnForm").addEventListener("click", addGdnForm);
}

if (d.getElementsByTagName("table").length) {
    d.getElementsByTagName("table")[0].addEventListener(
        "click",
        disableDeleteForm
    );
}

if (d.getElementById("addNewTransferForm")) {
    d.getElementById("addNewTransferForm").addEventListener(
        "click",
        addTransferForm
    );
}

if (d.getElementById("transferButton")) {
    d.getElementById("transferButton").addEventListener(
        "click",
        openTransferModal
    );
}

if (d.getElementById("addNewPurchaseOrderForm")) {
    d.getElementById("addNewPurchaseOrderForm").addEventListener(
        "click",
        addPurchaseOrderForm
    );
}

if (d.getElementById("closePurchaseOrderButton")) {
    d.getElementById("closePurchaseOrderButton").addEventListener(
        "click",
        closePurchaseOrderModal
    );
}

if (d.getElementById("searchField")) {
    d.getElementById("searchField").addEventListener("keyup", searchDataTables);
}

if (d.getElementById("sortButton")) {
    d.getElementById("sortButton").addEventListener(
        "click",
        sortDataTablesByOnHand
    );
}

if (d.getElementById("openAddGrnModal")) {
    d.getElementById("openAddGrnModal").addEventListener(
        "click",
        openAddGrnModal
    );
}

if (d.getElementById("addNewGrnForm")) {
    d.getElementById("addNewGrnForm").addEventListener("click", addGrnForm);
}

window.addEventListener("load", () => {
    $("#table_id").DataTable({
        responsive: true,
    });
});
