function addProformaInvoiceDetail() {
    let proformaInvoiceDetailsWrapper = d.getElementById(
        "proforma-invoice-details"
    );

    let proformaInvoiceDetails = d.getElementsByClassName(
        "proforma-invoice-detail"
    );

    let totalProformaInvoiceDetails = proformaInvoiceDetails.length;

    let proformaInvoiceDetail = proformaInvoiceDetails[0].cloneNode(true);

    proformaInvoiceDetail.setAttribute("x-data", "productDataProvider");

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    originalSelect.setAttribute("x-init", "select2");

    originalSelect.removeAttribute("onchange");

    proformaInvoiceDetail.querySelector(
        "[name=item-number]"
    ).innerText = `Item ${totalProformaInvoiceDetails + 1}`;

    proformaInvoiceDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute(
            "for",
            `proformaInvoice[${totalProformaInvoiceDetails}][product_id]`
        );

    proformaInvoiceDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1].innerHTML = "";

    proformaInvoiceDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1]
        .appendChild(originalSelect);

    proformaInvoiceDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].id = `proformaInvoice[${totalProformaInvoiceDetails}][product_id]`;

    proformaInvoiceDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].name = `proformaInvoice[${totalProformaInvoiceDetails}][product_id]`;

    proformaInvoiceDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll("select")[1].classList = "select2-products";

    proformaInvoiceDetail
        .querySelectorAll(".column")[0]
        .querySelector(".control > select")
        .remove();

    proformaInvoiceDetail
        .querySelectorAll(".column")[1]
        .querySelector("label")
        .setAttribute(
            "for",
            `proformaInvoice[${totalProformaInvoiceDetails}][quantity]`
        );

    proformaInvoiceDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "input"
        ).id = `proformaInvoice[${totalProformaInvoiceDetails}][quantity]`;

    proformaInvoiceDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "input"
        ).name = `proformaInvoice[${totalProformaInvoiceDetails}][quantity]`;

    proformaInvoiceDetail
        .querySelectorAll(".column")[1]
        .querySelector("input").value = "";

    proformaInvoiceDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "button"
        ).id = `proformaInvoice[${totalProformaInvoiceDetails}][product_id]Quantity`;

    proformaInvoiceDetail
        .querySelectorAll(".column")[1]
        .querySelector("button").innerText = "";

    proformaInvoiceDetail
        .querySelectorAll(".column")[2]
        .querySelector("label")
        .setAttribute(
            "for",
            `proformaInvoice[${totalProformaInvoiceDetails}][unit_price]`
        );

    proformaInvoiceDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "input"
        ).id = `proformaInvoice[${totalProformaInvoiceDetails}][unit_price]`;

    proformaInvoiceDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "input"
        ).name = `proformaInvoice[${totalProformaInvoiceDetails}][unit_price]`;

    proformaInvoiceDetail
        .querySelectorAll(".column")[2]
        .querySelector("input").value = "";

    proformaInvoiceDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "button"
        ).id = `proformaInvoice[${totalProformaInvoiceDetails}][product_id]Price`;

    proformaInvoiceDetail
        .querySelectorAll(".column")[2]
        .querySelector("button").innerText = "";

    proformaInvoiceDetail
        .querySelectorAll(".column")[3]
        .querySelector("label")
        .setAttribute(
            "for",
            `proformaInvoice[${totalProformaInvoiceDetails}][discount]`
        );

    proformaInvoiceDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "input"
        ).id = `proformaInvoice[${totalProformaInvoiceDetails}][discount]`;

    proformaInvoiceDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "input"
        ).name = `proformaInvoice[${totalProformaInvoiceDetails}][discount]`;

    proformaInvoiceDetail
        .querySelectorAll(".column")[3]
        .querySelector("input").value = "";

    proformaInvoiceDetail
        .querySelectorAll(".column")[4]
        .querySelector("label")
        .setAttribute(
            "for",
            `proformaInvoice[${totalProformaInvoiceDetails}][specification]`
        );

    proformaInvoiceDetail
        .querySelectorAll(".column")[4]
        .querySelector(
            "textarea"
        ).id = `proformaInvoice[${totalProformaInvoiceDetails}][specification]`;

    proformaInvoiceDetail
        .querySelectorAll(".column")[4]
        .querySelector(
            "textarea"
        ).name = `proformaInvoice[${totalProformaInvoiceDetails}][specification]`;

    proformaInvoiceDetail
        .querySelectorAll(".column")[4]
        .querySelector(".control .note-editor.note-frame")
        .remove();

    proformaInvoiceDetailsWrapper.appendChild(proformaInvoiceDetail);

    attachListenersToRemoveDetailButton();

    initializeSummernote();

    $(
        `[name='proformaInvoice[${totalProformaInvoiceDetails}][specification]']`
    ).summernote("reset");
}

