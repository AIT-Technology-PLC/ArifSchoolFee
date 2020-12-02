d.onreadystatechange = hideMainMenuScroller;

d.getElementById("menuLeft").addEventListener(
    "mouseout",
    hideMainMenuScrollerOnMouseOut
);

d.getElementById("menuLeft").addEventListener(
    "mouseover",
    showMainMenuScrollerOnMouseOver
);

if (d.getElementById("addNewForm")) {
    d.getElementById("addNewForm").addEventListener(
        "click",
        addKeyValueInputFields
    );
}

d.getElementById("createMenuButton").addEventListener(
    "click",
    toggleCreateMenu
);

d.getElementById("createMenu").addEventListener("mouseleave", toggleCreateMenu);

if (d.getElementById("addNewPurchaseForm")) {
    d.getElementById("addNewPurchaseForm").addEventListener(
        "click",
        addPurchaseForm
    );
}

d.addEventListener("readystatechange", jumpToCurrentPageMenuTitle);
