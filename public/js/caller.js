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
