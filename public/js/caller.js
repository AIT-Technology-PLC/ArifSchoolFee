window.addEventListener("beforeinstallprompt", function (e) {
    e.preventDefault();
});

if (d.getElementById("addNewForm")) {
    d.getElementById("addNewForm").addEventListener(
        "click",
        addKeyValueInputFields
    );
}

if (d.getElementsByName("createMenuModal").length) {
    for (let element of d.getElementsByName("createMenuModal")) {
        element.addEventListener("click", toggleCreateMenu);
    }
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

window.addEventListener("load", jumpToCurrentPageMenuTitle);

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

if (d.getElementById("availableTab")) {
    d.getElementById("availableTab").addEventListener(
        "click",
        showAvailableMerchandise
    );
}

if (d.getElementById("reservedTab")) {
    d.getElementById("reservedTab").addEventListener(
        "click",
        showReservedMerchandise
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
    d.getElementById("addNewGdnForm").addEventListener("click", addGdnDetail);
}

if (d.getElementsByClassName("delete-form").length) {
    for (const element of d.getElementsByClassName("delete-form")) {
        element.addEventListener("submit", disableDeleteForm);
    }
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

if (d.getElementById("openAddGrnModal")) {
    d.getElementById("openAddGrnModal").addEventListener(
        "click",
        openAddGrnModal
    );
}

if (d.getElementById("addNewGrnForm")) {
    d.getElementById("addNewGrnForm").addEventListener("click", addGrnForm);
}

if (d.querySelectorAll("table.regular-datatable").length) {
    window.addEventListener("load", initiateDataTables);
}

if (d.getElementById("openApproveGdnModal")) {
    d.getElementById("openApproveGdnModal").addEventListener(
        "click",
        openApproveGdnModal
    );
}

if (d.getElementById("openApproveGrnModal")) {
    d.getElementById("openApproveGrnModal").addEventListener(
        "click",
        openApproveGrnModal
    );
}

if (d.getElementById("openApproveTransferModal")) {
    d.getElementById("openApproveTransferModal").addEventListener(
        "click",
        openApproveTransferModal
    );
}

if (d.getElementById("addNewTenderForm")) {
    d.getElementById("addNewTenderForm").addEventListener(
        "click",
        addTenderForm
    );
}

window.addEventListener("online", showOnlineBox);

window.addEventListener("offline", showOfflineBox);

window.addEventListener("load", showOfflineBoxPermanent);

if (d.getElementById("notificationBox")) {
    d.getElementById("notificationButtonDesktop").addEventListener(
        "click",
        toggleNotificationBox
    );

    d.getElementById("notificationButtonMobile").addEventListener(
        "click",
        toggleNotificationBox
    );

    d.getElementById("closeNotificationButton").addEventListener(
        "click",
        toggleNotificationBox
    );

    window.addEventListener("load", () =>
        setInterval(showNewNotifications, 360000)
    );

    d.getElementById("notificationBox").addEventListener(
        "click",
        markNotificationAsRead
    );
}

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
    d.getElementById("addNewSivForm").addEventListener("click", addSivForm);
}

if (d.getElementById("openApproveSivModal")) {
    d.getElementById("openApproveSivModal").addEventListener(
        "click",
        openApproveSivModal
    );
}

if (d.getElementById("openExecuteSivModal")) {
    d.getElementById("openExecuteSivModal").addEventListener(
        "click",
        openExecuteSivModal
    );
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
        addDamageForm
    );
}

if (d.getElementById("addNewAdjustmentForm")) {
    d.getElementById("addNewAdjustmentForm").addEventListener(
        "click",
        addAdjustmentForm
    );
}

if (d.getElementById("addNewReturnForm")) {
    d.getElementById("addNewReturnForm").addEventListener(
        "click",
        addReturnForm
    );
}

if (d.getElementById("addNewReservationForm")) {
    d.getElementById("addNewReservationForm").addEventListener(
        "click",
        addReservationForm
    );
}

if (d.getElementsByClassName("summernote-table").length) {
    modifySummernoteTableClass();
}

if (d.getElementsByName("menu-accordion").length) {
    for (let element of d.getElementsByName("menu-accordion")) {
        element.addEventListener("click", toggleMenu);
    }
}

if (d.getElementsByClassName("select2-products").length) {
    initializeSelect2Products();
}

if (d.querySelectorAll("input[type=number]").length) {
    document.addEventListener("wheel", disableInputTypeNumberMouseWheel);
}
