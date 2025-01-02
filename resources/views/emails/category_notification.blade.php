<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Notification</title>
</head>
<body>
    <h1>Category Notification</h1>

    <p>The category <strong>{{ $category->name }}</strong> has been <strong>{{ $action }}</strong>.</p>

    <p>Description: {{ $category->description }}</p>
    <p>Status: {{ $category->status ? 'Active' : 'Inactive' }}</p>

    @if($category->image)
        <p><img src="{{ asset('storage/' . $category->image) }}" alt="Category Image" /></p>
    @else
        <p>No image available for this category.</p>
    @endif
</body>
</html>
