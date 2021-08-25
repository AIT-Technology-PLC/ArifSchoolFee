function addProformaInvoiceDetail() {
    let proformaInvoiceDetailsWrapper = d.getElementById(
        "proforma-invoice-details"
    );

    let proformaInvoiceDetails = d.getElementsByClassName(
        "proforma-invoice-detail"
    );

    let totalProformaInvoiceDetails = proformaInvoiceDetails.length;

    let proformaInvoiceDetail = proformaInvoiceDetails[0].cloneNode(true);

    let originalSelect = d.getElementById("original-select").cloneNode(true);

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
        .querySelector(".select").innerHTML = "";

    proformaInvoiceDetail
        .querySelectorAll(".column")[0]
        .querySelector(".select")
        .appendChild(originalSelect);

    proformaInvoiceDetail
        .querySelectorAll(".column")[0]
        .querySelector(
            "select"
        ).id = `proformaInvoice[${totalProformaInvoiceDetails}][product_id]`;

    proformaInvoiceDetail
        .querySelectorAll(".column")[0]
        .querySelector(
            "select"
        ).name = `proformaInvoice[${totalProformaInvoiceDetails}][product_id]`;

    proformaInvoiceDetail
        .querySelectorAll(".column")[0]
        .querySelector("select").classList = "select2-products";

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

    initializeSelect2Products();

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

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    gdnDetail.querySelector("[name=item-number]").innerText = `Item ${
        totalGdnDetails + 1
    }`;

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute("for", `gdn[${totalGdnDetails}][product_id]`);

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelector(".select").innerHTML = "";

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelector(".select")
        .appendChild(originalSelect);

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelector("select").id = `gdn[${totalGdnDetails}][product_id]`;

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelector("select").name = `gdn[${totalGdnDetails}][product_id]`;

    gdnDetail.querySelectorAll(".column")[0].querySelector("select").classList =
        "select2-products";

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

    initializeSelect2Products();
}

function addReservationDetail() {
    let reservationDetailsWrapper = d.getElementById("reservation-details");

    let reservationDetails = d.getElementsByClassName("reservation-detail");

    let totalReservationDetails = reservationDetails.length;

    let reservationDetail = reservationDetails[0].cloneNode(true);

    let originalSelect = d.getElementById("original-select").cloneNode(true);

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
        .querySelector(".select").innerHTML = "";

    reservationDetail
        .querySelectorAll(".column")[0]
        .querySelector(".select")
        .appendChild(originalSelect);

    reservationDetail
        .querySelectorAll(".column")[0]
        .querySelector(
            "select"
        ).id = `reservation[${totalReservationDetails}][product_id]`;

    reservationDetail
        .querySelectorAll(".column")[0]
        .querySelector(
            "select"
        ).name = `reservation[${totalReservationDetails}][product_id]`;

    reservationDetail
        .querySelectorAll(".column")[0]
        .querySelector("select").classList = "select2-products";

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

    initializeSelect2Products();
}

