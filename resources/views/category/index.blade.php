<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jogja Camp Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <h1>Categories</h1>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Button to Open Add Category Modal -->
    <a href="{{route('create')}}" class="btn btn-primary mb-3">
        Add Category
    </a>

    <!-- Search Form -->
    <form method="GET" action="{{ route('index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search categories..." value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Publish Status</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($categories as $index => $category)
            <tr>
                <th scope="row">{{ $index + 1 + ($categories->currentPage() - 1) * $categories->perPage() }}</th>
                <td>{{ htmlspecialchars($category->name) }}</td>
                <td>
                    @if ($category->is_publish)
                        <span class="badge bg-success">Published</span>
                    @else
                        <span class="badge bg-danger">Unpublished</span>
                    @endif
                </td>
                <td>
                    <a href="{{route('edit',$category->id)}}" class="btn btn-warning btn-sm">
                        Edit
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal{{$category->id}}" data-id="{{ $category->id }}">
                        Delete
                    </button>
                </td>
            </tr>

            <!-- Delete Category Modal -->
            <div class="modal fade" id="deleteCategoryModal{{$category->id}}" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteCategoryModalLabel">Delete Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this category?
                            <input type="hidden" id="deleteCategoryId">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <form action="{{ route('destroy', $category->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </tbody>
    </table>

    

    <!-- Pagination Links --> 
    {{ $categories->appends(['search' => request('search')])->links() }}

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>