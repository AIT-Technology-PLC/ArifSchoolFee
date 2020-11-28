d.onreadystatechange = hideMainMenuScroller;

d.getElementById("menuLeft").addEventListener(
    "mouseout",
    hideMainMenuScrollerOnMouseOut
);

d.getElementById("menuLeft").addEventListener(
    "mouseover",
    showMainMenuScrollerOnMouseOver
);

d.getElementById("addNewForm").addEventListener(
    "click",
    addKeyValueInputFields
);

d.getElementById("createMenuButton").addEventListener(
    "click",
    toggleCreateMenu
);
