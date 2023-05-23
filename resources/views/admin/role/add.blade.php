@extends('layout.admin')
@section('content')
    {{-- {{dd($permissions)}} --}}
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Thêm mới vai trò</h5>
                <div class="form-search form-inline">
                    <form action="#">
                        <input type="" class="form-control form-search" placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('role.store') }}" enctype="multipart/form-data">
                    @csrf
                    {{-- {!! Form::open(['route'=>'role.store'])!!} --}}
                    <div class="form-group">
                        <label class="text-strong" for="name">Tên vai trò</label>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        {{-- {!! Form::label('name', 'Tên vai trò') !!} --}}
                        <input class="form-control" type="text" name="name" id="name"
                            value="{{ old('name') }}">

                        {{-- {!! Form::text('name', , [$options]) !!} --}}
                    </div>
                    <div class="form-group">
                        <label class="text-strong" for="description">Mô tả</label>
                        <textarea class="form-control" type="text" name="description" id="description">{{ old('description') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <strong>Vai trò này có quyền gì?</strong>
                    <small class="form-text text-muted pb-2">Check vào module hoặc các hành động bên dưới để chọn
                        quyền.</small>
                    <!-- List Permission  -->
                    @foreach ($permissions as $module => $modulePermissions)
                        <div class="card my-4 border">
                            <div class="card-header">
                                <input type="checkbox" class="check-all" name="" id="{{ $module }}">
                                <label for="{{ $module }}" class="m-0">Module {{ ucfirst($module) }}</label>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($modulePermissions as $permission)
                                        <div class="col-md-3">
                                            <input type="checkbox" class="permission" value="{{ $permission->id }}"
                                                name="permission_id[]" id="{{ $permission->slug }}">
                                            <label for="{{ $permission->slug }}">{{ $permission->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <input type="submit" name="btn-add" class="btn btn-primary" value="Thêm mới">
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('.check-all').click(function() {
            $(this).closest('.card').find('.permission').prop('checked', this.checked)
        })
    </script>
@endsection
