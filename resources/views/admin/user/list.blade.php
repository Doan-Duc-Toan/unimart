{{-- {{ $user->roles()}} --}}
@extends('layout.admin')
@section('content')

    <div id="content" class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách thành viên</h5>
                <div class="form-search form-inline">
                    <form action="#">
                        <input type="text" value="{{ request()->input('keyword') }}" name="keyword"
                            class="form-control form-search" placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Kích hoạt<span
                            class="text-muted">({{ $count['0'] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}" class="text-primary">Vô hiệu hóa<span
                            class="text-muted">({{ $count['1'] }})</span></a>
                </div>
                <form action="{{ url('admin/user/action') }}" method="POST">
                    @csrf
                    <div class="form-action form-inline py-3">
                        <select name="act" class="form-control mr-1" id="">
                            <option>Chọn</option>
                            @foreach ($list_act as $act => $name)
                                <option value="{{ $act }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkall">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Họ tên</th>
                                <th scope="col">Email</th>
                                <th scope="col">Quyền</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Quyền</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>

                        <tbody>

                            @if (!empty($users->items()))
                                @php
                                    $t = 0;
                                @endphp
                                @foreach ($users as $user)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input name="list_check[]" type="checkbox" value="{{ $user->id }}">
                                        </td>
                                        <td scope="row">{{ $t }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>Admintrator</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>@foreach ($user->roles as $role)
                                            <span class="badge badge-warning">{{$role->name}}</span>
                                        @endforeach</td>
                                        <td>
                                            <a href="{{ route('admin_user.edit', $user->id) }}"
                                                class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                            @if (Auth::id() != $user->id)
                                                <a href="{{ route('admin_user.delete', $user->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fa fa-trash"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa bản ghi này?')"></i></a>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="bg-white">Không tìm thấy bản ghi nào!</td>
                                </tr>
                            @endif
                        </tbody>

                    </table>
                    {{ $users->links() }}
                </form>
            </div>


        </div>
    </div>
@endsection
