@extends('layout.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card-header font-weight-bold">
                Chỉnh sửa thông tin
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin_user.update',$user->id) }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input class="form-control" type="text" name="name" id="name" value="{{$user->name}}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" type="text" name="email" id="email" value="{{$user->email}}" disabled="disabled">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Nhóm quyền</label>
                        <select multiple class="form-control" name="role_id[]" id="">
                            <option>Chọn quyền</option>
                            @foreach ($roles as $role)
                                <option @php
                                    if (in_array($role->id,$user->roles->pluck('id')->toArray()))
                                    echo 'selected'
                                @endphp value="{{$role->id}}">{{ucfirst($role->name)}}</option>
                            @endforeach
                        </select>
                    </div>

                    <button value="Cập nhật" type="submit" name="btn_update" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
