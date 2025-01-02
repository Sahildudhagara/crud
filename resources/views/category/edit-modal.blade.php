<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('category.update', 'category_id') }}" method="POST" enctype="multipart/form-data" id="editCategoryForm">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="edit_name">Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="edit_description">Description</label>
                        <textarea name="description" id="edit_description" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status">Status</label>
                        <select name="status" id="edit_status" class="form-control">
                            <option value="1">Visible</option>
                            <option value="0">Hidden</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image">Image</label>
                        <input type="file" name="image" id="edit_image" class="form-control" />
                        <img id="edit_image_preview" src="#" alt="Image Preview" style="max-width: 100px; display: none;" />
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
