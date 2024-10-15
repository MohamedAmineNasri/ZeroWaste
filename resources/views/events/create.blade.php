@extends('layouts/contentNavbarLayout')  

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="w-100">
            <h1>Create New Event</h1>

            <form action="{{ route('events.store') }}" method="POST"enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="event_name">Event Name:</label>
                    <input type="text" name="event_name" id="event_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" class="form-control" required></textarea>
                </div>

                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" name="location" id="location" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="datetime-local" name="start_date" id="start_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="datetime-local" name="end_date" id="end_date" class="form-control" required>
                </div>
                 <!-- New field for image upload -->
                 <div class="form-group">
                    <label for="event_image">Event Image:</label>
                    <input type="file" name="event_image" id="event_image" class="form-control" accept="image/*">
                </div>
<br>
                <button type="submit" class="btn btn-primary">Create Event</button>
                <a href="{{ route('events.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
@endsection