function addGdnDetail() {
    let gdnDetailsWrapper = d.getElementById("gdn-details");

    let gdnDetails = d.getElementsByClassName("gdn-detail");

    let totalGdnDetails = gdnDetails.length;

    let gdnDetail = gdnDetails[0].cloneNode(true);

    gdnDetail.setAttribute("x-data", "productDataProvider");

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    originalSelect.setAttribute("x-init", "select2");

    originalSelect.removeAttribute("onchange");

    gdnDetail.querySelector("[name=item-number]").innerText = `Item ${
        totalGdnDetails + 1
    }`;

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute("for", `gdn[${totalGdnDetails}][product_id]`);

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1].innerHTML = "";

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1]
        .appendChild(originalSelect);

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].id = `gdn[${totalGdnDetails}][product_id]`;

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].name = `gdn[${totalGdnDetails}][product_id]`;

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll("select")[1].classList = "select2-products";

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelector(".control > select")
        .remove();

    gdnDetail
        .querySelectorAll(".column")[1]
        .querySelector("label")
        .setAttribute("for", `gdn[${totalGdnDetails}][warehouse_id]`);

    gdnDetail
        .querySelectorAll(".column")[1]
        .querySelector("select").id = `gdn[${totalGdnDetails}][warehouse_id]`;

    gdnDetail
        .querySelectorAll(".column")[1]
        .querySelector("select").name = `gdn[${totalGdnDetails}][warehouse_id]`;

    gdnDetail
        .querySelectorAll(".column")[2]
        .querySelector("label")
        .setAttribute("for", `gdn[${totalGdnDetails}][quantity]`);

    gdnDetail
        .querySelectorAll(".column")[2]
        .querySelector("input").id = `gdn[${totalGdnDetails}][quantity]`;

    gdnDetail
        .querySelectorAll(".column")[2]
        .querySelector("input").name = `gdn[${totalGdnDetails}][quantity]`;

    gdnDetail.querySelectorAll(".column")[2].querySelector("input").value = "";

    gdnDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "button"
        ).id = `gdn[${totalGdnDetails}][product_id]Quantity`;

    gdnDetail.querySelectorAll(".column")[2].querySelector("button").innerText =
        "";

    gdnDetail
        .querySelectorAll(".column")[3]
        .querySelector("label")
        .setAttribute("for", `gdn[${totalGdnDetails}][unit_price]`);

    gdnDetail
        .querySelectorAll(".column")[3]
        .querySelector("input").id = `gdn[${totalGdnDetails}][unit_price]`;

    gdnDetail
        .querySelectorAll(".column")[3]
        .querySelector("input").name = `gdn[${totalGdnDetails}][unit_price]`;

    gdnDetail.querySelectorAll(".column")[3].querySelector("input").value = "";

    gdnDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "button"
        ).id = `gdn[${totalGdnDetails}][product_id]Price`;

    gdnDetail.querySelectorAll(".column")[3].querySelector("button").innerText =
        "";

    gdnDetail
        .querySelectorAll(".column")[4]
        .querySelector("label")
        .setAttribute("for", `gdn[${totalGdnDetails}][discount]`);

    gdnDetail
        .querySelectorAll(".column")[4]
        .querySelector("input").id = `gdn[${totalGdnDetails}][discount]`;

    gdnDetail
        .querySelectorAll(".column")[4]
        .querySelector("input").name = `gdn[${totalGdnDetails}][discount]`;

    gdnDetail.querySelectorAll(".column")[4].querySelector("input").value = "";

    gdnDetail
        .querySelectorAll(".column")[5]
        .querySelector("label")
        .setAttribute("for", `gdn[${totalGdnDetails}][description]`);

    gdnDetail
        .querySelectorAll(".column")[5]
        .querySelector("textarea").id = `gdn[${totalGdnDetails}][description]`;

    gdnDetail
        .querySelectorAll(".column")[5]
        .querySelector(
            "textarea"
        ).name = `gdn[${totalGdnDetails}][description]`;

    gdnDetail.querySelectorAll(".column")[5].querySelector("textarea").value =
        "";

    gdnDetailsWrapper.appendChild(gdnDetail);

    attachListenersToRemoveDetailButton();
}

function addReservationDetail() {
    let reservationDetailsWrapper = d.getElementById("reservation-details");

    let reservationDetails = d.getElementsByClassName("reservation-detail");

    let totalReservationDetails = reservationDetails.length;

    let reservationDetail = reservationDetails[0].cloneNode(true);

    reservationDetail.setAttribute("x-data", "productDataProvider");

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    originalSelect.setAttribute("x-init", "select2");

    originalSelect.removeAttribute("onchange");

    reservationDetail.querySelector("[name=item-number]").innerText = `Item ${
        totalReservationDetails + 1
    }`;

    reservationDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute(
            "for",
            `reservation[${totalReservationDetails}][product_id]`
        );

    reservationDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1].innerHTML = "";

    reservationDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1]
        .appendChild(originalSelect);

    reservationDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].id = `reservation[${totalReservationDetails}][product_id]`;

    reservationDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        ).name = `reservation[${totalReservationDetails}][product_id]`;

    reservationDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll("select")[1].classList = "select2-products";

    reservationDetail
        .querySelectorAll(".column")[0]
        .querySelector(".control > select")
        .remove();

    reservationDetail
        .querySelectorAll(".column")[1]
        .querySelector("label")
        .setAttribute(
            "for",
            `reservation[${totalReservationDetails}][warehouse_id]`
        );

    reservationDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "select"
        ).id = `reservation[${totalReservationDetails}][warehouse_id]`;

    reservationDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "select"
        ).name = `reservation[${totalReservationDetails}][warehouse_id]`;

    reservationDetail
        .querySelectorAll(".column")[2]
        .querySelector("label")
        .setAttribute(
            "for",
            `reservation[${totalReservationDetails}][quantity]`
        );

    reservationDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "input"
        ).id = `reservation[${totalReservationDetails}][quantity]`;

    reservationDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "input"
        ).name = `reservation[${totalReservationDetails}][quantity]`;

    reservationDetail
        .querySelectorAll(".column")[2]
        .querySelector("input").value = "";

    reservationDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "button"
        ).id = `reservation[${totalReservationDetails}][product_id]Quantity`;

    reservationDetail
        .querySelectorAll(".column")[2]
        .querySelector("button").innerText = "";

    reservationDetail
        .querySelectorAll(".column")[3]
        .querySelector("label")
        .setAttribute(
            "for",
            `reservation[${totalReservationDetails}][unit_price]`
        );

    reservationDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "input"
        ).id = `reservation[${totalReservationDetails}][unit_price]`;

    reservationDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "input"
        ).name = `reservation[${totalReservationDetails}][unit_price]`;

    reservationDetail
        .querySelectorAll(".column")[3]
        .querySelector("input").value = "";

    reservationDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "button"
        ).id = `reservation[${totalReservationDetails}][product_id]Price`;

    reservationDetail
        .querySelectorAll(".column")[3]
        .querySelector("button").innerText = "";

    reservationDetail
        .querySelectorAll(".column")[4]
        .querySelector("label")
        .setAttribute(
            "for",
            `reservation[${totalReservationDetails}][discount]`
        );

    reservationDetail
        .querySelectorAll(".column")[4]
        .querySelector(
            "input"
        ).id = `reservation[${totalReservationDetails}][discount]`;

    reservationDetail
        .querySelectorAll(".column")[4]
        .querySelector(
            "input"
        ).name = `reservation[${totalReservationDetails}][discount]`;

    reservationDetail
        .querySelectorAll(".column")[4]
        .querySelector("input").value = "";

    reservationDetail
        .querySelectorAll(".column")[5]
        .querySelector("label")
        .setAttribute(
            "for",
            `reservation[${totalReservationDetails}][description]`
        );

    reservationDetail
        .querySelectorAll(".column")[5]
        .querySelector(
            "textarea"
        ).id = `reservation[${totalReservationDetails}][description]`;

    reservationDetail
        .querySelectorAll(".column")[5]
        .querySelector(
            "textarea"
        ).name = `reservation[${totalReservationDetails}][description]`;

    reservationDetail
        .querySelectorAll(".column")[5]
        .querySelector("textarea").value = "";

    reservationDetailsWrapper.appendChild(reservationDetail);

    attachListenersToRemoveDetailButton();
}

function addAdjustmentDetail() {
    let adjustmentDetailsWrapper = d.getElementById("adjustment-details");

    let adjustmentDetails = d.getElementsByClassName("adjustment-detail");

    let totalAdjustmentDetails = adjustmentDetails.length;

    let adjustmentDetail = adjustmentDetails[0].cloneNode(true);

    adjustmentDetail.setAttribute("x-data", "productDataProvider");

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    originalSelect.setAttribute("x-init", "select2");

    originalSelect.removeAttribute("onchange");

    adjustmentDetail.querySelector("[name=item-number]").innerText = `Item ${
        totalAdjustmentDetails + 1
    }`;

    adjustmentDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute(
            "for",
            `adjustment[${totalAdjustmentDetails}][product_id]`
        );

    adjustmentDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1].innerHTML = "";

    adjustmentDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1]
        .appendChild(originalSelect);

    adjustmentDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].id = `adjustment[${totalAdjustmentDetails}][product_id]`;

    adjustmentDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].name = `adjustment[${totalAdjustmentDetails}][product_id]`;

    adjustmentDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll("select")[1].classList = "select2-products";

    adjustmentDetail
        .querySelectorAll(".column")[0]
        .querySelector(".control > select")
        .remove();

    adjustmentDetail
        .querySelectorAll(".column")[1]
        .querySelector("label")
        .setAttribute("for", `adjustment[${totalAdjustmentDetails}][quantity]`);

    adjustmentDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "input"
        ).id = `adjustment[${totalAdjustmentDetails}][quantity]`;

    adjustmentDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "input"
        ).name = `adjustment[${totalAdjustmentDetails}][quantity]`;

    adjustmentDetail
        .querySelectorAll(".column")[1]
        .querySelector("input").value = "";

    adjustmentDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "button"
        ).id = `adjustment[${totalAdjustmentDetails}][product_id]Quantity`;

    adjustmentDetail
        .querySelectorAll(".column")[1]
        .querySelector("button").innerText = "";

    adjustmentDetail
        .querySelectorAll(".column")[2]
        .querySelector("label")
        .setAttribute(
            "for",
            `adjustment[${totalAdjustmentDetails}][is_subtract]`
        );

    adjustmentDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "select"
        ).id = `adjustment[${totalAdjustmentDetails}][is_subtract]`;

    adjustmentDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "select"
        ).name = `adjustment[${totalAdjustmentDetails}][is_subtract]`;

    adjustmentDetail
        .querySelectorAll(".column")[3]
        .querySelector("label")
        .setAttribute(
            "for",
            `adjustment[${totalAdjustmentDetails}][warehouse_id]`
        );

    adjustmentDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "select"
        ).id = `adjustment[${totalAdjustmentDetails}][warehouse_id]`;

    adjustmentDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "select"
        ).name = `adjustment[${totalAdjustmentDetails}][warehouse_id]`;

    adjustmentDetail
        .querySelectorAll(".column")[4]
        .querySelector("label")
        .setAttribute("for", `adjustment[${totalAdjustmentDetails}][reason]`);

    adjustmentDetail
        .querySelectorAll(".column")[4]
        .querySelector(
            "textarea"
        ).id = `adjustment[${totalAdjustmentDetails}][reason]`;

    adjustmentDetail
        .querySelectorAll(".column")[4]
        .querySelector(
            "textarea"
        ).name = `adjustment[${totalAdjustmentDetails}][reason]`;

    adjustmentDetail
        .querySelectorAll(".column")[4]
        .querySelector("textarea").value = "";

    adjustmentDetailsWrapper.appendChild(adjustmentDetail);

    attachListenersToRemoveDetailButton();
}

