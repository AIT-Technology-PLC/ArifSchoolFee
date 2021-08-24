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
    let totalProformaInvoiceDetails = gdnDetails.length;

    let gdnDetail = gdnDetails[0].cloneNode(true);

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    gdnDetail.querySelector("[name=item-number]").innerText = `Item ${
        totalProformaInvoiceDetails + 1
    }`;

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute("for", `gdn[${totalProformaInvoiceDetails}][product_id]`);

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelector(".select").innerHTML = "";

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelector(".select")
        .appendChild(originalSelect);

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelector(
            "select"
        ).id = `gdn[${totalProformaInvoiceDetails}][product_id]`;

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelector(
            "select"
        ).name = `gdn[${totalProformaInvoiceDetails}][product_id]`;

    gdnDetail.querySelectorAll(".column")[0].querySelector("select").classList =
        "select2-products";

    gdnDetail
        .querySelectorAll(".column")[0]
        .querySelector(".control > select")
        .remove();

    gdnDetail
        .querySelectorAll(".column")[1]
        .querySelector("label")
        .setAttribute(
            "for",
            `gdn[${totalProformaInvoiceDetails}][warehouse_id]`
        );

    gdnDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "select"
        ).id = `gdn[${totalProformaInvoiceDetails}][warehouse_id]`;

    gdnDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "select"
        ).name = `gdn[${totalProformaInvoiceDetails}][warehouse_id]`;

    gdnDetail
        .querySelectorAll(".column")[2]
        .querySelector("label")
        .setAttribute("for", `gdn[${totalProformaInvoiceDetails}][quantity]`);

    gdnDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "input"
        ).id = `gdn[${totalProformaInvoiceDetails}][quantity]`;

    gdnDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "input"
        ).name = `gdn[${totalProformaInvoiceDetails}][quantity]`;

    gdnDetail.querySelectorAll(".column")[2].querySelector("input").value = "";

    gdnDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "button"
        ).id = `gdn[${totalProformaInvoiceDetails}][product_id]Quantity`;

    gdnDetail.querySelectorAll(".column")[2].querySelector("button").innerText =
        "";

    gdnDetail
        .querySelectorAll(".column")[3]
        .querySelector("label")
        .setAttribute("for", `gdn[${totalProformaInvoiceDetails}][unit_price]`);

    gdnDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "input"
        ).id = `gdn[${totalProformaInvoiceDetails}][unit_price]`;

    gdnDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "input"
        ).name = `gdn[${totalProformaInvoiceDetails}][unit_price]`;

    gdnDetail.querySelectorAll(".column")[3].querySelector("input").value = "";

    gdnDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "button"
        ).id = `gdn[${totalProformaInvoiceDetails}][product_id]Price`;

    gdnDetail.querySelectorAll(".column")[3].querySelector("button").innerText =
        "";

    gdnDetail
        .querySelectorAll(".column")[4]
        .querySelector("label")
        .setAttribute("for", `gdn[${totalProformaInvoiceDetails}][discount]`);

    gdnDetail
        .querySelectorAll(".column")[4]
        .querySelector(
            "input"
        ).id = `gdn[${totalProformaInvoiceDetails}][discount]`;

    gdnDetail
        .querySelectorAll(".column")[4]
        .querySelector(
            "input"
        ).name = `gdn[${totalProformaInvoiceDetails}][discount]`;

    gdnDetail.querySelectorAll(".column")[4].querySelector("input").value = "";

    gdnDetail
        .querySelectorAll(".column")[5]
        .querySelector("label")
        .setAttribute(
            "for",
            `gdn[${totalProformaInvoiceDetails}][description]`
        );

    gdnDetail
        .querySelectorAll(".column")[5]
        .querySelector(
            "textarea"
        ).id = `gdn[${totalProformaInvoiceDetails}][description]`;

    gdnDetail
        .querySelectorAll(".column")[5]
        .querySelector(
            "textarea"
        ).name = `gdn[${totalProformaInvoiceDetails}][description]`;

    gdnDetail.querySelectorAll(".column")[5].querySelector("textarea").value =
        "";

    gdnDetailsWrapper.appendChild(gdnDetail);

    initializeSelect2Products();
}

