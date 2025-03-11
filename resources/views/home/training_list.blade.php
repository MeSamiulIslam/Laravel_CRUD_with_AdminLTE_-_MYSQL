@extends('master')

@section('title') Training List
@endsection

@section('header-resources')

@endsection

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><b>Training List</b></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('add_training') }}" class="btn btn-primary">Add New</a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Training Title</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($data->reverse() as $personal)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$personal->name}}</td>
                        <td>{{$personal->email}}</td>
                        <td>{{ $list[$personal->category_id] ?? '' }}</td>
                        @if ($personal->status === -1)
                        <td><a href="{{ route('edit_cv', ['id' => $personal->id]) }}" class="btn btn-primary">Edit</a></td>
                        @else
                        <td><a href="{{ route('show_all', ['id' => $personal->id]) }}" class="btn btn-success">View</a></td>
                        @endif
                    </tr>
                    <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
</div>

@endsection


@section('footer-resources')

@endsection