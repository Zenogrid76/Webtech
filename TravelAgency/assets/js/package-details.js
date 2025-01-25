// Validate inputs for trip details
function validateInputs() {
    const daysInput = document.getElementById("days");
    const peopleInput = document.getElementById("people");
    const maxPeople = packageDetails.maxPeople;

    let days = parseInt(daysInput.value) || 1;
    let people = parseInt(peopleInput.value) || 1;

    if (days < 1) {
        days = 1;
        daysInput.value = days;
    }

    if (people < 1) {
        people = 1;
        peopleInput.value = people;
    } else if (people > maxPeople) {
        people = maxPeople;
        peopleInput.value = people;
    }

    return { days, people };
}

// Real-time cost calculation
function updateTotalPrice() {
    const { perDayCost, basePrice, packageDuration } = packageDetails;
    const totalPriceElement = document.getElementById("total-price");

    const { days, people } = validateInputs();

    const costForDays = (basePrice / packageDuration) * days; // Cost for selected days
    const additionalCost = costForDays * 0.5 * (people - 1); // Additional cost for extra people
    const totalPrice = costForDays + additionalCost; // Total price calculation

    // Update total price display
    totalPriceElement.textContent = totalPrice.toLocaleString("en-BD", {
        style: "currency",
        currency: "BDT",
        minimumFractionDigits: 2,
    });
}

// Review Submission
function submitReview() {
    const reviewText = document.getElementById("review-text");
    const reviewsContainer = document.getElementById("reviews-container");
    const review = reviewText.value.trim();

    if (review === "") {
        alert("Review cannot be empty.");
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../../process/add-review.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            if (xhr.responseText === "ERROR") {
                alert("Failed to submit the review. Please try again.");
            } else {
                // Append the new review to the container
                reviewsContainer.insertAdjacentHTML("afterbegin", xhr.responseText);
                reviewText.value = ""; // Clear the text area
            }
        } else {
            alert("Error submitting review.");
        }
    };

    const params = `package_id=${packageDetails.packageId}&review=${encodeURIComponent(review)}`;
    xhr.send(params);
}

// Booking Submission
// Booking Submission
function bookPackage() {
    const { days, people } = validateInputs();
    const bankAccount = prompt("Enter your Bank Account Number:");
    const pin = prompt("Enter your PIN:");

    if (!bankAccount || !pin) {
        alert("Bank Account or PIN cannot be empty.");
        return;
    }

    const totalPriceElement = document.getElementById("total-price");
    const totalPrice = parseFloat(
        totalPriceElement.textContent.replace(/[^\d.-]/g, "") // Remove currency symbols and format
    );

    const endDate = new Date();
    endDate.setDate(endDate.getDate() + days);

    // Validate payment via AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../../process/validate-payment.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200 && xhr.responseText.trim() === "VALID") {
            // Insert booking into the database
            const bookingXhr = new XMLHttpRequest();
            bookingXhr.open("POST", "../../process/insert-booking.php", true);
            bookingXhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            bookingXhr.onload = function () {
                if (bookingXhr.status === 200) {
                    alert("Booking successful: " + bookingXhr.responseText);
                } else {
                    alert("Booking failed. Please try again.");
                }
            };

            const bookingData = `package_id=${packageDetails.packageId}&days=${days}&people=${people}&total_price=${totalPrice}&start_date=${new Date().toISOString().split("T")[0]}&end_date=${endDate.toISOString().split("T")[0]}`;
            bookingXhr.send(bookingData);
        } else {
            alert("Payment validation failed. Please check your credentials.");
        }
    };

    const paymentData = `bank_account=${encodeURIComponent(bankAccount)}&pin=${encodeURIComponent(pin)}`;
    xhr.send(paymentData);
}

// Attach event listeners when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    const daysInput = document.getElementById("days");
    const peopleInput = document.getElementById("people");

    daysInput.addEventListener("input", updateTotalPrice);
    peopleInput.addEventListener("input", updateTotalPrice);
});