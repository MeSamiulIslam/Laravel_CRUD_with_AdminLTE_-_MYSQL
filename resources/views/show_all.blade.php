@extends('master')

@section('title')
    View Application
@endsection

@section('header-resources')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.0.8/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="display-6">{{ $data->first_name }}'s Application Information</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">View Application Form</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">
                            <!-- Personal Information Card -->
                            <div class="card shadow mb-4">
                                <div class="card-header">
                                    <h3 class="card-title">Personal Information</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr>
                                                <td class="fw-bold">Profile Photo</td>
                                                <td>
                                                    <img src="{{ asset('storage/profile_photos/' . $data->profile_photo) }}"
                                                        class="img-thumbnail rounded-square" alt="Profile Photo"
                                                        style="max-width: 150px;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Full Name</td>
                                                <td>{{ $data->first_name . ' ' . $data->last_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Father's Name</td>
                                                <td>{{ $data->father_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Mother's Name</td>
                                                <td>{{ $data->mother_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Phone Number</td>
                                                <td>{{ $data->phone_number }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Email</td>
                                                <td>{{ $data->email }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Date of Birth</td>
                                                <td>{{ \Carbon\Carbon::parse($data->dob)->format('d M,Y') }}</td>
                                            </tr>

                                            <tr>
                                                <td class="fw-bold">Present Address</td>
                                                <td>{{ $data->present_address }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Permanent Address</td>
                                                <td>{{ $data->permanent_address }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Nationality</td>
                                                <td>{{ $data->nationality }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Gender</td>
                                                <td>{{ $data->gender }}</td>
                                            </tr>
                                            @if ($data->bid == null)
                                                <tr>
                                                    <td class="fw-bold">Identity</td>
                                                    <td>NID</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">NID Number</td>
                                                    <td>{{ $data->nid }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td class="fw-bold">Identity</td>
                                                    <td>BID</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">BID Number</td>
                                                    <td>{{ $data->bid }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td class="fw-bold">Attachment</td>
                                                <td>
                                                    @if ($data->attachment)
                                                        <a href="{{ asset('storage/' . $data->attachment) }}"
                                                            target="_blank">View Attachment</a>
                                                        <br>
                                                        <iframe src="{{ asset('storage/' . $data->attachment) }}"
                                                            width="100%" height="500px"></iframe>
                                                    @else
                                                        No Attachment Uploaded
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Academic Information Card -->
                            <div class="card shadow mb-4">
                                <div class="card-header">
                                    <h3 class="card-title">Academic Information</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Education Level</th>
                                                <th>Institution</th>
                                                <th>Group/Program</th>
                                                <th>Passing Year</th>
                                                <th>GPA/CGPA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($educations as $education)
                                                <tr>
                                                    <td>{{ $education->education_level }}</td>
                                                    <td>{{ $education->educational_institute_name }}</td>
                                                    <td>{{ $education->department }}</td>
                                                    <td>{{ $education->pass_year }}</td>
                                                    <td>{{ $education->cgpa }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Experience Information Card -->
                            <div class="card shadow mb-4">
                                <div class="card-header">
                                    <h3 class="card-title">Experience</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Company Name</th>
                                                <th>Designation</th>
                                                <th>Location</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Total Experience</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($experiences as $experience)
                                                <tr>
                                                    <td>{{ $experience->company_name }}</td>
                                                    <td>{{ $experience->designation }}</td>
                                                    <td>{{ $experience->company_location }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($experience->start_date)->format('d M,Y') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($experience->end_date)->format('d M,Y') }}
                                                    </td>

                                                    <td>{{ $experience->total_experience }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Training and Certification Information Card -->
                            <div class="card shadow mb-4">
                                <div class="card-header">
                                    <h3 class="card-title">Training & Certification</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Training Title</th>
                                                <th>Institute Name</th>
                                                <th>Duration</th>
                                                <th>Year</th>
                                                <th>Location</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($trainings as $training)
                                                <tr>
                                                    <td>{{ $training->title }}</td>
                                                    <td>{{ $training->institute_name }}</td>
                                                    <td>{{ $training->duration }}</td>
                                                    <td>{{ $training->year }}</td>
                                                    <td>{{ $training->training_location }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Download CV Button -->
                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('pdf.download', ['id' => $data->id]) }}" class="btn btn-success btn-lg">
                                    Download CV
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('footer-resources')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
@endsection
