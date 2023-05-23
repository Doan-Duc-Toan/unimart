@extends('layout.admin')
@section('content')
    <div  id="content" class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success">
                <span class="text-success">{{ session('status') }}</span>
            </div>
        @endif
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Chỉnh sửa quyền
                    </div>
                    <div class="card-body">
                        <form action="{{ route('permission.update',$permission->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên quyền</label>
                                <input value="{{$permission->name}}" class="form-control" type="text" name="name"
                                    id="name">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="slug">slug</label>
                                <input value="{{$permission->slug}}" class="form-control" type="text" name="slug"
                                    id="slug">
                                @error('slug')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea name="description" id="description" cols="20" rows="10" class="form-control">{{$permission->description}}</textarea>
                                @error('description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" value="Chỉnh sửa" name="btd_update" class="btn btn-primary">Chỉnh sửa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
