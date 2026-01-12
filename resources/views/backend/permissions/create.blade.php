@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Permission</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Create Permission</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="container">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('backend.admin.permissions.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label>Permission Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        required>
                                </div>

                                <button class="btn btn-success">Create</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
