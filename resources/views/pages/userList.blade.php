@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Sky User</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <ul class="list-group">
                    @if($users->count() == 0)
                    <div class="d-flex justify-content-center align-items-center flex-wrap mt-5">
                        <div class="text-center">
                            <h5 class="mt-n5">Belum ada User</h5>
                        </div>
                    </div>
                    @else
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        UserName</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        User Information</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Status</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Date Join</th>
                                    <th class="text-secondary opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $u)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="{{'public/uploads/profile/' . $u->profile_picture}}"
                                                    class="avatar avatar-sm me-3" alt="user1">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{$u->name}}</h6>
                                                <p class="text-xs text-secondary mb-0">{{$u->email .' | '.
                                                    $u->user_dating_id}}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{$u->gender}}</p>
                                        <p class="text-xs text-secondary mb-0">{{$u->phone}}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if($u->banned == 'no')
                                        <span class="badge badge-sm bg-gradient-success">Active</span>
                                        @else
                                        <span class="badge badge-sm bg-gradient-danger">Banned</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <span
                                            class="text-secondary text-xs font-weight-bold">{{date_format($u->created_at,
                                            'd M Y')}}</span>
                                    </td>
                                    <td class="align-middle">
                                        @if($u->banned == 'no')

                                        <a href="{{route('banned',$u->id)}}" class="btn btn-danger btn-sm mt-2"
                                            data-toggle="tooltip" data-original-title="Edit user">
                                            Banned
                                        </a>
                                        @else
                                        <a href="{{route('activate',$u->id)}}" class="btn btn-warning btn-sm mt-2"
                                            data-toggle="tooltip" data-original-title="Edit user">
                                            UnBanned
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    @endif
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection