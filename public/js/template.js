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
