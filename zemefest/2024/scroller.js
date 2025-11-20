var scrollerInterval;
function refreshScroller() {
    if (scrollerInterval) {
        clearInterval(scrollerInterval)
    }
    const viewport = document.getElementById('viewport');
    const content = document.getElementById('list-group');
    const scrollSpeed = 1; // Speed of movement in pixels per interval
    const scrollInterval = 50; // Interval in milliseconds

    // Function to move the content upwards
    const simulateScroll = () => {
        const contentHeight = content.clientHeight; // Total height of the content
        const viewportHeight = viewport.clientHeight; // Height of the visible area

        if (content.offsetTop <= -contentHeight) {
            // Reset to the top when it moves entirely out of view
            content.style.top = viewportHeight + 'px';
        }

        // Move the content upwards
        content.style.top = ((parseInt(content.style.top) || 0) - scrollSpeed) + 'px';
    };

    // Start the simulated scrolling with the specified interval
    scrollerInterval = setInterval(simulateScroll, scrollInterval);
}
