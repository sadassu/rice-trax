/**
 * Prevents triggering row-level click when clicking on
 * buttons, links, or elements acting like buttons.
 *
 * @param {MouseEvent} event - the click event
 * @returns {boolean} - true if click should proceed, false if ignored
 */
export function isSafeRowClick(event) {
    const target = event.target;
    return !(
        target.closest("button") ||
        target.closest("a") ||
        target.closest("[role='button']")
    );
}
