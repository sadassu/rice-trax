<script setup>
import SideBar from "../../Layouts/SideBar.vue";

defineOptions({
    layout: SideBar,
});

const props = defineProps({
    sale: Object,
});

const downloadReceipt = () => {
    const printWindow = window.open("", "_blank");

    const formatCurrencyForPrint = (amount) => {
        return Number(amount).toLocaleString("en-PH", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    };

    const htmlContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Receipt #${props.sale.id}</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background: white;
                    color: #000;
                    line-height: 1.4;
                }
                .receipt-container {
                    max-width: 600px;
                    margin: 0 auto;
                    background: white;
                    border: 1px solid #000;
                    padding: 20px;
                }
                .receipt-header {
                    text-align: center;
                    border-bottom: 2px solid #000;
                    padding-bottom: 15px;
                    margin-bottom: 20px;
                }
                .receipt-title {
                    font-size: 24px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                .receipt-id {
                    font-size: 18px;
                    font-weight: bold;
                }
                .receipt-info {
                    margin-bottom: 25px;
                    border: 1px solid #000;
                    padding: 15px;
                }
                .info-row {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 8px;
                    padding: 4px 0;
                }
                .info-label {
                    font-weight: bold;
                }
                .info-value {
                    font-weight: bold;
                }
                .items-section h2 {
                    font-size: 18px;
                    font-weight: bold;
                    margin-bottom: 10px;
                    border-bottom: 1px solid #000;
                    padding-bottom: 5px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;
                    border: 1px solid #000;
                }
                th {
                    background: #f0f0f0;
                    border: 1px solid #000;
                    padding: 10px;
                    text-align: left;
                    font-weight: bold;
                }
                td {
                    border: 1px solid #000;
                    padding: 8px;
                }
                tr:nth-child(even) {
                    background: #f9f9f9;
                }
                .receipt-footer {
                    text-align: center;
                    margin-top: 25px;
                    padding-top: 15px;
                    border-top: 1px solid #000;
                    font-size: 14px;
                }
                @media print {
                    body { margin: 0; padding: 10px; }
                }
            </style>
        </head>
        <body>
            <div class="receipt-container">
                <div class="receipt-header">
                    <div class="receipt-title">Sales Receipt</div>
                    <div class="receipt-id">Receipt #${props.sale.id}</div>
                </div>

                <div class="receipt-info">
                    <div class="info-row">
                        <span class="info-label">Date:</span>
                        <span class="info-value">${props.sale.sale_date}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Total Amount:</span>
                        <span class="info-value">₱${formatCurrencyForPrint(
                            props.sale.total_price
                        )}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Amount Paid:</span>
                        <span class="info-value">₱${formatCurrencyForPrint(
                            props.sale.amount_paid
                        )}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Change:</span>
                        <span class="info-value">₱${formatCurrencyForPrint(
                            props.sale.change
                        )}</span>
                    </div>
                </div>

                <div class="items-section">
                    <h2>Items Purchased</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Kg</th>
                                <th>Price/Kg</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${props.sale.sale_details
                                .map(
                                    (detail) => `
                                <tr>
                                    <td>${detail.product.name}</td>
                                    <td>${detail.kilograms}</td>
                                    <td>₱${formatCurrencyForPrint(
                                        detail.price_per_kilo
                                    )}</td>
                                    <td>₱${formatCurrencyForPrint(
                                        detail.total_price
                                    )}</td>
                                </tr>
                            `
                                )
                                .join("")}
                        </tbody>
                    </table>
                </div>

                <div class="receipt-footer">
                    <p>Thank you for your business!</p>
                </div>
            </div>
        </body>
        </html>
    `;

    printWindow.document.write(htmlContent);
    printWindow.document.close();

    // Wait for content to load then trigger print
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
};

// Format date function
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

// Format currency
const formatCurrency = (amount) => {
    return Number(amount).toLocaleString("en-PH", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

function goBack() {
    window.history.back();
}
</script>

<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <!-- Receipt Content (This will be printed) -->
        <div
            id="receipt-content"
            class="max-w-4xl mx-auto bg-white border border-gray-300 shadow-lg"
        >
            <!-- Receipt Header -->
            <div class="border-b-2 border-gray-800 text-center p-6">
                <h1 class="text-3xl font-bold mb-2">Sales Receipt</h1>
                <p class="text-xl font-semibold">Receipt #{{ sale.id }}</p>
            </div>

            <!-- Receipt Body -->
            <div class="p-8">
                <!-- Sale Information -->
                <div class="border border-gray-300 p-6 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-200"
                            >
                                <span class="font-semibold">Date & Time:</span>
                                <span class="font-bold">{{
                                    formatDate(sale.sale_date)
                                }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="font-semibold">Total Amount:</span>
                                <span class="font-bold text-lg"
                                    >₱{{
                                        formatCurrency(sale.total_price)
                                    }}</span
                                >
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-200"
                            >
                                <span class="font-semibold">Amount Paid:</span>
                                <span class="font-bold"
                                    >₱{{
                                        formatCurrency(sale.amount_paid)
                                    }}</span
                                >
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="font-semibold">Change:</span>
                                <span class="font-bold"
                                    >₱{{ formatCurrency(sale.change) }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Section -->
                <div class="items-section">
                    <h2
                        class="text-xl font-bold mb-4 pb-2 border-b border-gray-800"
                    >
                        Items Purchased
                    </h2>

                    <div class="overflow-x-auto">
                        <table
                            class="w-full border-collapse border border-gray-800"
                        >
                            <thead>
                                <tr class="bg-gray-100">
                                    <th
                                        class="border border-gray-800 px-4 py-3 text-left font-bold"
                                    >
                                        Product Name
                                    </th>
                                    <th
                                        class="border border-gray-800 px-4 py-3 text-center font-bold"
                                    >
                                        Weight (Kg)
                                    </th>
                                    <th
                                        class="border border-gray-800 px-4 py-3 text-center font-bold"
                                    >
                                        Price/Kg
                                    </th>
                                    <th
                                        class="border border-gray-800 px-4 py-3 text-right font-bold"
                                    >
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(detail, index) in sale.sale_details"
                                    :key="detail.id"
                                    :class="
                                        index % 2 === 0
                                            ? 'bg-white'
                                            : 'bg-gray-50'
                                    "
                                >
                                    <td
                                        class="border border-gray-800 px-4 py-3 font-medium"
                                    >
                                        {{ detail.product.name }}
                                    </td>
                                    <td
                                        class="border border-gray-800 px-4 py-3 text-center"
                                    >
                                        {{ detail.kilograms }}
                                    </td>
                                    <td
                                        class="border border-gray-800 px-4 py-3 text-center"
                                    >
                                        ₱{{
                                            formatCurrency(
                                                detail.price_per_kilo
                                            )
                                        }}
                                    </td>
                                    <td
                                        class="border border-gray-800 px-4 py-3 text-right font-semibold"
                                    >
                                        ₱{{
                                            formatCurrency(detail.total_price)
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Receipt Footer -->
                <div class="mt-8 pt-6 border-t border-gray-800 text-center">
                    <p class="text-sm font-medium">
                        Thank you for your business!
                    </p>
                    <p class="text-xs mt-2">
                        This receipt serves as proof of purchase.
                    </p>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-center gap-2 mt-4">
            <button
                @click="downloadReceipt"
                class="w-64 bg-gray-800 hover:bg-gray-900 p-3 text-white font-semibold rounded border shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    ></path>
                </svg>
                Download Receipt
            </button>
            <button
                class="rounded-xl w-64 justify-center cursor-pointer p-3 bg-blue-700 text-white"
                @click="goBack"
            >
                Back
            </button>
        </div>
    </div>
</template>