function addTransferDetail() {
    let transferDetailsWrapper = d.getElementById("transfer-details");

    let transferDetails = d.getElementsByClassName("transfer-detail");

    let totalTransferDetails = transferDetails.length;

    let transferDetail = transferDetails[0].cloneNode(true);

    transferDetail.setAttribute("x-data", "productDataProvider");

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    originalSelect.setAttribute("x-init", "select2");

    originalSelect.removeAttribute("onchange");

    transferDetail.querySelector("[name=item-number]").innerText = `Item ${
        totalTransferDetails + 1
    }`;

    transferDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute("for", `transfer[${totalTransferDetails}][product_id]`);

    transferDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1].innerHTML = "";

    transferDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1]
        .appendChild(originalSelect);

    transferDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].id = `transfer[${totalTransferDetails}][product_id]`;

    transferDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].name = `transfer[${totalTransferDetails}][product_id]`;

    transferDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll("select")[1].classList = "select2-products";

    transferDetail
        .querySelectorAll(".column")[0]
        .querySelector(".control > select")
        .remove();

    transferDetail
        .querySelectorAll(".column")[1]
        .querySelector("label")
        .setAttribute("for", `transfer[${totalTransferDetails}][quantity]`);

    transferDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "input"
        ).id = `transfer[${totalTransferDetails}][quantity]`;

    transferDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "input"
        ).name = `transfer[${totalTransferDetails}][quantity]`;

    transferDetail.querySelectorAll(".column")[1].querySelector("input").value =
        "";

    transferDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "button"
        ).id = `transfer[${totalTransferDetails}][product_id]Quantity`;

    transferDetail
        .querySelectorAll(".column")[1]
        .querySelector("button").innerText = "";

    transferDetail
        .querySelectorAll(".column")[2]
        .querySelector("label")
        .setAttribute("for", `transfer[${totalTransferDetails}][description]`);

    transferDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "textarea"
        ).id = `transfer[${totalTransferDetails}][description]`;

    transferDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "textarea"
        ).name = `transfer[${totalTransferDetails}][description]`;

    transferDetail
        .querySelectorAll(".column")[2]
        .querySelector("textarea").value = "";

    transferDetailsWrapper.appendChild(transferDetail);

    attachListenersToRemoveDetailButton();
}

