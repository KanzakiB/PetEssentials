
function redirectToProfile() {
    window.location.href = "customer_profile.php";
}

const addAddressModal = document.getElementById('addAddressModal');
const successModal = document.getElementById('successModal');
const newAddressButton = document.getElementById('newAddress');

newAddressButton.addEventListener('click', () => {
    addAddressModal.style.display = 'block';
});

function closeAddAddressModal() {
    addAddressModal.style.display = 'none';
}

if (sessionStorage.getItem('addressAdded') === 'true') {
    successModal.style.display = 'block';

    sessionStorage.removeItem('addressAdded');
}

function closeSuccessModal() {
    successModal.style.display = 'none';
}

const editAddressModal = document.getElementById('editAddressModal');
const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');

function openEditAddressModal(address) {
    document.getElementById('editAddressId').value = address.id;
    document.getElementById('edit_fullname').value = address.fullname;
    document.getElementById('edit_phone_no').value = address.phone_no;
    document.getElementById('edit_House_no').value = address.House_no;
    document.getElementById('edit_street_name').value = address.street_name;
    document.getElementById('edit_Barangay').value = address.Barangay;
    document.getElementById('edit_City').value = address.City;
    document.getElementById('edit_Postal_code').value = address.Postal_code;

    editAddressModal.style.display = 'block';
}

function closeEditAddressModal() {
    editAddressModal.style.display = 'none';
}

function openDeleteModal(addressId) {
    document.getElementById('confirmDeleteBtn').onclick = function () {
        deleteAddress(addressId);
    };
    deleteConfirmationModal.style.display = 'block';
}

function closeDeleteModal() {
    deleteConfirmationModal.style.display = 'none';
}

// Delete Address Function
function deleteAddress(addressId) {
    window.location.href = `delete_address.php?id=${addressId}`;
}

function closeUpdateSuccessModal() {
    document.getElementById("successupdateModal").style.display = "none";
}

function openDeleteConfirmationModal(addressID) {
    document.getElementById("deleteConfirmationModal").style.display = "block";
    
    document.getElementById("confirmDeleteBtn").setAttribute("data-address-id", addressID);
}

function closeDeleteModal() {
    document.getElementById("deleteConfirmationModal").style.display = "none";
}

document.getElementById("confirmDeleteBtn").addEventListener("click", function() {
    var addressID = this.getAttribute("data-address-id");
    

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "delete_address.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status == 200) {
            closeDeleteModal();
            location.reload();  
        } else {
            alert("Error deleting address.");
        }
    };
    xhr.send("addressID=" + addressID);
});

