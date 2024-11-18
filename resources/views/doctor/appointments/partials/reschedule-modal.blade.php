<div id="rescheduleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Reschedule Appointment</h3>

            <form id="rescheduleForm" action="" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="new_scheduled_time" class="block text-sm font-medium text-gray-700 mb-2">
                        New Date and Time
                    </label>
                    <input type="datetime-local"
                           id="new_scheduled_time"
                           name="new_scheduled_time"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           required>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button"
                            onclick="closeRescheduleModal()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Reschedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRescheduleModal(appointmentId) {
    const modal = document.getElementById('rescheduleModal');
    const form = document.getElementById('rescheduleForm');

    // Set the form action URL
    form.action = `/doctor/appointments/${appointmentId}/reschedule`;

    // Show the modal
    modal.classList.remove('hidden');
}

function closeRescheduleModal() {
    const modal = document.getElementById('rescheduleModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('rescheduleModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRescheduleModal();
    }
});
</script>