function addReturnDetail() {
    let returnDetailsWrapper = d.getElementById("return-details");

    let returnDetails = d.getElementsByClassName("return-detail");

    let totalReturnDetails = returnDetails.length;

    let returnDetail = returnDetails[0].cloneNode(true);

    returnDetail.setAttribute("x-data", "productDataProvider");

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    originalSelect.setAttribute("x-init", "select2");

    originalSelect.removeAttribute("onchange");

    returnDetail.querySelector("[name=item-number]").innerText = `Item ${
        totalReturnDetails + 1
    }`;

    returnDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute("for", `return[${totalReturnDetails}][product_id]`);

    returnDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1].innerHTML = "";

    returnDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1]
        .appendChild(originalSelect);

    returnDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].id = `return[${totalReturnDetails}][product_id]`;

    returnDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].name = `return[${totalReturnDetails}][product_id]`;

    returnDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll("select")[1].classList = "select2-products";

    returnDetail
        .querySelectorAll(".column")[0]
        .querySelector(".control > select")
        .remove();

    returnDetail
        .querySelectorAll(".column")[1]
        .querySelector("label")
        .setAttribute("for", `return[${totalReturnDetails}][warehouse_id]`);

    returnDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "select"
        ).id = `return[${totalReturnDetails}][warehouse_id]`;

    returnDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "select"
        ).name = `return[${totalReturnDetails}][warehouse_id]`;

    returnDetail
        .querySelectorAll(".column")[2]
        .querySelector("label")
        .setAttribute("for", `return[${totalReturnDetails}][quantity]`);

    returnDetail
        .querySelectorAll(".column")[2]
        .querySelector("input").id = `return[${totalReturnDetails}][quantity]`;

    returnDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "input"
        ).name = `return[${totalReturnDetails}][quantity]`;

    returnDetail.querySelectorAll(".column")[2].querySelector("input").value =
        "";

    returnDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "button"
        ).id = `return[${totalReturnDetails}][product_id]Quantity`;

    returnDetail
        .querySelectorAll(".column")[2]
        .querySelector("button").innerText = "";

    returnDetail
        .querySelectorAll(".column")[3]
        .querySelector("label")
        .setAttribute("for", `return[${totalReturnDetails}][unit_price]`);

    returnDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "input"
        ).id = `return[${totalReturnDetails}][unit_price]`;

    returnDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "input"
        ).name = `return[${totalReturnDetails}][unit_price]`;

    returnDetail.querySelectorAll(".column")[3].querySelector("input").value =
        "";

    returnDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "button"
        ).id = `return[${totalReturnDetails}][product_id]Price`;

    returnDetail
        .querySelectorAll(".column")[3]
        .querySelector("button").innerText = "";

    returnDetail
        .querySelectorAll(".column")[4]
        .querySelector("label")
        .setAttribute("for", `return[${totalReturnDetails}][description]`);

    returnDetail
        .querySelectorAll(".column")[4]
        .querySelector(
            "textarea"
        ).id = `return[${totalReturnDetails}][description]`;

    returnDetail
        .querySelectorAll(".column")[4]
        .querySelector(
            "textarea"
        ).name = `return[${totalReturnDetails}][description]`;

    returnDetail
        .querySelectorAll(".column")[4]
        .querySelector("textarea").value = "";

    returnDetailsWrapper.appendChild(returnDetail);

    attachListenersToRemoveDetailButton();
}

function addPurchaseDetail() {
    let purchaseDetailsWrapper = d.getElementById("purchase-details");

    let purchaseDetails = d.getElementsByClassName("purchase-detail");

    let totalPurchaseDetails = purchaseDetails.length;

    let purchaseDetail = purchaseDetails[0].cloneNode(true);

    purchaseDetail.setAttribute("x-data", "productDataProvider");

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    originalSelect.setAttribute("x-init", "select2");

    originalSelect.removeAttribute("onchange");

    purchaseDetail.querySelector("[name=item-number]").innerText = `Item ${
        totalPurchaseDetails + 1
    }`;

    purchaseDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute("for", `purchase[${totalPurchaseDetails}][product_id]`);

    purchaseDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1].innerHTML = "";

    purchaseDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1]
        .appendChild(originalSelect);

    purchaseDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].id = `purchase[${totalPurchaseDetails}][product_id]`;

    purchaseDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].name = `purchase[${totalPurchaseDetails}][product_id]`;

    purchaseDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll("select")[1].classList = "select2-products";

    purchaseDetail
        .querySelectorAll(".column")[0]
        .querySelector(".control > select")
        .remove();

    purchaseDetail
        .querySelectorAll(".column")[1]
        .querySelector("label")
        .setAttribute("for", `purchase[${totalPurchaseDetails}][quantity]`);

    purchaseDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "input"
        ).id = `purchase[${totalPurchaseDetails}][quantity]`;

    purchaseDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "input"
        ).name = `purchase[${totalPurchaseDetails}][quantity]`;

    purchaseDetail.querySelectorAll(".column")[1].querySelector("input").value =
        "";

    purchaseDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "button"
        ).id = `purchase[${totalPurchaseDetails}][product_id]Quantity`;

    purchaseDetail
        .querySelectorAll(".column")[1]
        .querySelector("button").innerText = "";

    purchaseDetail
        .querySelectorAll(".column")[2]
        .querySelector("label")
        .setAttribute("for", `purchase[${totalPurchaseDetails}][unit_price]`);

    purchaseDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "input"
        ).id = `purchase[${totalPurchaseDetails}][unit_price]`;

    purchaseDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "input"
        ).name = `purchase[${totalPurchaseDetails}][unit_price]`;

    purchaseDetail.querySelectorAll(".column")[2].querySelector("input").value =
        "";

    purchaseDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "button"
        ).id = `purchase[${totalPurchaseDetails}][product_id]Price`;

    purchaseDetail
        .querySelectorAll(".column")[2]
        .querySelector("button").innerText = "";

    purchaseDetail
        .querySelectorAll(".column")[3]
        .querySelector("label")
        .setAttribute("for", `purchase[${totalPurchaseDetails}][discount]`);

    purchaseDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "input"
        ).id = `purchase[${totalPurchaseDetails}][discount]`;

    purchaseDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "input"
        ).name = `purchase[${totalPurchaseDetails}][discount]`;

    purchaseDetail.querySelectorAll(".column")[3].querySelector("input").value =
        "";

    purchaseDetailsWrapper.appendChild(purchaseDetail);

    attachListenersToRemoveDetailButton();
}

