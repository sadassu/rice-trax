export const formatSack = (kgRemaining) => {
    if (!kgRemaining) return 0;
    return Math.floor(kgRemaining / 25);
};

// utils/timeFormat.js
export const formatTime = (dateTimeString) => {
    if (!dateTimeString) return "";

    // Replace space with T so JS can parse it
    const date = new Date(dateTimeString.replace(" ", "T"));

    return date.toLocaleTimeString("en-PH", {
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
    });
};