function addPurchaseOrderDetail() {
    let purchaseOrderDetailsWrapper = d.getElementById(
        "purchase-order-details"
    );

    let purchaseOrderDetails = d.getElementsByClassName(
        "purchase-order-detail"
    );

    let totalPurchaseOrderDetails = purchaseOrderDetails.length;

    let purchaseOrderDetail = purchaseOrderDetails[0].cloneNode(true);

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    purchaseOrderDetail.querySelector("[name=item-number]").innerText = `Item ${
        totalPurchaseOrderDetails + 1
    }`;

    purchaseOrderDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute(
            "for",
            `purchaseOrder[${totalPurchaseOrderDetails}][product_id]`
        );

    purchaseOrderDetail
        .querySelectorAll(".column")[0]
        .querySelector(".select").innerHTML = "";

    purchaseOrderDetail
        .querySelectorAll(".column")[0]
        .querySelector(".select")
        .appendChild(originalSelect);

    purchaseOrderDetail
        .querySelectorAll(".column")[0]
        .querySelector(
            "select"
        ).id = `purchaseOrder[${totalPurchaseOrderDetails}][product_id]`;

    purchaseOrderDetail
        .querySelectorAll(".column")[0]
        .querySelector(
            "select"
        ).name = `purchaseOrder[${totalPurchaseOrderDetails}][product_id]`;

    purchaseOrderDetail
        .querySelectorAll(".column")[0]
        .querySelector("select").classList = "select2-products";

    purchaseOrderDetail
        .querySelectorAll(".column")[0]
        .querySelector(".control > select")
        .remove();

    purchaseOrderDetail
        .querySelectorAll(".column")[1]
        .querySelector("label")
        .setAttribute(
            "for",
            `purchaseOrder[${totalPurchaseOrderDetails}][quantity]`
        );

    purchaseOrderDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "input"
        ).id = `purchaseOrder[${totalPurchaseOrderDetails}][quantity]`;

    purchaseOrderDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "input"
        ).name = `purchaseOrder[${totalPurchaseOrderDetails}][quantity]`;

    purchaseOrderDetail
        .querySelectorAll(".column")[1]
        .querySelector("input").value = "";

    purchaseOrderDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "button"
        ).id = `purchaseOrder[${totalPurchaseOrderDetails}][product_id]Quantity`;

    purchaseOrderDetail
        .querySelectorAll(".column")[1]
        .querySelector("button").innerText = "";

    purchaseOrderDetail
        .querySelectorAll(".column")[2]
        .querySelector("label")
        .setAttribute(
            "for",
            `purchaseOrder[${totalPurchaseOrderDetails}][unit_price]`
        );

    purchaseOrderDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "input"
        ).id = `purchaseOrder[${totalPurchaseOrderDetails}][unit_price]`;

    purchaseOrderDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "input"
        ).name = `purchaseOrder[${totalPurchaseOrderDetails}][unit_price]`;

    purchaseOrderDetail
        .querySelectorAll(".column")[2]
        .querySelector("input").value = "";

    purchaseOrderDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "button"
        ).id = `purchaseOrder[${totalPurchaseOrderDetails}][product_id]Price`;

    purchaseOrderDetail
        .querySelectorAll(".column")[2]
        .querySelector("button").innerText = "";

    purchaseOrderDetail
        .querySelectorAll(".column")[3]
        .querySelector("label")
        .setAttribute(
            "for",
            `purchaseOrder[${totalPurchaseOrderDetails}][description]`
        );

    purchaseOrderDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "textarea"
        ).id = `purchaseOrder[${totalPurchaseOrderDetails}][description]`;

    purchaseOrderDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "textarea"
        ).name = `purchaseOrder[${totalPurchaseOrderDetails}][description]`;

    purchaseOrderDetail
        .querySelectorAll(".column")[3]
        .querySelector("textarea").value = "";

    purchaseOrderDetailsWrapper.appendChild(purchaseOrderDetail);

    initializeSelect2Products();
}

function addAdjustmentDetail() {
    let adjustmentDetailsWrapper = d.getElementById("adjustment-details");

    let adjustmentDetails = d.getElementsByClassName("adjustment-detail");

    let totalAdjustmentDetails = adjustmentDetails.length;

    let adjustmentDetail = adjustmentDetails[0].cloneNode(true);

    let originalSelect = d.getElementById("original-select").cloneNode(true);

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
        .querySelector(".select").innerHTML = "";

    adjustmentDetail
        .querySelectorAll(".column")[0]
        .querySelector(".select")
        .appendChild(originalSelect);

    adjustmentDetail
        .querySelectorAll(".column")[0]
        .querySelector(
            "select"
        ).id = `adjustment[${totalAdjustmentDetails}][product_id]`;

    adjustmentDetail
        .querySelectorAll(".column")[0]
        .querySelector(
            "select"
        ).name = `adjustment[${totalAdjustmentDetails}][product_id]`;

    adjustmentDetail
        .querySelectorAll(".column")[0]
        .querySelector("select").classList = "select2-products";

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

    initializeSelect2Products();
}