function addSaleDetail() {
    let saleDetailsWrapper = d.getElementById("sale-details");

    let saleDetails = d.getElementsByClassName("sale-detail");

    let totalSaleDetails = saleDetails.length;

    let saleDetail = saleDetails[0].cloneNode(true);

    saleDetail.setAttribute("x-data", "productDataProvider");

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    originalSelect.setAttribute("x-init", "select2");

    originalSelect.removeAttribute("onchange");

    saleDetail.querySelector("[name=item-number]").innerText = `Item ${
        totalSaleDetails + 1
    }`;

    saleDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute("for", `sale[${totalSaleDetails}][product_id]`);

    saleDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1].innerHTML = "";

    saleDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1]
        .appendChild(originalSelect);

    saleDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].id = `sale[${totalSaleDetails}][product_id]`;

    saleDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].name = `sale[${totalSaleDetails}][product_id]`;

    saleDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll("select")[1].classList = "select2-products";

    saleDetail
        .querySelectorAll(".column")[0]
        .querySelector(".control > select")
        .remove();

    saleDetail
        .querySelectorAll(".column")[1]
        .querySelector("label")
        .setAttribute("for", `sale[${totalSaleDetails}][quantity]`);

    saleDetail
        .querySelectorAll(".column")[1]
        .querySelector("input").id = `sale[${totalSaleDetails}][quantity]`;

    saleDetail
        .querySelectorAll(".column")[1]
        .querySelector("input").name = `sale[${totalSaleDetails}][quantity]`;

    saleDetail.querySelectorAll(".column")[1].querySelector("input").value = "";

    saleDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "button"
        ).id = `sale[${totalSaleDetails}][product_id]Quantity`;

    saleDetail
        .querySelectorAll(".column")[1]
        .querySelector("button").innerText = "";

    saleDetail
        .querySelectorAll(".column")[2]
        .querySelector("label")
        .setAttribute("for", `sale[${totalSaleDetails}][unit_price]`);

    saleDetail
        .querySelectorAll(".column")[2]
        .querySelector("input").id = `sale[${totalSaleDetails}][unit_price]`;

    saleDetail
        .querySelectorAll(".column")[2]
        .querySelector("input").name = `sale[${totalSaleDetails}][unit_price]`;

    saleDetail.querySelectorAll(".column")[2].querySelector("input").value = "";

    saleDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "button"
        ).id = `sale[${totalSaleDetails}][product_id]Price`;

    saleDetail
        .querySelectorAll(".column")[2]
        .querySelector("button").innerText = "";

    saleDetail
        .querySelectorAll(".column")[3]
        .querySelector("label")
        .setAttribute("for", `sale[${totalSaleDetails}][discount]`);

    saleDetail
        .querySelectorAll(".column")[3]
        .querySelector("input").id = `sale[${totalSaleDetails}][discount]`;

    saleDetail
        .querySelectorAll(".column")[3]
        .querySelector("input").name = `sale[${totalSaleDetails}][discount]`;

    saleDetail.querySelectorAll(".column")[3].querySelector("input").value = "";

    saleDetailsWrapper.appendChild(saleDetail);

    attachListenersToRemoveDetailButton();
}

function addTenderDetail() {
    let tenderDetailsWrapper = d.getElementById("tender-details");

    let tenderDetails = d.getElementsByClassName("tender-detail");

    let totalTenderDetails = tenderDetails.length;

    let tenderDetail = tenderDetails[0].cloneNode(true);

    tenderDetail.setAttribute("x-data", "productDataProvider");

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    originalSelect.setAttribute("x-init", "select2");

    originalSelect.removeAttribute("onchange");

    tenderDetail.querySelector("[name=item-number]").innerText = `Item ${
        totalTenderDetails + 1
    }`;

    tenderDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute("for", `tender[${totalTenderDetails}][product_id]`);

    tenderDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1].innerHTML = "";

    tenderDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1]
        .appendChild(originalSelect);

    tenderDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].id = `tender[${totalTenderDetails}][product_id]`;

    tenderDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].name = `tender[${totalTenderDetails}][product_id]`;

    tenderDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll("select")[1].classList = "select2-products";

    tenderDetail
        .querySelectorAll(".column")[0]
        .querySelector(".control > select")
        .remove();

    tenderDetail
        .querySelectorAll(".column")[1]
        .querySelector("label")
        .setAttribute("for", `tender[${totalTenderDetails}][quantity]`);

    tenderDetail
        .querySelectorAll(".column")[1]
        .querySelector("input").id = `tender[${totalTenderDetails}][quantity]`;

    tenderDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "input"
        ).name = `tender[${totalTenderDetails}][quantity]`;

    tenderDetail.querySelectorAll(".column")[1].querySelector("input").value =
        "";

    tenderDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "button"
        ).id = `tender[${totalTenderDetails}][product_id]Quantity`;

    tenderDetail
        .querySelectorAll(".column")[1]
        .querySelector("button").innerText = "";

    tenderDetail
        .querySelectorAll(".column")[2]
        .querySelector("label")
        .setAttribute("for", `tender[${totalTenderDetails}][description]`);

    tenderDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "textarea"
        ).id = `tender[${totalTenderDetails}][description]`;

    tenderDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "textarea"
        ).name = `tender[${totalTenderDetails}][description]`;

    tenderDetail
        .querySelectorAll(".column")[2]
        .querySelector("textarea").value = "";

    tenderDetailsWrapper.appendChild(tenderDetail);

    attachListenersToRemoveDetailButton();
}

