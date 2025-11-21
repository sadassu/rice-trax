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

export const shortNumber = (val) => {
    if (val >= 1_000_000_000) return (val / 1_000_000_000).toFixed(1) + "B";
    if (val >= 1_000_000) return (val / 1_000_000).toFixed(1) + "M";
    if (val >= 1_000) return (val / 1_000).toFixed(1) + "k";
    return val;
};
