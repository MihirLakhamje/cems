<x-layout>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <x-slot:title>Registered Events | CEMS</x-slot:title>
    <x-slot:metaDescription>View and manage all departments</x-slot:metaDescription>

    <x-slot:header>Registered Events</x-slot:header>

    <section>
        <div class="container">
            <h1 class="mb-4">My Registered Events</h1>

            @if ($registrations->isEmpty())
                <p>You have not registered for any events yet.</p>
            @else
                <div class="row">
                    @foreach ($registrations as $reg)
                        @php
                            $event = $reg->event;
                        @endphp
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                @if ($event->image)
                                    <img src="{{ asset('storage/' . $event->image) }}" class="card-img-top"
                                        alt="{{ $event->name }}">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $event->name }}</h5>
                                    <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                                    <p><strong>Start:</strong> {{ $event->start_date }}</p>
                                    <p><strong>End:</strong> {{ $event->end_date }}</p>
                                    <p><strong>Location:</strong> {{ $event->location }}</p>
                                    <p><strong>Fees:</strong> ₹{{ $event->fees }}</p>
                                    <p><strong>Capacity:</strong> {{ $event->capacity }}</p>
                                    <p><strong>Status:</strong> {{ ucfirst($reg->status) }}</p>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('events.show', $event) }}"
                                        class="btn btn-primary btn-sm">View</a>
                                    <form action="{{ route('registrations.destroy', $event) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Cancel</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-layout>
