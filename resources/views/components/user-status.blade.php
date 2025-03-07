<li class="nav-item mx-3">
    <div class="btn-group d-flex flex-column">
        <button type="button" class="btn btn-outline-secondary dropdown-toggle status-button text-truncate"
            data-bs-toggle="dropdown" aria-expanded="false" style="max-width: 100%;">
            <span class="status-icon-container"></span>
            Current Status
        </button>
        <ul class="dropdown-menu status-dropdown">
            @foreach ($statuses as $status)
                @if ($status->title == 'Custom Status')
                    <li>
                        <a href="javascript:void(0)" class="dropdown-item d-flex align-items-center toggle-custom-status"
                            data-status="{{ $userStatusValue }}" data-status-id="{{ $status->id }}">
                            <i class="fa-solid fa-pen status-icon me-2"></i> Set Custom Status
                        </a>
                        <div class="dropdown-item custom-status-input" style="display: none;">
                            <input type="text" class="form-control form-control-sm"
                                placeholder="Enter custom status">
                            <button class="btn btn-primary btn-sm mt-1 p-1 save-custom-status">Save</button>
                        </div>
                    </li>
                @else
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="#"
                            data-status="{{ $status->title }}" data-status-id="{{ $status->id }}">
                            <i class="fa-solid {{ $status->icon }} status-icon me-2"></i>
                            {{ $status->title }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</li>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const userStatusID = @json($userStatusID); // Get the last status ID passed from Blade

        // Function to update the status button with the correct status
        function setCurrentStatus(statusTitle, statusIcon) {
            const statusButton = document.querySelector(".status-button");
            statusButton.innerHTML = `<span class="status-icon-container">${statusIcon}</span> ${statusTitle}`;
        }

        // Set the initial status when the page loads
        if (userStatusID) {
            const statusItem = document.querySelector(
                `.status-dropdown .dropdown-item[data-status-id="${userStatusID}"]`);
            if (statusItem) {
                const statusTitle = statusItem.dataset.status;
                const statusIcon = statusItem.querySelector(".status-icon")?.outerHTML ||
                    '<i class="fa-solid fa-pen status-icon me-2"></i>';
                setCurrentStatus(statusTitle, statusIcon);
            } else {
                console.log("Status item not found for userStatusID:", userStatusID);
            }
        }

        // Handle clicking on status items (both predefined and custom)
        document.querySelectorAll(".status-dropdown .dropdown-item").forEach(function(item) {
            item.addEventListener("click", function(event) {
                event.preventDefault();

                const statusTitle = this.dataset.status;
                const statusIcon = this.querySelector(".status-icon")?.outerHTML || "";

                if (statusTitle) {
                    setCurrentStatus(statusTitle, statusIcon);
                    updateStatusOnServer(statusTitle, this.dataset.statusId);
                }
            });
        });

        // Prevent dropdown from closing when clicking "Set Custom Status"
        document.querySelector(".toggle-custom-status").addEventListener("click", function(event) {
            event.stopPropagation(); // Prevent the dropdown from closing
            const customStatusInput = document.querySelector(".custom-status-input");
            customStatusInput.style.display =
                customStatusInput.style.display === "none" ? "block" : "none";
        });

        // Handle custom status input
        document.querySelector(".save-custom-status").addEventListener("click", function(event) {
            event.stopPropagation(); // Prevent the dropdown from closing
            const customStatusInput = document.querySelector(".custom-status-input input");
            const customStatus = customStatusInput.value.trim();

            if (!customStatus) {
                alert("Please enter a custom status.");
                return;
            }

            setCurrentStatus(customStatus, '<i class="fa-solid fa-edit status-icon me-2"></i>');
            customStatusInput.value = "";
            document.querySelector(".custom-status-input").style.display = "none";

            updateStatusOnServer(customStatus, 9); // ID 9 for custom status
        });

        // Function to update status on the server
        function updateStatusOnServer(status, statusId = null) {
            const status_time = document.getElementById("totalTimeDisplay");
            fetch('{{ route('users.status') }}', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                            "content"),
                    },
                    body: JSON.stringify({
                        status: status,
                        status_id: statusId,
                        status_time: status_time.value,
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    console.log(data.message); // Show success message
                })
                .catch((error) => {
                    console.error("Error updating status:", error);
                });
        }
    });
</script>
