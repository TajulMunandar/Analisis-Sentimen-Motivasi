@extends('dashboard.layout.main')
@section('content')
    <div class="row">
        <div class="col-12">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif (session()->has('failed'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('failed') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>

    <button class="btn btn-primary fs-5 fw-normal mt-2" data-bs-toggle="modal" data-bs-target="#tambahUser"><i
            class="fa-solid fa-square-plus fs-5 me-2"></i>Tambah</button>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card mt-2">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="myTable" class="table w-100 table-borderless table-striped border-top border-bottom">
                            <thead class="align-middle border-bottom">
                                <tr>
                                    <th>NO</th>
                                    <th>NAMA</th>
                                    <th>USERNAME</th>
                                    <th>ROLE</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>
                                            @if ($user->role == 1)
                                                <span class="badge bg-success">Admin</span>
                                            @else
                                                <span class="badge bg-success">Users</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editUser{{ $loop->iteration }}">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#hapusUser{{ $loop->iteration }}">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#modalResetPassword{{ $loop->iteration }}">
                                                <i class="fa-regular fa-unlock-keyhole"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Modal Edit User --}}
                                    <x-modal>
                                        @slot('id', "editUser$loop->iteration")
                                        @slot('title', 'Edit Data User')
                                        @slot('route', route('users.update', $user->id))
                                        @slot('method') @method('put') @endslot
                                        @slot('btnPrimaryTitle', 'Perbarui')

                                        <div class="mb-3">
                                            <label for="name-edit" class="form-label">Nama</label>
                                            <input type="name" class="form-control @error('name') is-invalid @enderror"
                                                id="name-edit" name="name" value="{{ old('name', $user->name) }}"
                                                autofocus required>
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="username-edit" class="form-label">Username</label>
                                            <input type="text"
                                                class="form-control @error('username') is-invalid @enderror"
                                                id="username-edit" name="username"
                                                value="{{ old('username', $user->username) }}" autofocus required>
                                            @error('username')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </x-modal>
                                    {{-- / Modal Edit User --}}

                                    {{-- Modal Hapus User --}}
                                    <x-modal>
                                        @slot('id', "hapusUser$loop->iteration")
                                        @slot('title', 'Hapus Data User')
                                        @slot('route', route('users.destroy', $user->id))
                                        @slot('method') @method('delete') @endslot
                                        @slot('btnPrimaryClass', 'btn-outline-danger')
                                        @slot('btnSecondaryClass', 'btn-secondary')
                                        @slot('btnPrimaryTitle', 'Hapus')

                                        <p class="fs-5">Apakah anda yakin akan menghapus data user
                                            <b>{{ $user->name }}</b>?
                                        </p>

                                    </x-modal>
                                    {{-- / Modal Hapus User  --}}

                                    {{-- Modal Reset Password Admin --}}
                                    <x-modal>
                                        @slot('id', "modalResetPassword$loop->iteration")
                                        @slot('title', 'Ganti Password')
                                        @slot('route', route('resetpassword.resetPasswordAdmin'))
                                        @slot('method') @method('post') @endslot

                                        @csrf
                                        <div class="row">
                                            <input type="hidden" value="{{ $user->id }}" name="id">
                                            <div class="mb-3">
                                                <label for="password-change" class="form-label text-dark">Password
                                                    Baru</label>
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password-change" name="password" autofocus required>
                                                @error('password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="password-change" class="form-label text-dark">Konfirmasi
                                                    Password
                                                    Baru</label>
                                                <input type="password"
                                                    class="form-control @error('password2') is-invalid @enderror"
                                                    id="password-change2" name="password2" autofocus required>
                                                @error('password2')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </x-modal>
                                    {{-- / Modal Reset Password Admin --}}
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- / Tabel Data ... --}}
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Tambah User -->
    <x-modal>
        @slot('id', 'tambahUser')
        @slot('title', 'Tambah Data User')
        @slot('overflow', 'overflow-auto')
        @slot('route', route('users.store'))

        @csrf
        <div class="row">
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="name" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    autofocus required>
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                    name="username" autofocus required>
                @error('username')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" autofocus required>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role">
                    <option value="1" selected>Admin</option>
                    <option value="2">User</option>
                </select>
            </div>
        </div>
    </x-modal>
    <!-- Akhir Modal Tambah User -->
@endsection


@section('script')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
@endsection
