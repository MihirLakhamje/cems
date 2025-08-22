<x-layout>
    <x-slot:title>{{ $event->name }} | CEMS</x-slot:title>
    <x-slot:metaDescription>View event details</x-slot:metaDescription>

    <x-slot:header>Event Details</x-slot:header>

    <div class="flex space-x-2 items-center mb-4">
        <x-link :typeoflink="'link'" href="{{ route('events.index') }}" class="text-blue-600 dark:text-blue-500">
            Back
        </x-link>
        <span class="text-gray-800 dark:text-white">|</span>
        <form action="{{ route('events.destroy', $event->id) }}" method="post">
            @csrf
            @method('DELETE')
            <x-link :typeoflink="'button'" onclick="return confirm('Are you sure? This action cannot be undone.')"
                class="text-red-600 dark:text-red-500">
                Delete
            </x-link>
        </form>
    </div>

    <section class=" rounded-lg dark:bg-gray-800 flex flex-col justify-between flex-wrap gap-10 ">
        <div class="container">
            <div class="card mb-4">
                @if ($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" class="card-img-top" alt="{{ $event->name }}">
                @endif

                <div class="card-body">
                    <h1 class="card-title">{{ $event->name }}</h1>
                    <p class="card-text">{{ $event->description }}</p>

                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item"><strong>Start Date:</strong> {{ $event->start_date }}</li>
                        <li class="list-group-item"><strong>End Date:</strong> {{ $event->end_date }}</li>
                        <li class="list-group-item"><strong>Location:</strong> {{ $event->location }}</li>
                        <li class="list-group-item"><strong>Fees:</strong> ₹{{ $event->fees }}</li>
                        <li class="list-group-item"><strong>Capacity:</strong> {{ $event->capacity }}</li>
                    </ul>

                    {{-- Registration logic --}}
                    @auth
                        @if ($event->users->contains(auth()->id()))
                            <form action="{{ route('registrations.destroy', $event) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Cancel Registration</button>
                            </form>
                        @else
                            <form action="{{ route('registrations.store', $event) }}" method="POST">
                                @csrf
                                <button class="btn btn-success">Register</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </section>
</x-layout>