function addDamageDetail() {
    let damageDetailsWrapper = d.getElementById("damage-details");

    let damageDetails = d.getElementsByClassName("damage-detail");

    let totalDamageDetails = damageDetails.length;

    let damageDetail = damageDetails[0].cloneNode(true);

    damageDetail.setAttribute("x-data", "productDataProvider");

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    originalSelect.setAttribute("x-init", "select2");

    originalSelect.removeAttribute("onchange");

    damageDetail.querySelector("[name=item-number]").innerText = `Item ${
        totalDamageDetails + 1
    }`;

    damageDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute("for", `damage[${totalDamageDetails}][product_id]`);

    damageDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1].innerHTML = "";

    damageDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1]
        .appendChild(originalSelect);

    damageDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].id = `damage[${totalDamageDetails}][product_id]`;

    damageDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].name = `damage[${totalDamageDetails}][product_id]`;

    damageDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll("select")[1].classList = "select2-products";

    damageDetail
        .querySelectorAll(".column")[0]
        .querySelector(".control > select")
        .remove();

    damageDetail
        .querySelectorAll(".column")[1]
        .querySelector("label")
        .setAttribute("for", `damage[${totalDamageDetails}][warehouse_id]`);

    damageDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "select"
        ).id = `damage[${totalDamageDetails}][warehouse_id]`;

    damageDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "select"
        ).name = `damage[${totalDamageDetails}][warehouse_id]`;

    damageDetail
        .querySelectorAll(".column")[2]
        .querySelector("label")
        .setAttribute("for", `damage[${totalDamageDetails}][quantity]`);

    damageDetail
        .querySelectorAll(".column")[2]
        .querySelector("input").id = `damage[${totalDamageDetails}][quantity]`;

    damageDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "input"
        ).name = `damage[${totalDamageDetails}][quantity]`;

    damageDetail.querySelectorAll(".column")[2].querySelector("input").value =
        "";

    damageDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "button"
        ).id = `damage[${totalDamageDetails}][product_id]Quantity`;

    damageDetail
        .querySelectorAll(".column")[2]
        .querySelector("button").innerText = "";

    damageDetail
        .querySelectorAll(".column")[3]
        .querySelector("label")
        .setAttribute("for", `damage[${totalDamageDetails}][description]`);

    damageDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "textarea"
        ).id = `damage[${totalDamageDetails}][description]`;

    damageDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "textarea"
        ).name = `damage[${totalDamageDetails}][description]`;

    damageDetail
        .querySelectorAll(".column")[3]
        .querySelector("textarea").value = "";

    damageDetailsWrapper.appendChild(damageDetail);

    attachListenersToRemoveDetailButton();
}

function addSivDetail() {
    let sivDetailsWrapper = d.getElementById("siv-details");

    let sivDetails = d.getElementsByClassName("siv-detail");

    let totalSivDetails = sivDetails.length;

    let sivDetail = sivDetails[0].cloneNode(true);

    sivDetail.setAttribute("x-data", "productDataProvider");

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    originalSelect.setAttribute("x-init", "select2");

    originalSelect.removeAttribute("onchange");

    sivDetail.querySelector("[name=item-number]").innerText = `Item ${
        totalSivDetails + 1
    }`;

    sivDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute("for", `siv[${totalSivDetails}][product_id]`);

    sivDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1].innerHTML = "";

    sivDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1]
        .appendChild(originalSelect);

    sivDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].id = `siv[${totalSivDetails}][product_id]`;

    sivDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].name = `siv[${totalSivDetails}][product_id]`;

    sivDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll("select")[1].classList = "select2-products";

    sivDetail
        .querySelectorAll(".column")[0]
        .querySelector(".control > select")
        .remove();

    sivDetail
        .querySelectorAll(".column")[1]
        .querySelector("label")
        .setAttribute("for", `siv[${totalSivDetails}][warehouse_id]`);

    sivDetail
        .querySelectorAll(".column")[1]
        .querySelector("select").id = `siv[${totalSivDetails}][warehouse_id]`;

    sivDetail
        .querySelectorAll(".column")[1]
        .querySelector("select").name = `siv[${totalSivDetails}][warehouse_id]`;

    sivDetail
        .querySelectorAll(".column")[2]
        .querySelector("label")
        .setAttribute("for", `siv[${totalSivDetails}][quantity]`);

    sivDetail
        .querySelectorAll(".column")[2]
        .querySelector("input").id = `siv[${totalSivDetails}][quantity]`;

    sivDetail
        .querySelectorAll(".column")[2]
        .querySelector("input").name = `siv[${totalSivDetails}][quantity]`;

    sivDetail.querySelectorAll(".column")[2].querySelector("input").value = "";

    sivDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "button"
        ).id = `siv[${totalSivDetails}][product_id]Quantity`;

    sivDetail.querySelectorAll(".column")[2].querySelector("button").innerText =
        "";

    sivDetail
        .querySelectorAll(".column")[3]
        .querySelector("label")
        .setAttribute("for", `siv[${totalSivDetails}][description]`);

    sivDetail
        .querySelectorAll(".column")[3]
        .querySelector("textarea").id = `siv[${totalSivDetails}][description]`;

    sivDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "textarea"
        ).name = `siv[${totalSivDetails}][description]`;

    sivDetail.querySelectorAll(".column")[3].querySelector("textarea").value =
        "";

    sivDetailsWrapper.appendChild(sivDetail);

    attachListenersToRemoveDetailButton();
}

