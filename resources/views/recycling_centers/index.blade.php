@extends('layouts/contentNavbarLayout')

@section('content')
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->

  <style>
    /* Custom styles to make map controls smaller */
    .leaflet-control {
      font-size: 2px; /* Adjust control font size */
      padding: 1px;    /* Adjust padding for controls */
      margin-left: -2px;
    }

    .leaflet-control-zoom {
      margin-top: 33px; /* Add space above zoom control */
    }

    /* Hide the attribution control for a cleaner look */
    .leaflet-control-attribution {
      display: none;
    }

    .search-bar-container {
      position: relative;
      margin-bottom: 20px; /* Add space below search bar */
    }

    .search-bar {
      width: 100%;
      padding-left: 40px; /* Add padding for icon */
    }

    .search-icon {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d; /* Bootstrap's text-secondary color */
    }

    .btn-spacing {
      margin-bottom: 10px; /* Add space below buttons */
    }

    .btn-edit {
      background-color: orange; /* Set edit button color to orange */
      color: white; /* Ensure text is readable */
    }
  </style>

  <div class="container">
    <h1>Recycling Centers</h1>

    <!-- Search Bar -->
    <div class="search-bar-container">
      <i class="bx bx-search search-icon"></i> <!-- Search Icon -->
      <input type="text" id="search" class="form-control search-bar" placeholder="Search Recycling Centers" aria-label="Search">
    </div>

    <div class="d-flex justify-content-start">
      <a href="{{ route('recycling-centers.create') }}" class="btn btn-primary btn-spacing">Add Recycling Center</a>
    </div>

    @if ($message = Session::get('success'))
      <div class="alert alert-success">{{ $message }}</div>
    @endif

    <!-- Striped Rows Table -->
    <div class="card">
      <h5 class="card-header">Recycling Centers List</h5>
      <div class="table-responsive text-nowrap">
        <table class="table table-striped" id="recyclingCentersTable">
          <thead>
          <tr>
            <th><i class="bx bx-store"></i> Name</th>
            <th><i class="bx bx-map"></i> Address</th>
            <th><i class="bx bx-phone"></i> Phone</th>
            <th><i class="bx bx-recycle"></i> Waste Category</th>
            <th>Map</th> <!-- Added Map Column -->
            <th>Actions</th>
          </tr>
          </thead>
          <tbody class="table-border-bottom-0">
          @foreach ($recyclingCenters as $center)
            <tr>
              <td>
                <a href="{{ route('recycling-centers.show', $center->id) }}">
                  <i class="bx bx-store"></i> {{ $center->name }}
                </a>
              </td>
              <td>
                <i class="bx bx-map"></i> {{ $center->address }}
              </td>
              <td>
                <i class="bx bx-phone"></i> {{ $center->phone }}
              </td>
              <td>
                <i class="bx bx-recycle"></i> <!-- Waste Category Icon -->
                {{ $center->wasteCategory->name }}
              </td>
              <td>
                <!-- Mini Map -->
                <div id="map-{{ $center->id }}" style="height: 100px; width: 150px;"></div>
                <script>
                  // Initialize the mini map for each recycling center
                  var miniMap{{ $center->id }} = L.map('map-{{ $center->id }}', {
                    zoomControl: true // Show zoom controls
                  }).setView([{{ $center->latitude }}, {{ $center->longitude }}], 14);

                  // Add OpenStreetMap tile layer
                  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                  }).addTo(miniMap{{ $center->id }});

                  // Marker for recycling center
                  L.marker([{{ $center->latitude }}, {{ $center->longitude }}]).addTo(miniMap{{ $center->id }});
                </script>
              </td>
              <td>
                <div class="d-flex flex-column">
                  <a href="{{ route('recycling-centers.edit', $center->id) }}" class="btn rounded-pill btn-edit btn-spacing">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                  </a>
                  <button class="btn rounded-pill btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $center->id }}">
                    <i class="bx bx-trash me-1"></i> Delete
                  </button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="deleteModal{{ $center->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $center->id }}" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel{{ $center->id }}">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        Are you sure you want to delete this recycling center?
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('recycling-centers.destroy', $center->id) }}" method="POST" style="display:inline;">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn rounded-pill btn-danger">
                            <i class="bx bx-trash"></i> Delete
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="card mb-4">
        <h5 class="card-header">Pagination</h5>
        <div class="card-body">
          <div class="row">
            <div class="col text-center">
              <!-- Laravel's built-in pagination links -->
              {{ $recyclingCenters->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
      <!-- End Pagination -->

    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#search').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#recyclingCentersTable tbody tr').filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
      });
    });
  </script>
@endsection
