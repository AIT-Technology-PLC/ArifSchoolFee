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
