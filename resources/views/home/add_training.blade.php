@extends('master')

@section('title') Training Form
@endsection

@section('header-resources')
  <link rel="stylesheet" href="https://unpkg.com/cropperjs/dist/cropper.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.11/build/css/intlTelInput.css">
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

@endsection

@section('content')

<div class="wrapper">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Training Form</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Training Form</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
             <!-- form start -->
            {!! Form::open(['url' => 'store-training', 'method' => 'post', 'enctype' => 'multipart/form-data', 'files' => 'true']) !!}
              @csrf
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Training Information</h3>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    {!! Form::label('training_photo', 'Training Photo', ['class' => 'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::file('training_photo', ['class' => 'form-control', 'id' => 'training_photo']) !!}
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    {!! Form::label('training_title', 'Training Title', ['class' => 'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('training_title', old('training_title'), ['class' => 'form-control', 'id' => 'training_title', 'placeholder' => 'Enter Training Title']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    {!! Form::label('course_venue', 'Course Venue', ['class' => 'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('course_venue', old('course_venue'), ['class' => 'form-control', 'id' => 'course_venue', 'placeholder' => 'Enter Course Venue']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    {!! Form::label('class_start', 'Class Start', ['class' => 'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::date('class_start', old('class_start'), ['class' => 'form-control', 'id' => 'class_start', 'placeholder' => 'Enter Class Start Date']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    {!! Form::label('qualification', 'Necessary Qualification', ['class' => 'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::textarea('qualification', null, ['class' => 'form-control summernote']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    {!! Form::label('course_goal', 'Course Goal', ['class' => 'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('course_goal', old('course_goal'), ['class' => 'form-control', 'id' => 'course_goal', 'placeholder' => 'Enter Course Goal']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    {!! Form::label('description', 'Course Description', ['class' => 'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::textarea('description', null, ['class' => 'form-control summernote']) !!}
                    </div>
                  </div>
                </div>
              </div>

              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Training Sessions</h3>
                </div>
                <div class="container mt-4">
                  <table id="dynamicTable" class="table table-bordered">
                      <thead>
                          <tr>
                              <th>Start Time</th>
                              <th>End Time</th>
                              <th>Day</th>
                              <th>Seats</th>
                              <th>Training Period</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td>{!! Form::time('edu_level[]', null, ['class' => 'form-control', 'placeholder' => '']) !!}</td>
                              <td>{!! Form::time('department[]', null, ['class' => 'form-control', 'placeholder' => '']) !!}</td>
                              <td>{!! Form::text('edu_inst_name[]', null, ['class' => 'form-control', 'placeholder' => '']) !!}</td>
                              <td>{!! Form::number('pass_year[]', null, ['class' => 'form-control', 'min' => 1900, 'max' => 2100, 'placeholder' => '']) !!}</td>
                              <td>{!! Form::number('cgpa[]', null, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => '']) !!}</td>
                              <td>{!! Form::button('<i class="fa fa-plus"></i>', ['type' => 'button', 'id' => 'addRow', 'class' => 'btn btn-primary mb-3']) !!}</td>
                          </tr>
                      </tbody>
                  </table>
                </div>
              </div>
              
              <div class="d-flex justify-content-end" style="margin-bottom: 10px;">
                  {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
  <script>
        $(function () {
          $('.summernote').summernote({
              height:100
          })
        })

        $(document).ready(function () {
            $("#addRow").click(function () {
                var newRow = '<tr>' +
                    '<td><input type="text" name="edu_level[]" class="form-control" placeholder="Enter your Education Level"></td>' +
                    '<td><input type="text" name="department[]" class="form-control" placeholder="Enter your Department"></td>' +
                    '<td><input type="text" name="edu_inst_name[]" class="form-control" placeholder="Enter your Institute Name"></td>' +
                    '<td><input type="number" name="pass_year[]" class="form-control" placeholder="Enter your Passing Year"></td>' +
                    '<td><input type="number" name="cgpa[]" class="form-control" placeholder="Enter your CGPA" step="0.01"></td>' +
                    '<td><button type="button" class="btn btn-danger removeRow"><i class="fa fa-minus"></button></td>' +
                    '</tr>';
                $("#dynamicTable tbody").append(newRow);
            });

            $("#dynamicTable").on('click', '.removeRow', function () {
                $(this).closest('tr').remove();
            });
        });

        $(document).ready(function () {
            $("#addRowEx").click(function () {
                var newRow = '<tr>' +
                    '<td><input type="text" name="com_name[]" class="form-control" placeholder="Enter your Company Name"></td>' +
                    '<td><input type="text" name="designation[]" class="form-control" placeholder="Enter your Designation"></td>' +
                    '<td><input type="text" name="com_location[]" class="form-control" placeholder="Enter your Company Location"></td>' +
                    '<td><input type="date" name="start[]" id="start_date" class="form-control"></td>' +
                    '<td><input type="date" name="end[]" id="end_date" class="form-control"></td>' +
                    '<td><input type="text" name="total[]" id="total_days" class="form-control"></td>' +
                    '<td><button type="button" class="btn btn-danger removeRow"><i class="fa fa-minus"></button></td>' +
                    '</tr>';
                $("#dynamicTableEx tbody").append(newRow);
            });

            $("#dynamicTableEx").on('click', '.removeRow', function () {
                $(this).closest('tr').remove();
            });

            $("#dynamicTableEx").on('change', '#start_date, #end_date', function () {
                var row = $(this).closest('tr');
                var startDate = row.find('#start_date').val();
                var endDate = row.find('#end_date').val();
                if (startDate && endDate) {
                    var age = calculateDate(startDate, endDate);
                    row.find('#total_days').val(age);
                }
            });
        });

        $(document).ready(function () {
            $("#addRowTr").click(function () {
                var newRow = '<tr>' +
                    '<td><input type="text" name="title[]" class="form-control" placeholder="Enter Training Title"></td>' +
                    '<td><input type="text" name="tr_inst_name[]" class="form-control" placeholder="Enter Institute Name"></td>' +
                    '<td><input type="text" name="duration[]" class="form-control" placeholder="Enter Training Duration"></td>' +
                    '<td><input type="number" name="year[]" class="form-control" min="1900" max="2100" placeholder="Enter Year"></td>' +
                    '<td><input type="text" name="tr_location[]" class="form-control" placeholder="Enter the Location"></td>' +
                    '<td><button type="button" class="btn btn-danger removeRow"><i class="fa fa-minus"></button></td>' +
                    '</tr>';
                $("#dynamicTableTr tbody").append(newRow);
            });

            $("#dynamicTableTr").on('click', '.removeRow', function () {
                $(this).closest('tr').remove();
            });
        });
   
  </script>

@endsection