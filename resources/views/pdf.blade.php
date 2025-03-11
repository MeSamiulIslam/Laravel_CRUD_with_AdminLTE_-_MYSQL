<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1,
        h3 {
            color: blue;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .section-header {
            background-color: #f4f4f4;
            padding: 10px;
        }

        .personal-info {
            margin-bottom: 20px;
        }

        .profile-photo img {
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <h1>Applicant CV</h1>

    <!-- Profile Photo Section -->
    <div class="profile-photo">
        @if (isset($cv_data['profile_photo']) && !empty($cv_data['profile_photo']))
            <img src="{{ public_path('storage/profile_photos/' . $cv_data['profile_photo']) }}" alt="Profile Photo"
                class="img-thumbnail">
        @else
            <p>No Profile Photo Available</p>
        @endif
    </div>




    <!-- Personal Information Section -->
    <h3 class="section-header">Personal Information</h3>
    <div class="personal-info">
        <p><strong>Full Name:</strong> {{ $cv_data['first_name'] . ' ' . $cv_data['last_name'] }}</p>
        <p><strong>Father's Name:</strong> {{ $cv_data['father_name'] }}</p>
        <p><strong>Mother's Name:</strong> {{ $cv_data['mother_name'] }}</p>
        <p><strong>Phone Number:</strong> {{ $cv_data['phone_number'] }}</p>
        <p><strong>Email:</strong> {{ $cv_data['email'] }}</p>
        <p><strong>Date of Birth:</strong> {{ \Carbon\Carbon::parse($cv_data['dob'])->format('d F, Y') }}</p>
        <p><strong>Present Address:</strong> {{ $cv_data['present_address'] }}</p>
        <p><strong>Permanent Address:</strong> {{ $cv_data['permanent_address'] }}</p>
        <p><strong>Nationality:</strong> {{ $cv_data['nationality'] }}</p>
        <p><strong>Gender:</strong> {{ $cv_data['gender'] }}</p>
        <p><strong>Identity Type:</strong> {{ $cv_data['option'] == 'nid' ? 'NID' : 'BID' }}</p>
        @if ($cv_data['option'] == 'nid')
            <p><strong>NID Number:</strong> {{ $cv_data['nid'] }}</p>
        @else
            <p><strong>BID Number:</strong> {{ $cv_data['bid'] }}</p>
        @endif
    </div>

    <!-- Education Section -->
    <h3 class="section-header">Academic Information</h3>
    <table>
        <thead>
            <tr>
                <th>Education Level</th>
                <th>Institution</th>
                <th>Group/Program</th>
                <th>Passing Year</th>
                <th>GPA/CGPA</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cv_data['education'] as $education)
                <tr>
                    <td>{{ $education['education_level'] }}</td>
                    <td>{{ $education['department'] }}</td>
                    <td>{{ $education['educational_institute_name'] }}</td>
                    <td>{{ $education['pass_year'] }}</td>
                    <td>{{ $education['cgpa'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Experience Section -->
    <h3 class="section-header">Experience</h3>
    <table>
        <thead>
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
            @foreach ($cv_data['experience'] as $experience)
                <tr>
                    <td>{{ $experience['company_name'] }}</td>
                    <td>{{ $experience['designation'] }}</td>
                    <td>{{ $experience['company_location'] }}</td>
                    <td>{{ $experience['start_date'] }}</td>
                    <td>{{ $experience['end_date'] }}</td>
                    <td>{{ $experience['total_experience'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Training Section -->
    <h3 class="section-header">Training and Certifications</h3>
    <table>
        <thead>
            <tr>
                <th>Training Title</th>
                <th>Institute Name</th>
                <th>Duration</th>
                <th>Training Year</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cv_data['training'] as $training)
                <tr>
                    <td>{{ $training['title'] }}</td>
                    <td>{{ $training['institute_name'] }}</td>
                    <td>{{ $training['duration'] }}</td>
                    <td>{{ $training['year'] }}</td>
                    <td>{{ $training['training_location'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
