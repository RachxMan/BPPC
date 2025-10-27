@extends('layouts.admin')

@section('title', 'Upload Data')
@section('header-title', 'Upload Data')
@section('header-subtitle', 'Manage your data uploads here')

@section('content')
<div class="container">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Upload Form --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5>Upload File</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.upload_data.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">Select File (CSV / Excel)</label>
                    <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" required>
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>

    {{-- List of Uploaded Data --}}
    <div class="card">
        <div class="card-header">
            <h5>Uploaded Data</h5>
        </div>
        <div class="card-body">
            @if($uploads->isEmpty())
                <p>No data uploaded yet.</p>
            @else
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>File Name</th>
                            <th>Uploaded By</th>
                            <th>Uploaded At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($uploads as $index => $upload)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $upload->filename }}</td>
                                <td>{{ $upload->user->name ?? 'N/A' }}</td>
                                <td>{{ $upload->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.upload_data.download', $upload->id) }}" class="btn btn-sm btn-success">Download</a>
                                    <form action="{{ route('admin.upload_data.destroy', $upload->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                {{ $uploads->links() }}
            @endif
        </div>
    </div>

</div>
@endsection
