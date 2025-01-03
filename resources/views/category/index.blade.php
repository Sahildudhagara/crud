@extends('category.layout')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="alert-container"></div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Categories List</h4>
                    <div>
                        <span class="btn btn-primary">{{ $userEmail }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createCategoryModal">Add Category</button>
                    </div>
                </div>

                <div class="card-body">
                    <!-- <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search Categories..." /> -->
                    <table class="table table-striped table-bordered"id="categoriesTable" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>  <!-- Ensure the header matches the data fields -->
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>

              
            </div>
        </div>
    </div>
</div>



<!-- Modal for Category Creation -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryLabel">Create Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" />
                    </div>

                    <div class="mb-3">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image">Image:</label>
                        <input type="file" name="image" id="image" class="form-control">
                        <div id="imagePreviewContainer">
                            <img id="imagePreview" src="" width="100" alt="No Image Selected">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <input type="checkbox" name="status" value="1" style="width:30px;height:30px;" />
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Category Detail -->
<div class="modal fade" id="categoryDetailModal" tabindex="-1" aria-labelledby="categoryDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryDetailModalLabel">Category Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Name</label>
                    <p id="categoryDetailName"></p>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <p id="categoryDetailDescription"></p>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <p id="categoryDetailStatus"></p>
                </div>

                <div class="mb-3">
                    <label>Image</label>
                    <br/>
                    <img id="categoryDetailImage" src="" width="200" alt="Category Image">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Edit Category -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('category.update', '') }}" method="POST" enctype="multipart/form-data" id="editCategoryForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" id="editCategoryName" required />
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <input type="text" name="description" class="form-control" id="editCategoryDescription" required />
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image">Image:</label>
                        <input type="file" name="image" class="form-control" id="editCategoryImage" onchange="previewImage(event, true)">
                        <div id="editImagePreviewContainer">
                            <img id="editImagePreview" src="" width="100" alt="No Image Selected">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <input type="checkbox" name="status" id="editCategoryStatus" style="width:30px;height:30px;" />
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
  $(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let table = $('#categoriesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("category.index") }}',
            method: 'GET',
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'description' },
            { data: 'status' },
            { data: 'image', render: function(data) {
                return data ? <img src="${data}" width="100"> : 'No Image';
            }},
            { data: 'action_buttons' }
        ]
    });

    // Listen for search input and update the DataTable
    $('#searchInput').on('keyup', function() {
        table.draw();
    });

    // Show Category
    $(document).on('click', '.show-category', function() {
        let categoryId = $(this).data('id');
        $.get('/category/' + categoryId, function(data) {
            $('#categoryDetailName').text(data.name);
            $('#categoryDetailDescription').text(data.description);
            $('#categoryDetailStatus').text(data.status == 1 ? 'Visible' : 'Hidden');
            $('#categoryDetailImage').attr('src', data.image ? '/storage/' + data.image : '/default-image.jpg');
            $('#categoryDetailModal').modal('show');
        });
    });

    // Edit Category
    $(document).on('click', '.edit-category', function() {
        let categoryId = $(this).data('id');
        $.get('/category/' + categoryId + '/edit', function(data) {
            $('#editCategoryName').val(data.name);
            $('#editCategoryDescription').val(data.description);
            $('#editCategoryStatus').prop('checked', data.status == 1);
            $('#editImagePreview').attr('src', data.image ? '/storage/' + data.image : '/default-image.jpg');
            $('#editCategoryForm').attr('action', '/category/' + categoryId); // Correct the action URL
            $('#editCategoryModal').modal('show');
        });
    });

    // Handle Edit Category via AJAX
    $('#editCategoryForm').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        let categoryId = $(this).attr('action').split('/').pop();

        $.ajax({
            url: '/category/' + categoryId,
            method: 'post',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response.status);
                $('#editCategoryModal').modal('hide');
                table.ajax.reload();
            },
            error: function(response) {
                alert('There was an error updating the category.');
            }
        });
    });

    // Delete Category
    $(document).on('click', '.delete-category', function() {
        let categoryId = $(this).data('id');
        if (confirm("Are you sure?")) {
            $.ajax({
                url: '/category/' + categoryId,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                },
                success: function(response) {
                    alert(response.status);
                    table.ajax.reload();
                }
            });
        }
    });

    // Image preview function for create and edit modals
    function previewImage(event, isEdit = false) {
        const imagePreviewContainer = isEdit ? document.getElementById('editImagePreviewContainer') : document.getElementById('imagePreviewContainer');
        const imagePreview = isEdit ? document.getElementById('editImagePreview') : document.getElementById('imagePreview');

        if (event.target.files && event.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.width = 100; // Set the width to 100px
            }
            reader.readAsDataURL(event.target.files[0]);
        } else {
            imagePreview.src = ''; // Clear the preview if no file is selected
        }
    }

    // Event listener for the create modal image input
    $('#image').on('change', function(event) {
        previewImage(event, false);
    });

    // Event listener for the edit modal image input
    $('#editCategoryImage').on('change', function(event) {
        previewImage(event, true);
    });

    // Handle Create Category via AJAX
    $('#categoryForm').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: '{{ route("category.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response.status);
                $('#createCategoryModal').modal('hide');
                table.ajax.reload();

                // Clear the form fields after successful submission
                $('#categoryForm')[0].reset(); // Reset form
                $('#imagePreview').attr('src', ''); // Clear image preview
            },
            error: function(response) {
                alert('There was an error creating the category.');
            }
        });
    });
});

</script>
@endsection