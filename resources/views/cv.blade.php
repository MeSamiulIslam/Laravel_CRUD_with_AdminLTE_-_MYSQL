@extends('master')

@section('title')
    Application Form
@endsection

@section('header-resources')
    <link rel="stylesheet" href="https://unpkg.com/cropperjs/dist/cropper.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.11/build/css/intlTelInput.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

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
            margin-left: 10px;
            margin-right: 10px;
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

        .step-content .card {
            width: 100%;
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

        .intl-tel-input {
            display: block !important;
            width: 100% !important;
        }

        .form-control {
            width: 100%;
        }

        .intl-tel-input input[type="tel"] {
            width: 100% !important;
            height: 38px;
            padding: 6px 12px;
            line-height: 1.5;
        }

        .intl-tel-input .country-list {
            width: 100% !important;
        }
    </style>

    <div class="wrapper">
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Application Information</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Applicant CV</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- form start -->
                        {!! Form::open([
                            'route' => 'Upload',
                            'method' => 'POST',
                            'enctype' => 'multipart/form-data',
                            'files' => true,
                            'id' => 'fileUpload',
                        ]) !!}

                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Step 1: Personal Information -->
                        <div id="step-1" class="step-content">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Personal Information</h3>
                                </div>

                                <!-- Profile Photo -->
                                <div class="form-group row">
                                    {!! Form::label('profile_photo', 'Profile Photo', ['class' => 'col-md-2 col-form-label']) !!}
                                    <div class="col-md-4">
                                        {!! Form::file('profile_photo', [
                                            'class' => 'form-control-file',
                                            'id' => 'inputImage',
                                            'accept' => 'image/*',
                                        ]) !!}
                                        <small class="form-text text-muted">[File Format: *.jpg/ .jpeg/ .png |
                                            Max Size: 2MB]</small>
                                        <div id="fileSizeError" class="text-danger mt-2" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div style="overflow: hidden; margin-top: 10px;">
                                            <img id="image" src="" alt="Select an image to crop"
                                                style="max-width: 100%; max-height: 200px; display: none;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Crop Image button and preview -->
                                <div class="form-group row">
                                    <div class="col-md-4 offset-md-2">
                                        {!! Form::hidden('cropped_image', null, ['id' => 'croppedImage']) !!}
                                        {!! Form::button('Crop Image', ['type' => 'button', 'id' => 'cropButton', 'class' => 'btn btn-primary']) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-2">
                                        <img id="croppedImagePreview" src="" alt="Cropped Image Preview"
                                            style="display: none; max-width: 100%; max-height: 200px; margin-top: 10px;">
                                    </div>
                                </div>

                                <!-- Name -->
                                <div class="form-group row no-margin-top">
                                    <div class="col-md-2">
                                        {!! Form::label('first_name', 'First Name') !!}<span style="color: red;"> *</span>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::text('first_name', old('first_name'), [
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
                                        {!! Form::label('last_name', 'Last Name') !!}<span style="color: red;"> *</span>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::text('last_name', old('last_name'), [
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
                                <div class="form-group row no-margin-top">
                                    <div class="col-md-2">
                                        {!! Form::label('father_name', 'Father Name') !!}<span style="color: red;"> *</span>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::text('father_name', old('father_name'), [
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
                                        {!! Form::label('mother_name', 'Mother Name') !!}<span style="color: red;"> *</span>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::text('mother_name', old('mother_name'), [
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

                                <!--Phone Number and Email -->
                                <div class="form-group row no-margin-top">
                                    <div class="col-md-2">
                                        {!! Form::label('phone_number', 'Phone Number') !!}<span style="color: red;"> *</span>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            {!! Form::text('phone_number', old('phone_number'), [
                                                'class' => 'form-control',
                                                'id' => 'phone_number',
                                                'placeholder' => 'Enter phone number',
                                                'required',
                                                'pattern' => '[0-9]{11}', // Accepts exactly 11 digits
                                                'maxlength' => '11', // Limits input to 11 digits
                                                'title' => 'Phone number must contain exactly 11 digits',
                                            ]) !!}
                                        </div>
                                        @error('phone_number')
                                            <strong class="text-danger">{{ $message }}</strong>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        {!! Form::label('email', 'Email') !!}<span style="color: red;"> *</span>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::email('email', old('email'), [
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


                                <!-- Date of Birth and Nationality -->
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        {!! Form::label('dob', 'Date of Birth') !!}<span style="color: red;"> *</span>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::date('dob', old('dob'), [
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
                                    {!! Form::label('nationality', 'Nationality', ['class' => 'col-md-2 col-form-label']) !!}
                                    <div class="col-md-4">
                                        {!! Form::text('nationality', old('nationality'), [
                                            'class' => 'form-control',
                                            'id' => 'nationality',
                                            'placeholder' => 'Enter nationality',
                                        ]) !!}
                                        @error('nationality')
                                            <strong class="text-danger">{{ $message }}</strong>
                                        @enderror
                                    </div>


                                </div>

                                <!-- Permanent Address and Present Address -->
                                <div class="form-group row">
                                    {!! Form::label('permanent_address', 'Permanent Address', ['class' => 'col-md-2 col-form-label']) !!}
                                    <div class="col-md-4">
                                        {!! Form::textarea('permanent_address', old('permanent_address'), [
                                            'class' => 'form-control',
                                            'id' => 'permanent_address',
                                            'placeholder' => 'Enter permanent address',
                                            'rows' => 2, // Adjust the number of rows to control the height
                                        ]) !!}
                                        @error('permanent_address')
                                            <strong class="text-danger">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                    {!! Form::label('present_address', 'Present Address', ['class' => 'col-md-2 col-form-label']) !!}
                                    <div class="col-md-4">
                                        {!! Form::textarea('present_address', old('present_address'), [
                                            'class' => 'form-control',
                                            'id' => 'present_address',
                                            'placeholder' => 'Enter present address',
                                            'rows' => 2, // Adjust the number of rows to control the height
                                        ]) !!}
                                        @error('present_address')
                                            <strong class="text-danger">{{ $message }}</strong>
                                        @enderror
                                    </div>


                                </div>

                                <!-- Gender and Identity -->
                                <div class="form-group row">
                                    {!! Form::label('gender', 'Gender', ['class' => 'col-md-2 col-form-label']) !!}
                                    <div class="col-md-4">
                                        <div class="form-check form-check-inline">
                                            {!! Form::radio('gender', 'Male', old('gender') == 'Male', [
                                                'class' => 'form-check-input',
                                                'id' => 'genderMale',
                                            ]) !!}
                                            {!! Form::label('genderMale', 'Male', ['class' => 'form-check-label']) !!}
                                        </div>
                                        <div class="form-check form-check-inline">
                                            {!! Form::radio('gender', 'Female', old('gender') == 'Female', [
                                                'class' => 'form-check-input',
                                                'id' => 'genderFemale',
                                            ]) !!}
                                            {!! Form::label('genderFemale', 'Female', ['class' => 'form-check-label']) !!}
                                        </div>
                                        <div class="form-check form-check-inline">
                                            {!! Form::radio('gender', 'Other', old('gender') == 'Other', [
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
                                            {!! Form::radio('identity_type', 'nid', old('identity_type') == 'nid', [
                                                'class' => 'form-check-input',
                                                'id' => 'identity_typeNID',
                                                'onclick' => 'toggleInput("nid")',
                                            ]) !!}
                                            {!! Form::label('identity_typeNID', 'NID', ['class' => 'form-check-label']) !!}
                                        </div>
                                        <div class="form-check form-check-inline">
                                            {!! Form::radio('identity_type', 'bid', old('identity_type') == 'bid', [
                                                'class' => 'form-check-input',
                                                'id' => 'identity_typeBID',
                                                'onclick' => 'toggleInput("bid")',
                                            ]) !!}
                                            {!! Form::label('identity_typeBID', 'BID', ['class' => 'form-check-label']) !!}
                                        </div>
                                    </div>
                                </div>

                                <!-- Attachment and NID/BID Number -->
                                <div class="form-group row">
                                    {!! Form::label('attachment', 'Attachment', ['class' => 'col-md-2 col-form-label']) !!}
                                    <div class="col-md-4">
                                        {!! Form::file('attachment', [
                                            'class' => 'form-control',
                                            'id' => 'attachmentInput',
                                            'accept' => '.pdf',
                                        ]) !!}
                                        <small class="form-text" style="color: red;">Please attach a PDF file. Max size:
                                            2MB.</small>
                                        <div id="attachmentError" class="text-danger mt-2" style="display: none;"></div>
                                    </div>

                                    <!-- NID Field -->
                                    <div class="col-md-2" id="nidLabel"
                                        style="display: {{ old('option') == 'nid' ? 'block' : 'none' }};">
                                        {!! Form::label('nid', 'NID Number', ['class' => 'col-form-label']) !!}
                                    </div>
                                    <div class="col-md-4" id="nidInput"
                                        style="display: {{ old('option') == 'nid' ? 'block' : 'none' }};">
                                        {!! Form::number('nid', old('nid'), [
                                            'class' => 'form-control',
                                            'id' => 'nid',
                                            'placeholder' => 'Enter NID Number',
                                            'min' => '0',
                                            'maxlength' => '17',
                                        ]) !!}
                                    </div>

                                    <!-- BID Field -->
                                    <div class="col-md-2" id="bidLabel"
                                        style="display: {{ old('option') == 'bid' ? 'block' : 'none' }};">
                                        {!! Form::label('bid', 'BID Number', ['class' => 'col-form-label']) !!}
                                    </div>
                                    <div class="col-md-4" id="bidInput"
                                        style="display: {{ old('option') == 'bid' ? 'block' : 'none' }};">
                                        {!! Form::number('bid', old('bid'), [
                                            'class' => 'form-control',
                                            'id' => 'bid',
                                            'placeholder' => 'Enter BID Number',
                                            'min' => '0',
                                            'maxlength' => '17',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                            <!-- Step navigation -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    {!! Form::button('Save as Draft', [
                                        'class' => 'btn btn-info',
                                        'onclick' => "setStatus(-1); document.getElementById('fileUpload').submit();",
                                    ]) !!}
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary" onclick="showStep(2)">Next</button>
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
                                                            null,
                                                            ['class' => 'form-control select2', 'style' => 'width: 100%;', 'placeholder' => 'Select education level'],
                                                        ) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('institution[]', null, ['class' => 'form-control', 'placeholder' => 'Enter institution name']) !!}
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
                                                            null,
                                                            ['class' => 'form-control select2', 'style' => 'width: 100%;', 'placeholder' => 'Select group/program'],
                                                        ) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::number('passing_year[]', null, [
                                                            'class' => 'form-control',
                                                            'min' => 1950,
                                                            'max' => 2100,
                                                            'placeholder' => 'Enter passing year',
                                                        ]) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::number('gpa_cgpa[]', null, [
                                                            'class' => 'form-control',
                                                            'step' => '0.01', // Allow decimals with two decimal places
                                                            'min' => '0.00', // Minimum CGPA value
                                                            'max' => '5.00', // Maximum CGPA value
                                                            'placeholder' => 'Enter GPA/CGPA out of 5',
                                                        ]) !!}
                                                    </td>

                                                    <td class="text-center">
                                                        {!! Form::button('<i class="fa fa-plus"></i>', [
                                                            'type' => 'button',
                                                            'id' => 'addRow',
                                                            'class' => 'btn btn-primary mb-3',
                                                        ]) !!}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>

                                    {!! Form::button('Save as Draft', [
                                        'class' => 'btn btn-info',
                                        'onclick' => "setStatus(-1); document.getElementById('fileUpload').submit();",
                                    ]) !!}
                                </div>

                                <div>
                                    <button type="button" class="btn btn-secondary" onclick="showStep(1)"
                                        style="margin-right: 10px;">Previous</button>
                                    <button type="button" class="btn btn-primary" onclick="showStep(3)">Next</button>
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
                                        <tr>
                                            <td>{!! Form::text('company_name[]', null, ['class' => 'form-control', 'placeholder' => 'Enter company name']) !!}</td>
                                            <td>{!! Form::text('designation[]', null, ['class' => 'form-control', 'placeholder' => 'Enter designation']) !!}</td>
                                            <td>{!! Form::text('company_location[]', null, [
                                                'class' => 'form-control',
                                                'placeholder' => 'Enter company location',
                                            ]) !!}</td>
                                            <td>
                                                {!! Form::date('start[]', null, [
                                                    'id' => 'start_date',
                                                    'class' => 'form-control start-date',
                                                    'max' => now()->toDateString(),
                                                    'onchange' => 'validateDates(this)',
                                                ]) !!}
                                                <div class="invalid-feedback text-danger" style="display:none;">Start
                                                    date
                                                    must be greater than End date.</div>
                                            </td>
                                            <td>
                                                {!! Form::date('end[]', null, [
                                                    'id' => 'end_date',
                                                    'class' => 'form-control end-date',
                                                    'max' => now()->toDateString(),
                                                    'onchange' => 'validateDates(this)',
                                                ]) !!}
                                                <div class="invalid-feedback text-danger" style="display:none;">Start
                                                    date
                                                    must be greater than End date.</div>
                                            </td>
                                            <td>{!! Form::text('total[]', null, ['id' => 'total_days', 'class' => 'form-control', 'readonly']) !!}</td>
                                            <td>{!! Form::button('<i class="fa fa-plus"></i>', [
                                                'type' => 'button',
                                                'id' => 'addRowEx',
                                                'class' => 'btn btn-primary mb-3',
                                            ]) !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>

                                    {!! Form::button('Save as Draft', [
                                        'class' => 'btn btn-info',
                                        'onclick' => "setStatus(-1); document.getElementById('fileUpload').submit();",
                                    ]) !!}


                                </div>

                                <div>
                                    <button type="button" class="btn btn-secondary" onclick="showStep(2)"
                                        style="margin-right: 10px;">Previous</button>
                                    <button type="button" class="btn btn-primary" onclick="showStep(4)">Next</button>

                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Training and Certification -->
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
                                        <tr>
                                            <td>{!! Form::text('title[]', null, ['class' => 'form-control', 'placeholder' => 'Enter training title']) !!}</td>
                                            <td>{!! Form::text('institute_name[]', null, ['class' => 'form-control', 'placeholder' => 'Enter institute name']) !!}</td>
                                            <td>{!! Form::text('duration[]', null, ['class' => 'form-control', 'placeholder' => 'Enter training duration']) !!}</td>
                                            <td>{!! Form::number('year[]', null, [
                                                'class' => 'form-control',
                                                'min' => 1900,
                                                'max' => 2100,
                                                'placeholder' => 'Enter year',
                                            ]) !!}</td>
                                            <td>{!! Form::text('training_location[]', null, ['class' => 'form-control', 'placeholder' => 'Enter the location']) !!}</td>
                                            <td>
                                                {!! Form::button('<i class="fa fa-plus"></i>', [
                                                    'type' => 'button',
                                                    'id' => 'addRowTr',
                                                    'class' => 'btn btn-primary mb-3',
                                                ]) !!}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Submit and Previous Buttons -->
                            <div class="d-flex justify-content-between">
                                <div>
                                    {!! Form::hidden('status', '', ['id' => 'status']) !!}
                                    {!! Form::button('Save as Draft', ['class' => 'btn btn-info', 'onclick' => 'submitForm(-1)']) !!}
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary"
                                        onclick="showStep(3)">Previous</button>
                                    <button type="submit" class="btn btn-success"
                                        onclick="submitForm(1)">Submit</button>
                                </div>
                            </div>
                        </div>


                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- /.row -->
        </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    </div>
@endsection

@section('footer-resources')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <script>
        function validateStep(step) {
            let isValid = true; // Assume the step is valid initially
            let stepContent = document.querySelector('#step-' + step);
            let requiredFields = stepContent.querySelectorAll('[required]');

            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');

                    // Show error message
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
            document.querySelectorAll('.step-content').forEach(function(stepContent) {
                stepContent.style.display = 'none';
            });
            document.getElementById('step-' + step).style.display = 'block';
        }
    </script>


    <script>
        // Initialize intlTelInput
        const input = document.querySelector("#phone_number");
        const iti = window.intlTelInput(input, {
            initialCountry: "bd",
            separateDialCode: true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.11/build/js/utils.js",
            nationalMode: true,
            formatOnDisplay: true,
            autoHideDialCode: true,
        });

        // Restrict input to digits and limit to 11 characters
        input.addEventListener('input', function() {
            const maxLength = 11;
            let dialCodeLength = iti.getSelectedCountryData().dialCode.length;

            if (this.value.length > maxLength) {
                this.value = this.value.slice(0, maxLength);
            }

            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Validate the phone number on form submission or on input change
        input.addEventListener('blur', function() {
            if (iti.isValidNumber()) {
                input.setCustomValidity(''); // Reset any custom validation error
            } else {
                // Phone number is not valid, show an error
                input.setCustomValidity('Please enter a valid phone number');
                input.reportValidity();
            }
        });
    </script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://unpkg.com/cropperjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/tracking/build/tracking-min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tracking/build/data/face-min.js"></script>
    <script src="https://unpkg.com/cropperjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1/dist/face-api.js"></script>

           <script>
        let cropper;
        const inputImage = document.getElementById('inputImage');
        const image = document.getElementById('image');
        const croppedImageInput = document.getElementById('croppedImage');
        const fileSizeError = document.getElementById('fileSizeError');
        const profilePhotoError = document.getElementById('profilePhotoError');
        const cropButton = document.getElementById('cropButton');
        const croppedImagePreview = document.getElementById('croppedImagePreview');

        // Initially hide the "Crop Image" button
        cropButton.style.display = 'none';

        // Function to check if profile photo is selected
        function isProfilePhotoSelected() {
            return inputImage.files && inputImage.files.length > 0;
        }

        // Handle image selection
        inputImage.addEventListener('change', function(event) {
            const files = event.target.files;
            if (files && files.length > 0) {
                const file = files[0];

                // Check file size (limit: 2MB)
                if (file.size > 2 * 1024 * 1024) { // 2MB in bytes
                    fileSizeError.style.display = 'block';
                    fileSizeError.textContent = 'File size exceeds 2MB. Please upload a smaller image.';
                    inputImage.value = ''; // Clear the input
                    image.style.display = 'none';
                    cropButton.style.display = 'none'; // Hide Crop Image button if the file size exceeds
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                    return;
                } else {
                    fileSizeError.style.display = 'none';
                }

                const done = url => {
                    inputImage.value = '';
                    image.src = url;
                    image.style.display = 'block';
                    croppedImagePreview.style.display = 'none'; // Hide previous cropped image preview
                    if (cropper) {
                        cropper.destroy();
                    }
                    cropper = new Cropper(image, {
                        viewMode: 1,
                        autoCropArea: 1,
                        aspectRatio: 1, // Flexible aspect ratio
                        movable: true,
                        zoomable: true,
                        scalable: true,
                        cropBoxResizable: true,
                    });

                    cropButton.style.display = 'block'; // Show the Crop Image button once an image is selected

                    // Hide the required error message if the image is valid
                    profilePhotoError.style.display = 'none';
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

        // Add crop functionality
        cropButton.addEventListener('click', function() {
            if (!cropper) {
                alert('Please upload and select an image first.');
                return;
            }

            const canvas = cropper.getCroppedCanvas();
            if (canvas) {
                const croppedImage = canvas.toDataURL('image/jpeg'); // Get the cropped image as base64

                // Replace the original image preview with the cropped image
                image.src = croppedImage;

                // Store the cropped image in the hidden input for form submission
                croppedImageInput.value = croppedImage;

                // Hide the crop button after cropping
                cropButton.style.display = 'none';

                // Destroy the cropper to prevent further cropping
                cropper.destroy();
                cropper = null;
            }
        });

        // Handle the form navigation logic
        function showStep(step) {
            // Hide all step content and show only the current step
            document.querySelectorAll('.step-content').forEach(function(stepContent) {
                stepContent.style.display = 'none';
            });

            document.getElementById('step-' + step).style.display = 'block';

            // Hide profile photo error if a valid image has been uploaded
            if (isProfilePhotoSelected()) {
                profilePhotoError.style.display = 'none';
            }
        }

        // Ensure that on form submission, profile photo validation is still active
        document.getElementById('fileUpload').addEventListener('submit', function(event) {
            if (!isProfilePhotoSelected()) {
                event.preventDefault(); // Prevent the form from being submitted if profile photo is not selected
                profilePhotoError.style.display = 'block';
                profilePhotoError.textContent = 'Profile photo is required.';
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


        document.addEventListener('DOMContentLoaded', function() {
            const dateOfBirthInput = document.getElementById('dob');

            // Function to format date to "Month Day, Year"
            function formatDateToReadableString(date) {
                const options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                };
                return date.toLocaleDateString('en-US', options);
            }

            // When the user selects a date, format it and show the date picker
            dateOfBirthInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);

                // Check if a valid date was selected
                if (!isNaN(selectedDate.getTime())) {
                    // Set the display to formatted string like "September 11, 2024"
                    this.setAttribute('data-date', this.value); // Save the raw date in a custom attribute
                    this.setAttribute('type', 'text'); // Switch back to text input
                    this.value = formatDateToReadableString(selectedDate); // Display the formatted date
                }
            });

            // When the input is focused, change back to the date type to show the date picker
            dateOfBirthInput.addEventListener('focus', function() {
                const rawDate = this.getAttribute('data-date'); // Get the raw date stored
                this.setAttribute('type', 'date'); // Show the date picker

                // Restore the date picker value if a date was previously selected
                if (rawDate) {
                    this.value = rawDate;
                }
            });

            dateOfBirthInput.addEventListener('blur', function() {
                const selectedDate = new Date(this.value);

                if (!isNaN(selectedDate.getTime())) {
                    this.setAttribute('type',
                        'text');
                    this.value = formatDateToReadableString(selectedDate);
                }
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
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
                    days += new Date(end.getFullYear(), end.getMonth(), 0)
                        .getDate(); // Get the number of days in the previous month
                }

                // Adjust for negative months
                if (months < 0) {
                    years -= 1;
                    months += 12;
                }

                let experienceString = '';
                if (years > 0) experienceString += years + ' year' + (years > 1 ? 's' : '') + ', ';
                if (months > 0) experienceString += months + ' month' + (months > 1 ? 's' : '') + ', ';
                if (days > 0 || (years === 0 && months === 0)) experienceString += days + ' day' + (days > 1 ? 's' :
                    '');

                return experienceString.trim().replace(/,$/, '');
            }

            function updateExperience(event) {
                const input = event.target;
                const row = input.closest('tr');
                const startDate = row.querySelector('input[name="start[]"]').value;
                const endDate = row.querySelector('input[name="end[]"]').value;
                const totalDaysField = row.querySelector('input[name="total[]"]');

                totalDaysField.value = calculateExperience(startDate, endDate);
            }

            // Attach event listener to calculate experience on change of start or end date
            function attachExperienceCalculation(row) {
                const startInput = row.querySelector('input[name="start[]"]');
                const endInput = row.querySelector('input[name="end[]"]');

                startInput.addEventListener('change', updateExperience);
                endInput.addEventListener('change', updateExperience);
            }

            // Initially attach listeners to existing rows
            document.querySelectorAll('table#dynamicTableEx tbody tr').forEach(function(row) {
                attachExperienceCalculation(row);
            });

            // Listen for dynamically added rows (when + button is clicked)
            document.getElementById('addRowEx').addEventListener('click', function() {
                // Assuming new row is added at the end of the table
                const newRow = document.querySelector('table#dynamicTableEx tbody tr:last-child');
                attachExperienceCalculation(newRow);
            });
        });
    </script>

    <script>
        document.getElementById('attachmentInput').addEventListener('change', function() {
            const file = this.files[0];
            const attachmentError = document.getElementById('attachmentError');
            if (file && file.size > 2 * 1024 * 1024) { // 2MB limit
                attachmentError.style.display = 'block';
                attachmentError.textContent = 'File size exceeds 2MB. Please upload a smaller file.';
                this.value = ''; // Clear the file input
            } else {
                attachmentError.style.display = 'none'; // Hide error if valid
            }
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
            document.addEventListener('DOMContentLoaded', function() {
        // Select all GPA/CGPA inputs and attach an event listener
        document.querySelectorAll('input[name="gpa_cgpa[]"]').forEach(function(input) {
            input.addEventListener('input', function() {
                // Automatically restrict value to 5.00 if it exceeds
                if (this.value > 5.00) {
                    this.value = 5.00;
                    alert('GPA/CGPA cannot exceed 5.00.');
                }
            });

            // Also ensure GPA/CGPA cannot be negative or more than 5.00
            input.addEventListener('blur', function() {
                if (this.value > 5.00) {
                    this.value = 5.00; // Set the value to 5.00 if it exceeds
                    alert('GPA/CGPA cannot exceed 5.00.');
                }
                if (this.value < 0) {
                    this.value = 0.00; // Set to minimum of 0.00 if less than 0
                }
            });
        });
    });

    </script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 on page load
            $('.select2').select2({
                width: '100%'
            });

            // Function to clear input fields and handle Select2 properly
            function resetRow(row) {
                // Clear all input and select fields in the cloned row
                row.find('input').val('');
                row.find('select').val('').trigger('change');

                // Destroy and reinitialize Select2 for the cloned row
                row.find('select.select2').select2({
                    width: '100%'
                });
            }

            // Add a new row to the "Academic Information" table
            $('#addRow').on('click', function() {
                let tableBody = $('#dynamicTable tbody');
                let firstRow = tableBody.find('tr:first').clone(); // Clone the first row

                // Reset the cloned row inputs and select fields
                resetRow(firstRow);

                // Remove any existing delete button from the first row
                firstRow.find('.btn-danger').remove();

                // Add a delete button to the cloned row
                let deleteButton = $(
                    '<button type="button" class="btn btn-danger mb-3"><i class="fa fa-minus"></i></button>'
                );
                deleteButton.on('click', function() {
                    $(this).closest('tr')
                        .remove(); // Remove the row when the delete button is clicked
                });
                firstRow.find('td:last-child').html(deleteButton);

                // Append the cloned row to the table body
                tableBody.append(firstRow);

                // Make sure only one instance of Select2 is initialized per row
                firstRow.find('.select2-container').remove(); // Remove existing Select2 containers
                firstRow.find('select.select2').select2({
                    width: '100%'
                }); // Reinitialize Select2
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function calculateExperience(startDate, endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);

                if (isNaN(start.getTime()) || isNaN(end.getTime())) {
                    return ''; // If either date is invalid, return an empty string
                }

                let years = end.getFullYear() - start.getFullYear();
                let months = end.getMonth() - start.getMonth();
                let days = end.getDate() - start.getDate();

                // Adjust for negative days
                if (days < 0) {
                    months -= 1;
                    days += new Date(end.getFullYear(), end.getMonth(), 0)
                        .getDate(); // Get the number of days in the previous month
                }

                // Adjust for negative months
                if (months < 0) {
                    years -= 1;
                    months += 12;
                }

                let experienceString = '';
                if (years > 0) experienceString += years + ' year' + (years > 1 ? 's' : '') + ', ';
                if (months > 0) experienceString += months + ' month' + (months > 1 ? 's' : '') + ', ';
                if (days > 0 || (years === 0 && months === 0)) experienceString += days + ' day' + (days > 1 ? 's' :
                    '');

                return experienceString.trim().replace(/,$/, '');
            }

            function updateExperience(event) {
                const input = event.target;
                const row = input.closest('tr');
                const startDate = row.querySelector('input[name="start[]"]').value;
                const endDate = row.querySelector('input[name="end[]"]').value;
                const totalDaysField = row.querySelector('input[name="total[]"]');

                // If start date is greater than end date, show an error
                if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                    alert('Start date cannot be greater than end date.');
                    totalDaysField.value = '';
                    return;
                }

                // Calculate and update experience
                totalDaysField.value = calculateExperience(startDate, endDate);
            }

            // Attach event listener to calculate experience on change of start or end date
            function attachExperienceCalculation(row) {
                const startInput = row.querySelector('input[name="start[]"]');
                const endInput = row.querySelector('input[name="end[]"]');

                startInput.addEventListener('change', updateExperience);
                endInput.addEventListener('change', updateExperience);
            }

            // Function to add a remove button for dynamically created rows
            function addRemoveButton(row) {
                const lastCell = row.querySelector('td:last-child');
                lastCell.innerHTML = ''; // Clear the content

                // Create remove button
                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'btn btn-danger';
                removeButton.innerHTML = '<i class="fa fa-minus"></i>';

                // Remove row when the remove button is clicked
                removeButton.addEventListener('click', function() {
                    row.remove();
                });

                lastCell.appendChild(removeButton); // Append the remove button to the last cell
            }

            // Add new row with experience calculation and validation for dynamic rows
            function addNewRow() {
                let tableBody = document.querySelector('#dynamicTableEx tbody');
                let firstRow = tableBody.querySelector('tr:first-child').cloneNode(true);

                // Clear input values in the cloned row
                firstRow.querySelectorAll('input').forEach(input => input.value = '');

                // Attach event listeners for the new row
                attachExperienceCalculation(firstRow);

                // Add remove button to the new row (but not to the first row)
                addRemoveButton(firstRow);

                // Add the new row to the table
                tableBody.appendChild(firstRow);
            }

            // Initially attach listeners to the first (fixed) row
            document.querySelectorAll('table#dynamicTableEx tbody tr:first-child').forEach(function(row) {
                attachExperienceCalculation(row);
                // No remove button for the first row (it's fixed)
            });

            // Listen for the "Add Row" button click and add a new row
            document.getElementById('addRowEx').addEventListener('click', function() {
                addNewRow();
            });
        });
    </script>





    <script>
        // Function to set the status and submit the form
        function submitForm(statusValue) {
            setStatus(statusValue);
            document.getElementById('fileUpload').submit();
        }

        function setStatus(statusValue) {
            document.getElementById('status').value = statusValue;
            document.getElementById('fileUpload').submit();
        }


        function setStatus(statusValue) {
            document.getElementById('status').value = statusValue;
            document.getElementById('fileUpload').submit();
        }



        // Set the status value in the hidden input field
        function setStatus(statusValue) {
            document.getElementById('status').value = statusValue;
        }

        // You can still use this for form validation or any additional logic
        document.getElementById('fileUpload').addEventListener('submit', function(event) {
            let statusValue = document.getElementById('status').value;

            // You can add custom validation or additional logic here if needed
            if (statusValue === '-1') {
                // Save as draft logic (if any custom logic required)
                console.log("Saving as Draft...");
            } else if (statusValue === '1') {
                // Normal submission logic
                console.log("Submitting the form...");
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function addNewTrainingRow() {
                const tableBody = document.querySelector('#dynamicTableTr tbody');
                const firstRow = tableBody.querySelector('tr:first-child').cloneNode(true); // Clone the first row

                firstRow.querySelectorAll('input').forEach(input => input.value = '');

                const lastCell = firstRow.querySelector('td:last-child');
                lastCell.innerHTML = ''; // Clear the content of the last cell

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'btn btn-danger mb-3';
                removeButton.innerHTML = '<i class="fa fa-minus"></i>';

                //event listener to remove the row when the "-" button is clicked
                removeButton.addEventListener('click', function() {
                    firstRow.remove();
                });

                lastCell.appendChild(removeButton);

                //new row to the table body
                tableBody.appendChild(firstRow);
            }

            document.getElementById('addRowTr').addEventListener('click', function() {
                addNewTrainingRow();
            });
        });
    </script>
@endsection
