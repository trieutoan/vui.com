@extends('admin.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">User
                <small>Create</small>
            </h1>
        </div>
        <!-- /.col-lg-12 -->
        <div class="col-lg-7" style="padding-bottom:120px">
            @include('admin.block.errors')
            <form action="{{ route('admin.user.store') }}" method="POST">
            {{ csrf_field() }}
                <div class="form-group">
                    <label>Username</label>
                    <input class="form-control" name="txtUser" placeholder="Please Enter Username" value="{{ old('txtUser') }}" />
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" name="txtPass" placeholder="Please Enter Password" />
                </div>
                <div class="form-group">
                    <label>RePassword</label>
                    <input type="password" class="form-control" name="txtRePass" placeholder="Please Enter RePassword" />
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" name="txtEmail" placeholder="Please Enter Email" value="{{ old('txtEmail') }}" />
                </div>
                <div class="form-group">
                    <label>User Level</label>
                    <label class="radio-inline">
                        <input name="rdoLevel" value="1" checked="" type="radio">Admin
                    </label>
                    <label class="radio-inline">
                        <input name="rdoLevel" value="0" type="radio">Member
                    </label>
                </div>
                <button type="submit" class="btn btn-default">User Create</button>
            </form>
        </div>
    </div>
    <!-- /.row -->
</div>
@endsection