function addReservationDetail() {
    let reservationDetailsWrapper = d.getElementById("reservation-details");

    let reservationDetails = d.getElementsByClassName("reservation-detail");
    let totalProformaInvoiceDetails = reservationDetails.length;

    let reservationDetail = reservationDetails[0].cloneNode(true);

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    reservationDetail.querySelector("[name=item-number]").innerText = `Item ${
        totalProformaInvoiceDetails + 1
    }`;

    reservationDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute(
            "for",
            `reservation[${totalProformaInvoiceDetails}][product_id]`
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
        ).id = `reservation[${totalProformaInvoiceDetails}][product_id]`;

    reservationDetail
        .querySelectorAll(".column")[0]
        .querySelector(
            "select"
        ).name = `reservation[${totalProformaInvoiceDetails}][product_id]`;

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
            `reservation[${totalProformaInvoiceDetails}][warehouse_id]`
        );

    reservationDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "select"
        ).id = `reservation[${totalProformaInvoiceDetails}][warehouse_id]`;

    reservationDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "select"
        ).name = `reservation[${totalProformaInvoiceDetails}][warehouse_id]`;

    reservationDetail
        .querySelectorAll(".column")[2]
        .querySelector("label")
        .setAttribute(
            "for",
            `reservation[${totalProformaInvoiceDetails}][quantity]`
        );

    reservationDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "input"
        ).id = `reservation[${totalProformaInvoiceDetails}][quantity]`;

    reservationDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "input"
        ).name = `reservation[${totalProformaInvoiceDetails}][quantity]`;

    reservationDetail
        .querySelectorAll(".column")[2]
        .querySelector("input").value = "";

    reservationDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "button"
        ).id = `reservation[${totalProformaInvoiceDetails}][product_id]Quantity`;

    reservationDetail
        .querySelectorAll(".column")[2]
        .querySelector("button").innerText = "";

    reservationDetail
        .querySelectorAll(".column")[3]
        .querySelector("label")
        .setAttribute(
            "for",
            `reservation[${totalProformaInvoiceDetails}][unit_price]`
        );

    reservationDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "input"
        ).id = `reservation[${totalProformaInvoiceDetails}][unit_price]`;

    reservationDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "input"
        ).name = `reservation[${totalProformaInvoiceDetails}][unit_price]`;

    reservationDetail
        .querySelectorAll(".column")[3]
        .querySelector("input").value = "";

    reservationDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "button"
        ).id = `reservation[${totalProformaInvoiceDetails}][product_id]Price`;

    reservationDetail
        .querySelectorAll(".column")[3]
        .querySelector("button").innerText = "";

    reservationDetail
        .querySelectorAll(".column")[4]
        .querySelector("label")
        .setAttribute(
            "for",
            `reservation[${totalProformaInvoiceDetails}][discount]`
        );

    reservationDetail
        .querySelectorAll(".column")[4]
        .querySelector(
            "input"
        ).id = `reservation[${totalProformaInvoiceDetails}][discount]`;

    reservationDetail
        .querySelectorAll(".column")[4]
        .querySelector(
            "input"
        ).name = `reservation[${totalProformaInvoiceDetails}][discount]`;

    reservationDetail
        .querySelectorAll(".column")[4]
        .querySelector("input").value = "";

    reservationDetail
        .querySelectorAll(".column")[5]
        .querySelector("label")
        .setAttribute(
            "for",
            `reservation[${totalProformaInvoiceDetails}][description]`
        );

    reservationDetail
        .querySelectorAll(".column")[5]
        .querySelector(
            "textarea"
        ).id = `reservation[${totalProformaInvoiceDetails}][description]`;

    reservationDetail
        .querySelectorAll(".column")[5]
        .querySelector(
            "textarea"
        ).name = `reservation[${totalProformaInvoiceDetails}][description]`;

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

    let totalProformaInvoiceDetails = purchaseOrderDetails.length;

    let purchaseOrderDetail = purchaseOrderDetails[0].cloneNode(true);

    let originalSelect = d.getElementById("original-select").cloneNode(true);

    purchaseOrderDetail.querySelector("[name=item-number]").innerText = `Item ${
        totalProformaInvoiceDetails + 1
    }`;

    purchaseOrderDetail
        .querySelectorAll(".column")[0]
        .querySelector("label")
        .setAttribute(
            "for",
            `purchaseOrder[${totalProformaInvoiceDetails}][product_id]`
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
        ).id = `purchaseOrder[${totalProformaInvoiceDetails}][product_id]`;

    purchaseOrderDetail
        .querySelectorAll(".column")[0]
        .querySelector(
            "select"
        ).name = `purchaseOrder[${totalProformaInvoiceDetails}][product_id]`;

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
            `purchaseOrder[${totalProformaInvoiceDetails}][quantity]`
        );

    purchaseOrderDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "input"
        ).id = `purchaseOrder[${totalProformaInvoiceDetails}][quantity]`;

    purchaseOrderDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "input"
        ).name = `purchaseOrder[${totalProformaInvoiceDetails}][quantity]`;

    purchaseOrderDetail
        .querySelectorAll(".column")[1]
        .querySelector("input").value = "";

    purchaseOrderDetail
        .querySelectorAll(".column")[1]
        .querySelector(
            "button"
        ).id = `purchaseOrder[${totalProformaInvoiceDetails}][product_id]Quantity`;

    purchaseOrderDetail
        .querySelectorAll(".column")[1]
        .querySelector("button").innerText = "";

    purchaseOrderDetail
        .querySelectorAll(".column")[2]
        .querySelector("label")
        .setAttribute(
            "for",
            `purchaseOrder[${totalProformaInvoiceDetails}][unit_price]`
        );

    purchaseOrderDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "input"
        ).id = `purchaseOrder[${totalProformaInvoiceDetails}][unit_price]`;

    purchaseOrderDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "input"
        ).name = `purchaseOrder[${totalProformaInvoiceDetails}][unit_price]`;

    purchaseOrderDetail
        .querySelectorAll(".column")[2]
        .querySelector("input").value = "";

    purchaseOrderDetail
        .querySelectorAll(".column")[2]
        .querySelector(
            "button"
        ).id = `purchaseOrder[${totalProformaInvoiceDetails}][product_id]Price`;

    purchaseOrderDetail
        .querySelectorAll(".column")[2]
        .querySelector("button").innerText = "";

    purchaseOrderDetail
        .querySelectorAll(".column")[3]
        .querySelector("label")
        .setAttribute(
            "for",
            `purchaseOrder[${totalProformaInvoiceDetails}][description]`
        );

    purchaseOrderDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "textarea"
        ).id = `purchaseOrder[${totalProformaInvoiceDetails}][description]`;

    purchaseOrderDetail
        .querySelectorAll(".column")[3]
        .querySelector(
            "textarea"
        ).name = `purchaseOrder[${totalProformaInvoiceDetails}][description]`;

    purchaseOrderDetail
        .querySelectorAll(".column")[3]
        .querySelector("textarea").value = "";

    purchaseOrderDetailsWrapper.appendChild(purchaseOrderDetail);

    initializeSelect2Products();
}
