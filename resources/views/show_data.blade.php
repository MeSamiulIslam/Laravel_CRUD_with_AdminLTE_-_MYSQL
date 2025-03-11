@extends('master')

@section('title')
    Application List
@endsection

@section('header-resources')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.0.8/css/responsive.dataTables.min.css" rel="stylesheet">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><b>Application List</b></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('cv') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add New
                                    </a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Display success and error messages -->
            <section class="content">
                <div class="container-fluid">
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ Session::get('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                </div>
            </section>

            <!-- Application List -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="myTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">Sl. no.</th>
                                        <th style="width: 10%;">Applicant Photo</th>
                                        <th style="width: 20%;">Name</th>
                                        <th style="width: 25%;">Email</th>
                                        <th style="width: 15%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->sortByDesc('id') as $personal)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                @if ($personal->profile_photo)
                                                    <img src="{{ asset('storage/profile_photos/' . $personal->profile_photo) }}"
                                                         alt="Profile Photo" class="rounded" width="60" height="60">
                                                @else
                                                    <img src="{{ asset('storage/default_profile_photo.jpg') }}"
                                                         alt="Applicant Photo" class="rounded-square" width="60" height="60">
                                                @endif
                                            </td>


                                            <td>{{ $personal->first_name . ' ' . $personal->last_name }}</td>
                                            <td>{{ $personal->email }}</td>
                                            <td>
                                                @if ($personal->status == -1)
                                                    <!-- Show Edit button if CV is a draft -->
                                                    <a href="{{ route('edit_cv', ['id' => $personal->id]) }}"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                @else
                                                    <!-- Show View and PDF buttons if CV is submitted -->
                                                    <a href="{{ route('show_all', ['id' => $personal->id]) }}"
                                                        class="btn btn-success">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="{{ route('pdf.view', ['id' => $personal->id]) }}"
                                                        class="btn btn-info">
                                                        <i class="fas fa-file-pdf"></i> PDF View
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('footer-resources')
    <!-- jQuery, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.0.8/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            // DataTables with asc order by the first column (Sl. no.)
            $('#myTable').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [
                    [0, 'asc']
                ],
                columnDefs: [{
                        targets: 1,
                        orderable: false
                    },
                    {
                        targets: 4,
                        orderable: false
                    } // Disable sorting on 'Action' column
                ],
                language: {
                    search: "Search Applications:",
                    lengthMenu: "Show _MENU_ applications per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ applications",
                    paginate: {
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
        });
    </script>
@endsection
