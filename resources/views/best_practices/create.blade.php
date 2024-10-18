@php
  $isNavbar = false;
  $isMenu = false;
  $navbarHideToggle = false;
@endphp

@extends('layouts/contentNavbarLayout')

@section('content')
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Create Best Practice</h4>

  <div class="row">
    <div class="col-xl">
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">Add New Best Practice</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('back_office.best_practices.store') }}" method="POST" enctype="multipart/form-data"> <!-- Change here -->
            @csrf

            <div class="mb-3">
              <label class="form-label" for="title">Title:</label>
              <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Enter Title" required>
            </div>

            <div class="mb-3">
              <label class="form-label" for="contents">Content:</label>
              <textarea class="form-control" id="contents" name="contents" placeholder="Enter Content" required>{{ old('contents') }}</textarea>
            </div>

            <div class="mb-3">
              <label class="form-label" for="category_id">Category:</label>
              <select class="form-select" id="category_id" name="category_id" required>
                <option value="">Select a Category</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label" for="tags">Tags:</label>
              <input type="text" class="form-control" id="tags" name="tags" value="{{ old('tags') }}" placeholder="Enter Tags (comma separated)">
            </div>

            <div class="mb-3">
              <label class="form-label" for="image">Upload Image:</label>
              <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
