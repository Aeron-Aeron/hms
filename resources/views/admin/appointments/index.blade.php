<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appointment Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date & Time
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Patient
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Doctor
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($appointments as $appointment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $appointment->scheduled_time->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $appointment->patient->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            Dr. {{ $appointment->doctor->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <form action="{{ route('admin.appointments.updateStatus', $appointment) }}"
                                                  method="POST"
                                                  class="inline-flex">
                                                @csrf
                                                @method('PUT')
                                                <select name="status"
                                                        onchange="this.form.submit()"
                                                        class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <option value="pending" {{ $appointment->status === 'pending' ? 'selected' : '' }}>
                                                        Pending
                                                    </option>
                                                    <option value="accepted" {{ $appointment->status === 'accepted' ? 'selected' : '' }}>
                                                        Accepted
                                                    </option>
                                                    <option value="completed" {{ $appointment->status === 'completed' ? 'selected' : '' }}>
                                                        Completed
                                                    </option>
                                                    <option value="cancelled" {{ $appointment->status === 'cancelled' ? 'selected' : '' }}>
                                                        Cancelled
                                                    </option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm
</file>
