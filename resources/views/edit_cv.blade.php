@extends('master')

@section('title')
    Edit Application
@endsection

@section('header-resources')
    <link rel="stylesheet" href="https://unpkg.com/cropperjs/dist/cropper.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.11/build/css/intlTelInput.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

    <!-- jQuery and DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
@endsection

@section('content')
    <style>
        .form-group {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .btn {
            margin-bottom: 5px;
            margin-right: 5px;
        }

        .breadcrumb {
            padding: 10px 20px;
        }

        input[type="file"] {
            padding: 5px;
            margin: 5px 0;
        }

        .step-content {
            width: 100%;
            margin: 5px;
        }

        .step-content .table {
            width: 100%;
        }

        .no-margin-bottom {
            margin-bottom: 0 !important;
        }

        .cropper-container {
            max-width: 100%;
            max-height: 400px;
        }

        #croppedImage {
            margin-top: 20px;
        }

        .select2-container .select2-selection--single {
            height: 38px;
            line-height: 36px;
            padding: 6px 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }
    </style>

    <div class="wrapper">
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Edit Application Information</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Edit Applicant CV</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <form id="editCvForm" action="{{ route('update_cv', $data->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="status" id="status" value="">

                            <!-- Step 1: Personal Information -->
                            <div id="step-1" class="step-content">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Personal Information</h3>
                                    </div>

                                    <!-- Profile Photo Upload and Cropping -->
                                    <div class="form-group row" style="margin: 20px 0;">
                                        {!! Form::label('profile_photo', 'Profile Photo', ['class' => 'col-md-2 col-form-label']) !!}
                                        <div class="col-md-4">
                                            {!! Form::file('profile_photo', ['class' => 'form-control-file', 'id' => 'inputImage', 'accept' => 'image/*']) !!}
                                            <small class="form-text text-danger">[File Format: *.jpg/ .jpeg/ .png | Max
                                                Size: 2MB]</small>
                                            <div id="fileSizeError" class="text-danger mt-2" style="display: none;"></div>
                                        </div>

                                        <!-- Image Preview Section -->
                                        <div class="col-md-6">
                                            <!-- Show old profile photo if exists -->
                                            @if (!empty($data->profile_photo))
                                                <img id="currentProfilePhoto"
                                                    src="{{ asset('storage/profile_photos/' . $data->profile_photo) }}"
                                                    alt="Current Profile Photo" style="max-width: 100%; max-height: 200px;">
                                            @endif

                                            <!-- New Image (for cropping) will appear here after the user selects a new image -->
                                            <img id="image" src="" alt="Select an image to crop"
                                                style="max-width: 100%; max-height: 200px; display: none;">

                                            <!-- Cropped Image Preview will show here after the user crops the image -->
                                            <img id="croppedImagePreview" src="" alt="Cropped Image Preview"
                                                style="display: none; max-width: 100%; max-height: 200px; margin-top: 10px;">
                                        </div>
                                    </div>

                                    <!-- Cropping Button and Hidden Input for Cropped Image -->
                                    <div class="form-group row">
                                        <div class="col-md-4 offset-md-2">
                                            {!! Form::hidden('cropped_image', null, ['id' => 'croppedImage']) !!}
                                            {!! Form::button('Crop Image', [
                                                'type' => 'button',
                                                'id' => 'cropButton',
                                                'class' => 'btn btn-primary',
                                                'style' => 'display:none',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <!-- Name -->
                                    <div class="form-group row no-margin-top" style="margin: 20px 0;">
                                        <div class="col-md-2">
                                            {!! Form::label('first_name', 'First Name') !!}<span class="text-danger"> *</span>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::text('first_name', old('first_name', $data->first_name), [
                                                'class' => 'form-control',
                                                'id' => 'first_name',
                                                'placeholder' => 'Enter your first name',
                                                'required',
                                            ]) !!}
                                            @error('first_name')
                                                <strong class="text-danger">{{ $message }}</strong>
                                            @enderror
                                        </div>
                                        <div class="col-md-2">
                                            {!! Form::label('last_name', 'Last Name') !!}<span class="text-danger"> *</span>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::text('last_name', old('last_name', $data->last_name), [
                                                'class' => 'form-control',
                                                'id' => 'last_name',
                                                'placeholder' => 'Enter your last name',
                                                'required',
                                            ]) !!}
                                            @error('last_name')
                                                <strong class="text-danger">{{ $message }}</strong>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Father Name and Mother Name  -->
                                    <div class="form-group row no-margin-top" style="margin: 20px 0;">
                                        <div class="col-md-2">
                                            {!! Form::label('father_name', 'Father Name') !!}<span class="text-danger"> *</span>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::text('father_name', old('father_name', $data->father_name), [
                                                'class' => 'form-control',
                                                'id' => 'father_name',
                                                'placeholder' => 'Enter father name',
                                                'required',
                                            ]) !!}
                                            @error('father_name')
                                                <strong class="text-danger">{{ $message }}</strong>
                                            @enderror
                                        </div>

                                        <div class="col-md-2">
                                            {!! Form::label('mother_name', 'Mother Name') !!}<span class="text-danger"> *</span>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::text('mother_name', old('mother_name', $data->mother_name), [
                                                'class' => 'form-control',
                                                'id' => 'mother_name',
                                                'placeholder' => 'Enter mother name',
                                                'required',
                                            ]) !!}
                                            @error('mother_name')
                                                <strong class="text-danger">{{ $message }}</strong>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Phone Number and Email -->
                                    <div class="form-group row no-margin-top" style="margin: 20px 0;">
                                        <div class="col-md-2">
                                            {!! Form::label('phone_number', 'Phone Number') !!}<span class="text-danger"> *</span>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::tel('phone_number', old('phone_number', $data->phone_number), [
                                                'class' => 'form-control',
                                                'id' => 'phone_number',
                                                'placeholder' => 'Enter phone number',
                                                'required',
                                                'pattern' => '[0-9]{10,15}',
                                                'maxlength' => '15',
                                                'title' => 'Phone number must be between 10 and 15 digits and contain only numbers',
                                            ]) !!}
                                            @error('phone_number')
                                                <strong class="text-danger">{{ $message }}</strong>
                                            @enderror
                                        </div>

                                        <div class="col-md-2">
                                            {!! Form::label('email', 'Email') !!}<span class="text-danger"> *</span>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::email('email', old('email', $data->email), [
                                                'class' => 'form-control',
                                                'id' => 'email',
                                                'placeholder' => 'Enter email address',
                                                'required',
                                            ]) !!}
                                            <div class="invalid-feedback" id="emailError" style="display: none;">
                                                Please enter a valid email address.
                                            </div>
                                            @error('email')
                                                <strong class="text-danger">{{ $message }}</strong>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Date of Birth and Present address -->
                                    <div class="form-group row" style="margin: 20px 0;">
                                        <div class="col-md-2">
                                            {!! Form::label('permanent_address', 'Permanent Address') !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::textarea('permanent_address', old('permanent_address', $data->permanent_address), [
                                                'class' => 'form-control',
                                                'id' => 'permanent_address',
                                                'rows' => 2, // Adjust the number of rows to control the height
                                                'placeholder' => 'Enter permanent address',
                                            ]) !!}
                                            @error('permanent_address')
                                                <strong class="text-danger">{{ $message }}</strong>
                                            @enderror
                                        </div>


                                        <div class="col-md-2">
                                            {!! Form::label('present_address', 'Present Address') !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::textarea('present_address', old('present_address', $data->present_address), [
                                                'class' => 'form-control',
                                                'id' => 'present_address',
                                                'rows' => 2, // Adjust the number of rows to control the height
                                                'placeholder' => 'Enter present address',
                                            ]) !!}
                                            @error('present_address')
                                                <strong class="text-danger">{{ $message }}</strong>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Permanent address and Nationality -->
                                    <div class="form-group row" style="margin: 20px 0;">
                                        <div class="col-md-2">
                                            {!! Form::label('dob', 'Date of Birth') !!}<span class="text-danger"> *</span>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::date('dob', old('dob', $data->dob), [
                                                'class' => 'form-control',
                                                'id' => 'dob',
                                                'placeholder' => 'Enter date of birth',
                                                'max' => now()->toDateString(),
                                                'required',
                                            ]) !!}
                                            @error('dob')
                                                <strong class="text-danger">{{ $message }}</strong>
                                            @enderror
                                        </div>


                                        <div class="col-md-2">
                                            {!! Form::label('nationality', 'Nationality') !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::text('nationality', old('nationality', $data->nationality), [
                                                'class' => 'form-control',
                                                'id' => 'nationality',
                                                'placeholder' => 'Enter nationality',
                                            ]) !!}
                                            @error('nationality')
                                                <strong class="text-danger">{{ $message }}</strong>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Gender -->
                                    <div class="form-group row" style="margin: 20px 0;">
                                        {!! Form::label('gender', 'Gender', ['class' => 'col-md-2 col-form-label']) !!}
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                {!! Form::radio('gender', 'Male', old('gender', $data->gender) == 'Male', [
                                                    'class' => 'form-check-input',
                                                    'id' => 'genderMale',
                                                ]) !!}
                                                {!! Form::label('genderMale', 'Male', ['class' => 'form-check-label']) !!}
                                            </div>
                                            <div class="form-check form-check-inline">
                                                {!! Form::radio('gender', 'Female', old('gender', $data->gender) == 'Female', [
                                                    'class' => 'form-check-input',
                                                    'id' => 'genderFemale',
                                                ]) !!}
                                                {!! Form::label('genderFemale', 'Female', ['class' => 'form-check-label']) !!}
                                            </div>
                                            <div class="form-check form-check-inline">
                                                {!! Form::radio('gender', 'Other', old('gender', $data->gender) == 'Other', [
                                                    'class' => 'form-check-input',
                                                    'id' => 'genderOther',
                                                ]) !!}
                                                {!! Form::label('genderOther', 'Other', ['class' => 'form-check-label']) !!}
                                            </div>
                                            @error('gender')
                                                <strong class="text-danger">{{ $message }}</strong>
                                            @enderror
                                        </div>

                                           {!! Form::label('identity_type', 'Identity Type', ['class' => 'col-md-2 col-form-label']) !!}
                                            <div class="col-md-4">
                                                <div class="form-check form-check-inline">
                                                    <!-- NID radio button -->
                                                    {!! Form::radio('identity_type', 'nid', $data->identity_type == 'nid', [
                                                        'class' => 'form-check-input',
                                                        'id' => 'identityNID',
                                                        'onclick' => 'toggleInput("nid")'
                                                    ]) !!}
                                                    {!! Form::label('identityNID', 'NID', ['class' => 'form-check-label']) !!}
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <!-- BID radio button -->
                                                    {!! Form::radio('identity_type', 'bid', $data->identity_type == 'bid', [
                                                        'class' => 'form-check-input',
                                                        'id' => 'identityBID',
                                                        'onclick' => 'toggleInput("bid")'
                                                    ]) !!}
                                                    {!! Form::label('identityBID', 'BID', ['class' => 'form-check-label']) !!}
                                                </div>
                                            </div>
                                        </div>

                                    <!-- Attachment and NID/BID Number -->
                                    <div class="form-group row" style="margin: 20px 0;">
                                        {!! Form::label('attachment', 'Attachment', ['class' => 'col-md-2 col-form-label']) !!}
                                            <div class="col-md-4">
                                                {!! Form::file('attachment', ['class' => 'form-control', 'accept' => '.pdf']) !!}
                                                <small class="form-text text-muted">Please attach a PDF file. Max size: 2MB.</small>

                                                @if (!empty($data->attachment))
                                                    <small class="form-text text-info">
                                                        Current File:
                                                        <a href="{{ asset('storage/' . $data->attachment) }}" target="_blank" rel="noopener noreferrer">
                                                            View PDF
                                                        </a>
                                                    </small>
                                                @endif

                                                @error('attachment')
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                @enderror
                                            </div>

                                        <div class="col-md-2" id="nidLabel"
                                            style="display: {{ old('option', $data->identity_type) == 'nid' ? 'block' : 'none' }};">
                                            {!! Form::label('nid', 'NID Number', ['class' => 'col-form-label']) !!}
                                        </div>
                                        <div class="col-md-4" id="nidInput"
                                            style="display: {{ old('option', $data->identity_type) == 'nid' ? 'block' : 'none' }};">
                                            {!! Form::number('nid', old('nid', $data->nid), [
                                                'class' => 'form-control',
                                                'id' => 'nid',
                                                'placeholder' => 'Enter NID Number',
                                                'min' => '0',
                                                'maxlength' => '17',
                                            ]) !!}
                                        </div>

                                        <div class="col-md-2" id="bidLabel"
                                            style="display: {{ old('option', $data->identity_type) == 'bid' ? 'block' : 'none' }};">
                                            {!! Form::label('bid', 'BID Number', ['class' => 'col-form-label']) !!}
                                        </div>
                                        <div class="col-md-4" id="bidInput"
                                            style="display: {{ old('option', $data->identity_type) == 'bid' ? 'block' : 'none' }};">
                                            {!! Form::number('bid', old('bid', $data->bid), [
                                                'class' => 'form-control',
                                                'id' => 'bid',
                                                'placeholder' => 'Enter BID Number',
                                                'min' => '0',
                                                'maxlength' => '17',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        {!! Form::button('Save as Draft', [
                                            'class' => 'btn btn-info',
                                            'onclick' => "setStatus(-1); document.getElementById('editCvForm').submit();",
                                        ]) !!}
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-primary"
                                            onclick="showStep(2)">Next</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Academic Information -->
                            <div id="step-2" class="step-content" style="display: none;">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Academic Information</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="dynamicTable" class="table table-bordered mt-4">
                                                <thead>
                                                    <tr>
                                                        <th>Education Level</th>
                                                        <th>Institution</th>
                                                        <th>Group/Program</th>
                                                        <th>Passing Year</th>
                                                        <th style="width:11%">GPA/CGPA</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($educations as $index => $education)
                                                        <tr>
                                                            <td>
                                                                {!! Form::select(
                                                                    'education_level[]',
                                                                    [
                                                                        'Primary School Certificate' => 'Primary School Certificate',
                                                                        'Junior School Certificate' => 'Junior School Certificate',
                                                                        'Secondary School Certificate' => 'Secondary School Certificate',
                                                                        'Higher School Certificate' => 'Higher School Certificate',
                                                                        'Bachelor of Science' => 'Bachelor of Science',
                                                                        'Master of Science' => 'Master of Science',
                                                                        'Doctor of Philosophy' => 'Doctor of Philosophy',
                                                                        'Other' => 'Other',
                                                                    ],
                                                                    old('education_level.' . $index, $education->education_level),
                                                                    ['class' => 'form-control select2', 'style' => 'width: 100%;', 'placeholder' => 'Select education level'],
                                                                ) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('institution[]', old('institution.' . $index, $education->educational_institute_name), [
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Enter institution name',
                                                                ]) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select(
                                                                    'group_program[]',
                                                                    [
                                                                        'Science' => 'Science',
                                                                        'Commerce' => 'Commerce',
                                                                        'Arts' => 'Arts',
                                                                        'Computer Science and Engineering' => 'Computer Science and Engineering',
                                                                        'Electrical and Electronic Engineering' => 'Electrical and Electronic Engineering',
                                                                        'Civil Engineering' => 'Civil Engineering',
                                                                        'English' => 'English',
                                                                        'Artificial Intelligence' => 'Artificial Intelligence',
                                                                        'Data Science' => 'Data Science',
                                                                        'Biochemistry' => 'Biochemistry',
                                                                        'Other' => 'Other',
                                                                    ],
                                                                    old('group_program.' . $index, $education->department),
                                                                    ['class' => 'form-control select2', 'style' => 'width: 100%;', 'placeholder' => 'Select group/program'],
                                                                ) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::number('passing_year[]', old('passing_year.' . $index, $education->pass_year), [
                                                                    'class' => 'form-control',
                                                                    'min' => 1900,
                                                                    'max' => 2100,
                                                                    'placeholder' => 'Enter passing year',
                                                                ]) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::number('gpa_cgpa[]', old('gpa_cgpa.' . $index, $education->cgpa), [
                                                                    'class' => 'form-control',
                                                                    'step' => '0.01',
                                                                    'min' => '0.00',
                                                                    'max' => '5.00',
                                                                    'placeholder' => 'Enter GPA/CGPA out of 5',
                                                                ]) !!}
                                                            </td>
                                                            <td class="text-center">
                                                                @if ($index === 0)
                                                                    <!-- "+" Button only for the first row -->
                                                                    {!! Form::button('<i class="fa fa-plus"></i>', [
                                                                        'type' => 'button',
                                                                        'id' => 'addRow',
                                                                        'class' => 'btn btn-primary mb-3',
                                                                    ]) !!}
                                                                @else
                                                                    <!-- "-" Button for other rows -->
                                                                    {!! Form::button('<i class="fa fa-minus"></i>', [
                                                                        'type' => 'button',
                                                                        'class' => 'btn btn-danger mb-3 removeRow',
                                                                    ]) !!}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        {!! Form::button('Save as Draft', [
                                            'class' => 'btn btn-info',
                                            'onclick' => "setStatus(-1); document.getElementById('editCvForm').submit();",
                                        ]) !!}
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-secondary" onclick="showStep(1)"
                                            style="margin-right: 10px;">Previous</button>
                                        <button type="button" class="btn btn-primary"
                                            onclick="showStep(3)">Next</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Experience -->
                            <div id="step-3" class="step-content" style="display: none;">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Experience</h3>
                                    </div>
                                    <table id="dynamicTableEx" class="table table-bordered mt-4">
                                        <thead>
                                            <tr>
                                                <th>Company name</th>
                                                <th>Designation</th>
                                                <th>Location</th>
                                                <th>Start date</th>
                                                <th>End date</th>
                                                <th>Total experience</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($experiences as $index => $experience)
                                                <tr>
                                                    <td>
                                                        {!! Form::text('company_name[]', old('company_name.' . $index, $experience->company_name), [
                                                            'class' => 'form-control',
                                                            'placeholder' => 'Enter company name',
                                                        ]) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('designation[]', old('designation.' . $index, $experience->designation), [
                                                            'class' => 'form-control',
                                                            'placeholder' => 'Enter designation',
                                                        ]) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('company_location[]', old('company_location.' . $index, $experience->company_location), [
                                                            'class' => 'form-control',
                                                            'placeholder' => 'Enter company location',
                                                        ]) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::date('start[]', old('start.' . $index, $experience->start_date), [
                                                            'id' => 'start_date',
                                                            'class' => 'form-control start-date',
                                                            'max' => now()->toDateString(),
                                                            'onchange' => 'validateDates(this)',
                                                        ]) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::date('end[]', old('end.' . $index, $experience->end_date), [
                                                            'id' => 'end_date',
                                                            'class' => 'form-control end-date',
                                                            'max' => now()->toDateString(),
                                                            'onchange' => 'validateDates(this)',
                                                        ]) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('total[]', old('total.' . $index, $experience->total_experience), [
                                                            'id' => 'total_days',
                                                            'class' => 'form-control',
                                                            'readonly',
                                                        ]) !!}
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($index === 0)
                                                            <!-- "+" Button only for the first row -->
                                                            {!! Form::button('<i class="fa fa-plus"></i>', [
                                                                'type' => 'button',
                                                                'id' => 'addRowEx',
                                                                'class' => 'btn btn-primary mb-3',
                                                            ]) !!}
                                                        @else
                                                            <!-- "-" Button for other rows -->
                                                            {!! Form::button('<i class="fa fa-minus"></i>', [
                                                                'type' => 'button',
                                                                'class' => 'btn btn-danger mb-3 removeRow',
                                                            ]) !!}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        {!! Form::button('Save as Draft', [
                                            'class' => 'btn btn-info',
                                            'onclick' => "setStatus(-1); document.getElementById('editCvForm').submit();",
                                        ]) !!}
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-secondary" onclick="showStep(2)"
                                            style="margin-right: 10px;">Previous</button>
                                        <button type="button" class="btn btn-primary"
                                            onclick="showStep(4)">Next</button>
                                    </div>
                                </div>
                            </div>


                            <!-- Step 4: Training and Certification -->
                            <div id="step-4" class="step-content" style="display: none;">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Training and certification</h3>
                                    </div>
                                    <table id="dynamicTableTr" class="table table-bordered mt-4">
                                        <thead>
                                            <tr>
                                                <th>Training title</th>
                                                <th>Institute name</th>
                                                <th>Duration</th>
                                                <th>Training year</th>
                                                <th>Location</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($trainings as $index => $training)
                                                <tr>
                                                    <td>
                                                        {!! Form::text('title[]', old('title.' . $index, $training->title), [
                                                            'class' => 'form-control',
                                                            'placeholder' => 'Enter training title',
                                                        ]) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('institute_name[]', old('institute_name.' . $index, $training->institute_name), [
                                                            'class' => 'form-control',
                                                            'placeholder' => 'Enter institute name',
                                                        ]) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('duration[]', old('duration.' . $index, $training->duration), [
                                                            'class' => 'form-control',
                                                            'placeholder' => 'Enter training duration',
                                                        ]) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::number('year[]', old('year.' . $index, $training->year), [
                                                            'class' => 'form-control',
                                                            'min' => 1900,
                                                            'max' => 2100,
                                                            'placeholder' => 'Enter year',
                                                        ]) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('training_location[]', old('training_location.' . $index, $training->training_location), [
                                                            'class' => 'form-control',
                                                            'placeholder' => 'Enter the location',
                                                        ]) !!}
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($index === 0)
                                                            <!-- "+" Button for the first row -->
                                                            {!! Form::button('<i class="fa fa-plus"></i>', [
                                                                'type' => 'button',
                                                                'id' => 'addRowTr',
                                                                'class' => 'btn btn-primary mb-3',
                                                            ]) !!}
                                                        @else
                                                            <!-- "-" Button for the rest of the rows -->
                                                            {!! Form::button('<i class="fa fa-minus"></i>', [
                                                                'type' => 'button',
                                                                'class' => 'btn btn-danger mb-3 removeRow',
                                                            ]) !!}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <div>
                                        {!! Form::button('Save as Draft', ['class' => 'btn btn-info', 'onclick' => 'saveAsDraft();']) !!}
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-secondary" onclick="showStep(3)"
                                            style="margin-right: 10px;">Previous</button>
                                        <button type="submit" class="btn btn-success"
                                            onclick="submitForm();">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection


@section('footer-resources')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://unpkg.com/cropperjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/tracking/build/tracking-min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tracking/build/data/face-min.js"></script>
    <script src="https://unpkg.com/cropperjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1/dist/face-api.js"></script>



    <script>
        function validateStep(step) {
            let isValid = true;
            let stepContent = document.querySelector('#step-' + step);
            let requiredFields = stepContent.querySelectorAll('[required]');

            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');

                    if (!field.nextElementSibling || !field.nextElementSibling.classList.contains(
                            'invalid-feedback')) {
                        let errorElement = document.createElement('div');
                        errorElement.className = 'invalid-feedback';
                        errorElement.innerText = 'This field is required.';
                        field.parentNode.appendChild(errorElement);
                    }
                } else {
                    field.classList.remove('is-invalid');
                    if (field.nextElementSibling && field.nextElementSibling.classList.contains(
                            'invalid-feedback')) {
                        field.nextElementSibling.remove();
                    }
                }
            });

            return isValid;
        }

        function showStep(step) {
            if (step > 1 && !validateStep(step - 1)) {
                return;
            }

            document.querySelectorAll('.step-content').forEach(function(stepContent) {
                stepContent.style.display = 'none';
            });

            document.getElementById('step-' + step).style.display = 'block';
        }
    </script>

    <script>
        $(document).ready(function() {
            let cropper;
            const inputImage = document.getElementById('inputImage');
            const image = document.getElementById('image');
            const croppedImageInput = document.getElementById('croppedImage');
            const cropButton = document.getElementById('cropButton');
            const croppedImagePreview = document.getElementById('croppedImagePreview');
            const fileSizeError = document.getElementById('fileSizeError');
            const currentProfilePhoto = document.getElementById('currentProfilePhoto');

            // Hide the Crop Button and Cropped Preview initially
            cropButton.style.display = 'none';
            croppedImagePreview.style.display = 'none';

            // When the user selects a new image
            inputImage.addEventListener('change', function(event) {
                const files = event.target.files;
                if (files && files.length > 0) {
                    const file = files[0];

                    // Check file size (limit: 2MB)
                    if (file.size > 2 * 1024 * 1024) { // 2MB in bytes
                        fileSizeError.style.display = 'block';
                        fileSizeError.textContent = 'File size exceeds 2MB. Please upload a smaller image.';
                        inputImage.value = '';
                        image.style.display = 'none';
                        cropButton.style.display = 'none';
                        if (cropper) {
                            cropper.destroy();
                            cropper = null;
                        }
                        return;
                    } else {
                        fileSizeError.style.display = 'none';
                    }

                    // Show the new image and hide the old profile photo
                    if (currentProfilePhoto) {
                        currentProfilePhoto.style.display = 'none';
                    }
                    image.style.display = 'block';

                    const done = url => {
                        inputImage.value = '';
                        image.src = url;
                        if (cropper) {
                            cropper.destroy();
                        }
                        cropper = new Cropper(image, {
                            viewMode: 1,
                            autoCropArea: 1,
                            aspectRatio: 1,
                            movable: true,
                            zoomable: true,
                            scalable: true,
                            cropBoxResizable: true,
                        });

                        cropButton.style.display = 'block';
                    };

                    if (URL) {
                        done(URL.createObjectURL(file));
                    } else if (FileReader) {
                        const reader = new FileReader();
                        reader.onload = e => {
                            done(reader.result);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });

            // When the user clicks the "Crop Image" button
            cropButton.addEventListener('click', function() {
                if (!cropper) {
                    alert('Please upload and select an image first.');
                    return;
                }

                const canvas = cropper.getCroppedCanvas();
                if (canvas) {
                    const croppedImage = canvas.toDataURL('image/jpeg');

                    croppedImageInput.value = croppedImage;

                    croppedImagePreview.src = croppedImage;
                    croppedImagePreview.style.display = 'block';

                    image.style.display = 'none';

                    cropButton.style.display = 'none';

                    cropper.destroy();
                    cropper = null;
                }
            });
        });
    </script>

    <script>
        function setStatus(statusValue) {
            document.getElementById('status').value = statusValue;
        }
    </script>

    <script>
        // Function to handle Save as Draft button
        function saveAsDraft() {
            document.getElementById('status').value = -1;
            document.getElementById('editCvForm').submit();
        }

        // Function to handle Submit button
        function submitForm() {
            document.getElementById('status').value = 1;
            document.getElementById('editCvForm').submit();
        }

        // Show the appropriate step of the form
        function showStep(step) {
            document.querySelectorAll('.step-content').forEach(function(stepContent) {
                stepContent.style.display = 'none';
            });
            document.getElementById('step-' + step).style.display = 'block';
        }
    </script>


    <script>
        const phoneInput = document.querySelector("#phone_number");

        // Initialize intlTelInput plugin without formatting
        const iti = window.intlTelInput(phoneInput, {
            initialCountry: "bd",
            separateDialCode: true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.11/build/js/utils.js",
            formatOnDisplay: false,
            nationalMode: false
        });

        phoneInput.addEventListener('input', function() {
            // Remove any non-digit characters
            this.value = this.value.replace(/[^0-9]/g, '');

            if (this.value.length > 15) {
                this.value = this.value.slice(0, 15);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            if (phoneInput.value) {
                phoneInput.value = phoneInput.value.replace(/[^0-9]/g, '');
            }
        });
    </script>


    <script>
        function toggleInput(type) {
            const nidLabel = document.getElementById('nidLabel');
            const nidInput = document.getElementById('nidInput');
            const nidField = document.getElementById('nid');

            const bidLabel = document.getElementById('bidLabel');
            const bidInput = document.getElementById('bidInput');
            const bidField = document.getElementById('bid');

            if (type === 'nid') {
                // Show NID and hide BID
                nidLabel.style.display = 'block';
                nidInput.style.display = 'block';
                bidLabel.style.display = 'none';
                bidInput.style.display = 'none';

                // Clear BID field if NID is selected
                bidField.value = '';
            } else if (type === 'bid') {
                // Show BID and hide NID
                bidLabel.style.display = 'block';
                bidInput.style.display = 'block';
                nidLabel.style.display = 'none';
                nidInput.style.display = 'none';

                // Clear NID field if BID is selected
                nidField.value = '';
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateOfBirthInput = document.getElementById('dob');

            // Function to format the date as "Month name, date, year"
            function formatDateToReadableString(date) {
                const options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                return date.toLocaleDateString('en-US', options);
            }

            // Check if the input value exists and format it
            if (dateOfBirthInput.value) {
                const dobDate = new Date(dateOfBirthInput.value);
                if (!isNaN(dobDate.getTime())) {
                    dateOfBirthInput.setAttribute('type', 'text');
                    dateOfBirthInput.value = formatDateToReadableString(dobDate);
                }
            }

            // When the input is focused, change the input type back to 'date' for editing
            dateOfBirthInput.addEventListener('focus', function() {
                this.setAttribute('type', 'date');
            });

            // When a new date is selected, format it back to "Month name, date, year" after selecting
            dateOfBirthInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                if (!isNaN(selectedDate.getTime())) {
                    this.setAttribute('type', 'text');
                    this.value = formatDateToReadableString(selectedDate);
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Function to calculate experience based on start and end dates
            function calculateExperience(startDate, endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);

                if (isNaN(start.getTime()) || isNaN(end.getTime())) {
                    return '';
                }

                let years = end.getFullYear() - start.getFullYear();
                let months = end.getMonth() - start.getMonth();
                let days = end.getDate() - start.getDate();

                // Adjust for negative days
                if (days < 0) {
                    months -= 1;
                    days += new Date(end.getFullYear(), end.getMonth(), 0).getDate();
                }

                // Adjust for negative months
                if (months < 0) {
                    years -= 1;
                    months += 12;
                }

                if (years < 0 || (years === 0 && months === 0 && days < 0)) {
                    return '';
                }

                let experienceString = '';
                if (years > 0) experienceString += years + ' year' + (years > 1 ? 's' : '') + ', ';
                if (months > 0) experienceString += months + ' month' + (months > 1 ? 's' : '') + ', ';
                if (days > 0 || (years === 0 && months === 0)) experienceString += days + ' day' + (days > 1 ? 's' : '');

                return experienceString.trim().replace(/,$/, '');
            }

            // Function to update experience when start or end dates change
            function updateExperience(row) {
                const startDate = row.find('input[name="start[]"]').val();
                const endDate = row.find('input[name="end[]"]').val();
                const totalExperienceField = row.find('input[name="total[]"]');

                if (startDate && endDate) {
                    const start = new Date(startDate);
                    const end = new Date(endDate);

                    if (end < start) {
                        // Show an alert if the End date is earlier than the Start date
                        alert('End date cannot be earlier than the Start date.');

                        // Clear the End date field to prevent invalid inputs
                        row.find('input[name="end[]"]').val('');

                        // Reset the total experience field
                        totalExperienceField.val('');
                    } else {
                        // If dates are valid, calculate and display experience
                        const experience = calculateExperience(startDate, endDate);
                        totalExperienceField.val(experience);
                    }
                } else {
                    // Clear the total experience if either of the dates is missing
                    totalExperienceField.val('');
                }
            }

            // Function to attach event listeners to date fields in a row
            function attachDateListeners(row) {
                row.find('input[name="start[]"], input[name="end[]"]').off('change').on('change', function() {
                    updateExperience(row);
                });
            }

            // Function to add a new row
            function addNewRowEx() {
                let tableBody = $('#dynamicTableEx tbody');
                let firstRow = tableBody.find('tr:first').clone();

                // Clear the values in the cloned row's input fields
                firstRow.find('input').val('');

                // Ensure the total experience field is empty
                firstRow.find('input[name="total[]"]').val('');

                // Remove the "+" button from the cloned row and add a "-" button
                firstRow.find('.btn-primary').remove();
                firstRow.find('td:last-child').html(
                    '<button type="button" class="btn btn-danger mb-3 removeRow"><i class="fa fa-minus"></i></button>'
                );

                // Append the cloned row to the table body
                tableBody.append(firstRow);

                // Attach event listeners for start and end date change
                attachDateListeners(firstRow);

                // Attach event listener for removing the row
                firstRow.find('.removeRow').on('click', function() {
                    $(this).closest('tr').remove();
                });
            }

            // Attach event listener to the "+" button to add a new row when clicked
            $('#addRowEx').on('click', function() {
                addNewRowEx();
            });

            // Attach event listener for removing rows (initial rows)
            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
            });

            // Initially attach listeners to existing rows
            $('#dynamicTableEx tbody tr').each(function() {
                attachDateListeners($(this));
            });
        });

    </script>
    <script>
        $(document).ready(function() {
            // Function to validate CGPA/GPA
            function validateCgpa(cgpaInput) {
                const cgpaValue = parseFloat(cgpaInput.val());
                if (cgpaValue > 5.00) {
                    cgpaInput.val(5.00);
                    alert('GPA/CGPA cannot exceed 5.00.');
                } else if (cgpaValue < 0 || isNaN(cgpaValue)) {
                    cgpaInput.val('');
                    alert('Please enter a valid GPA/CGPA.');
                }
            }

            // Attach the validation event to CGPA/GPA inputs
            $('input[name="gpa_cgpa[]"]').on('input', function() {
                validateCgpa($(this));
            });

            // For dynamically added rows, make sure to attach the validation event
            $(document).on('input', 'input[name="gpa_cgpa[]"]', function() {
                validateCgpa($(this));
            });
        });
    </script>




    <script>
        function validateEmail(email) {
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return emailPattern.test(email);
        }

        function validateStep(step) {
            let isValid = true;
            let stepContent = document.querySelector('#step-' + step);
            let requiredFields = stepContent.querySelectorAll('[required]');

            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    if (!field.nextElementSibling || !field.nextElementSibling.classList.contains(
                            'invalid-feedback')) {
                        let errorElement = document.createElement('div');
                        errorElement.className = 'invalid-feedback';
                        errorElement.innerText = 'This field is required.';
                        field.parentNode.appendChild(errorElement);
                    }
                } else {
                    field.classList.remove('is-invalid');
                    if (field.nextElementSibling && field.nextElementSibling.classList.contains(
                            'invalid-feedback')) {
                        field.nextElementSibling.remove();
                    }
                }
            });

            const emailField = document.getElementById('email');
            if (emailField && !validateEmail(emailField.value)) {
                isValid = false;
                emailField.classList.add('is-invalid');
                if (!document.getElementById('emailError')) {
                    let errorElement = document.createElement('div');
                    errorElement.className = 'invalid-feedback';
                    errorElement.id = 'emailError';
                    errorElement.innerText = 'Please enter a valid email address.';
                    emailField.parentNode.appendChild(errorElement);
                }
            } else if (emailField) {
                emailField.classList.remove('is-invalid');
                const emailError = document.getElementById('emailError');
                if (emailError) {
                    emailError.remove();
                }
            }

            return isValid;
        }

        function showStep(step, validate = true) {
            if (validate && step > 1 && !validateStep(step - 1)) {
                return;
            }

            document.querySelectorAll('.step-content').forEach(function(stepContent) {
                stepContent.style.display = 'none';
            });

            document.getElementById('step-' + step).style.display = 'block';
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-next').forEach(function(button) {
                button.addEventListener('click', function() {
                    const currentStep = parseInt(this.getAttribute('data-current-step'));
                    showStep(currentStep + 1);
                });
            });

            document.querySelectorAll('.btn-previous').forEach(function(button) {
                button.addEventListener('click', function() {
                    const currentStep = parseInt(this.getAttribute('data-current-step'));
                    showStep(currentStep - 1, false);
                });
            });
        });
    </script>



    <script>
        $(document).ready(function() {
            // Academic Information Section
            $('#addRow').off('click').on('click', function() {
                let tableBody = $('#dynamicTable tbody');
                let firstRow = tableBody.find('tr:first').clone();
                // Clear the values of the cloned row
                firstRow.find('input').val('');
                firstRow.find('select').val('').trigger('change');

                // Remove any existing "+" button in the new row and add a "-" button
                firstRow.find('.btn-primary').remove();
                firstRow.find('td:last-child').html(
                    '<button type="button" class="btn btn-danger mb-3 removeRow"><i class="fa fa-minus"></i></button>'
                );

                tableBody.append(firstRow);
            });


            // Training and Certification Section
            $('#addRowTr').off('click').on('click', function() {
                let tableBody = $('#dynamicTableTr tbody');
                let firstRow = tableBody.find('tr:first').clone();

                // Clear the values
                firstRow.find('input').val('');
                firstRow.find('select').val('').trigger('change');

                // Remove any existing "+" button in the new row and add a "-" button
                firstRow.find('.btn-primary').remove();
                firstRow.find('td:last-child').html(
                    '<button type="button" class="btn btn-danger mb-3 removeRow"><i class="fa fa-minus"></i></button>'
                );

                tableBody.append(firstRow);
            });

            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>
@endsection
