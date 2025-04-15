// Make the call UI draggable
function makeElementDraggable(elmnt) {
    let pos1 = 0,
        pos2 = 0,
        pos3 = 0,
        pos4 = 0;

    // Initialize drag on mouse down
    elmnt.onmousedown = dragMouseDown;

    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();

        // Get the current mouse position when the user starts dragging
        pos3 = e.clientX;
        pos4 = e.clientY;

        // Set up the mousemove and mouseup events
        document.onmouseup = closeDragElement;
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();

        // Calculate how far the mouse has moved
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;

        // Move the element by changing its position on the screen
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
        // Remove event listeners when the drag ends
        document.onmouseup = null;
        document.onmousemove = null;
    }
}

window.addEventListener('DOMContentLoaded', () => {
    const draggableBox = document.getElementById("draggableCallUI");
    makeElementDraggable(draggableBox); // Make the call interface draggable
});