function addGrnDetail() {
    let grnDetailsWrapper = d.getElementById("grn-details");

    let grnDetails = d.getElementsByClassName("grn-detail");

    let totalGrnDetails = grnDetails.length;

    let grnDetail = grnDetails[0].cloneNode(true);

    grnDetail.setAttribute("x-data", "productDataProvider");

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    originalSelect.setAttribute("x-init", "select2");

    originalSelect.removeAttribute("onchange");

    grnDetail.querySelector("[name=item-number]").innerText = `Item ${
        totalGrnDetails + 1
    }`;

    grnDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute("for", `grn[${totalGrnDetails}][product_id]`);

    grnDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1].innerHTML = "";

    grnDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(".select")[1]
        .appendChild(originalSelect);

    grnDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].id = `grn[${totalGrnDetails}][product_id]`;

    grnDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll(
            "select"
        )[1].name = `grn[${totalGrnDetails}][product_id]`;

    grnDetail
        .querySelectorAll(".column")[0]
        .querySelectorAll("select")[1].classList = "select2-products";

    grnDetail
        .querySelectorAll(".column")[0]
        .querySelector(".control > select")
        .remove();

    grnDetail
        .querySelectorAll(".column")[1]
        .querySelector("label")
        .setAttribute("for", `grn[${totalGrnDetails}][warehouse_id]`);

    grnDetail
        .querySelectorAll(".column")[1]
        .querySelector("select").id = `grn[${totalGrnDetails}][warehouse_id]`;

    grnDetail
        .querySelectorAll(".column")[1]
        .querySelector("select").name = `grn[${totalGrnDetails}][warehouse_id]`;

    grnDetail
        .querySelectorAll(".column")[2]
        .querySelector("label")
        .setAttribute("for", `grn[${totalGrnDetails}][quantity]`);

    grnDetail
        .querySelectorAll(".column")[2]
        .querySelector("input").id = `grn[${totalGrnDetails}][quantity]`;

    grnDetail
        .querySelectorAll(".column")[2]
        .querySelector("input").name = `grn[${totalGrnDetails}][quantity]`;

    grnDetail.querySelectorAll(".column")[2].querySelector("input").value = "";

    grnDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "button"
        ).id = `grn[${totalGrnDetails}][product_id]Quantity`;

    grnDetail.querySelectorAll(".column")[2].querySelector("button").innerText =
        "";

    grnDetail
        .querySelectorAll(".column")[3]
        .querySelector("label")
        .setAttribute("for", `grn[${totalGrnDetails}][description]`);

    grnDetail
        .querySelectorAll(".column")[3]
        .querySelector("textarea").id = `grn[${totalGrnDetails}][description]`;

    grnDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "textarea"
        ).name = `grn[${totalGrnDetails}][description]`;

    grnDetail.querySelectorAll(".column")[3].querySelector("textarea").value =
        "";

    grnDetailsWrapper.appendChild(grnDetail);

    attachListenersToRemoveDetailButton();
}

function rearrangeDetailItemNumber() {
    let itemNumberElements = document.getElementsByName("item-number");

    itemNumberElements.forEach((element, index) => {
        element.innerText = `Item ${index + 1}`;
    });
}

function removeDetail() {
    if (document.getElementsByName("remove-detail-button").length <= 1) {
        return;
    }

    let detailElement = this.parentElement.parentElement.parentElement;

    if (
        detailElement.isEqualNode(detailElement.parentElement.firstElementChild)
    ) {
        return;
    }

    detailElement.remove();

    rearrangeDetailItemNumber();
}

function attachListenersToRemoveDetailButton() {
    document.getElementsByName("remove-detail-button").forEach((element) => {
        element.addEventListener("click", removeDetail);
    });
}
