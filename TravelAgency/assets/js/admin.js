// Notification Logic
document.addEventListener("DOMContentLoaded", () => {
    const notification = document.getElementById("popup-notification");
    if (notification) {
        // Show the notification
        notification.style.display = "block";

        // Hide the notification after 3 seconds
        setTimeout(() => {
            notification.style.display = "none";
        }, 3000);
    }
});
// Function to toggle password visibility
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        button.textContent = "Hide"; // Change button text to 'Hide'
    } else {
        input.type = "password";
        button.textContent = "Show"; // Change button text to 'Show'
    }
}

//Function to add/edit package
document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("#add-edit-package form");
    const formTitle = document.querySelector("#form-title");
    const submitButton = document.querySelector("#submit-button");
    const clearButton = document.querySelector("#clear-button");

    // Clear Form Functionality
    clearButton.addEventListener("click", () => {
        // Reset the form fields
        form.reset();

        // Reset hidden fields
        document.querySelector("#package-id").value = "";
        document.querySelector("#current-image").value = "";

        // Reset form title and button text
        formTitle.textContent = "Add New Package";
        submitButton.textContent = "Add Package";
    });
});

// Function to populate the form with package data for editing
document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("#add-edit-package form");
    const formTitle = document.querySelector("#form-title");
    const submitButton = document.querySelector("#submit-button");
    const clearButton = document.querySelector("#clear-button");

    // Clear Form Functionality
    clearButton.addEventListener("click", () => {
        // Reset the form fields
        form.reset();

        // Reset hidden fields
        document.querySelector("#package-id").value = "";
        document.querySelector("#current-image").value = "";

        // Reset form title and button text
        formTitle.textContent = "Add New Package";
        submitButton.textContent = "Add Package";
    });
});

// Function to populate the form with package data for editing
function editPackage(package) {
    const formTitle = document.querySelector("#form-title");
    const submitButton = document.querySelector("#submit-button");

    // Update form title and button text
    formTitle.textContent = "Edit Package";
    submitButton.textContent = "Update Package";

    // Populate the form fields with package data
    document.querySelector("#package-id").value = package.package_id;
    document.querySelector("#package-name").value = package.name;
    document.querySelector("#description").value = package.description;
    document.querySelector("#price").value = package.price;
    document.querySelector("#duration").value = package.duration_days;
    document.querySelector("#current-image").value = package.package_image;
}
