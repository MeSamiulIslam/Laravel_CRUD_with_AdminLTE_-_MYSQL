<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CvApps;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CVController extends Controller
{
    public function create()
    {
        $list = Category::where('status', 1)->pluck('category_name', 'id');
        return view('cv', compact('list'));
    }

    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'father_name' => 'required|string|max:50',
            'mother_name' => 'required|string|max:50',
            'phone_number' => 'required|digits_between:10,11',
            'email' => 'required|email|max:50',
            'cropped_image' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Always required for new submissions
            'attachment' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Store CV data
            $cvApp = new CvApps();
            $cvApp->first_name = $request->first_name;
            $cvApp->last_name = $request->last_name;
            $cvApp->father_name = $request->father_name;
            $cvApp->mother_name = $request->mother_name;
            $cvApp->phone_number = $request->phone_number;
            $cvApp->email = $request->email;
            $cvApp->dob = date('Y-m-d', strtotime($request->dob));
            $cvApp->present_address = $request->present_address;
            $cvApp->permanent_address = $request->permanent_address;
            $cvApp->nationality = $request->nationality;
            $cvApp->gender = $request->gender;
            $cvApp->identity_type = $request->identity_type;
            $cvApp->nid = $request->nid;
            $cvApp->bid = $request->bid;
            $cvApp->status = $request->input('status'); // -1 for draft, 1 for submit

            // Handle profile photo upload
            if ($request->has('cropped_image')) {
                $croppedImageData = str_replace(['data:image/jpeg;base64,', ' '], ['', '+'], $request->cropped_image);
                $imageData = base64_decode($croppedImageData);
                $fileName = 'cropped_' . time() . '.jpg';
                Storage::disk('public')->put('profile_photos/' . $fileName, $imageData);
                $cvApp->profile_photo = $fileName;
            } elseif ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/profile_photos', $fileName);
                $cvApp->profile_photo = $fileName;
            }

            // Handle attachment file upload (PDF)
            if ($request->hasFile('attachment')) {
                $attachmentFile = $request->file('attachment');
                $attachmentName = 'attach_' . time() . '.' . $attachmentFile->getClientOriginalExtension();
                $attachmentPath = $attachmentFile->storeAs('attachments', $attachmentName, 'public');
                $cvApp->attachment = $attachmentPath;
            }

            $cvApp->save();

            $this->storeEducationData($cvApp->id, $request);
            $this->storeExperienceData($cvApp->id, $request);
            $this->storeTrainingData($cvApp->id, $request);

            DB::commit();
            Session::flash('success', 'CV submitted successfully!');
            return redirect()->route('show_data');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting CV: ' . $e->getMessage());
            Session::flash('error', 'An error occurred while submitting your CV.');
            return redirect()->back()->withInput();
        }
    }

    private function storeEducationData($appId, $request)
    {
        $education_level = $request->education_level;
        $institution = $request->institution;
        $group_program = $request->group_program;
        $passing_year = $request->passing_year;
        $gpa_cgpa = $request->gpa_cgpa;

        for ($i = 0; $i < count($education_level); $i++) {
            $data1 = new Education();
            $data1->app_id = $appId;
            $data1->education_level = $education_level[$i];
            $data1->department = $group_program[$i];
            $data1->educational_institute_name = $institution[$i];
            $data1->pass_year = $passing_year[$i];
            $data1->cgpa = $gpa_cgpa[$i];
            $data1->save();
        }
    }

    private function storeExperienceData($appId, $request)
    {
        $company_name = $request->company_name;
        $designation = $request->designation;
        $company_location = $request->company_location;
        $start_date = $request->start;
        $end_date = $request->end;
        $total_year = $request->total;

        for ($i = 0; $i < count($company_name); $i++) {
            $data2 = new Experience();
            $data2->app_id = $appId;
            $data2->company_name = $company_name[$i];
            $data2->designation = $designation[$i];
            $data2->company_location = $company_location[$i];
            $data2->start_date = $start_date[$i];
            $data2->end_date = $end_date[$i];
            $data2->total_experience = $total_year[$i];
            $data2->save();
        }
    }

    private function storeTrainingData($appId, $request)
    {
        $training_title = $request->title;
        $institute_name = $request->institute_name;
        $duration = $request->duration;
        $training_year = $request->year;
        $training_location = $request->training_location;

        for ($i = 0; $i < count($training_title); $i++) {
            $data3 = new Training();
            $data3->app_id = $appId;
            $data3->title = $training_title[$i];
            $data3->institute_name = $institute_name[$i];
            $data3->duration = $duration[$i];
            $data3->year = $training_year[$i];
            $data3->training_location = $training_location[$i];
            $data3->save();
        }
    }

    public function list()
    {
        $data =
            CvApps::paginate(100); // 10 records per page;
        $list = Category::where('status', 1)->pluck('category_name', 'id');
        return view('show_data', compact('data', 'list'));
    }

    public function view($id)
    {
        $data = CvApps::find($id);
        $educations = Education::where('app_id', $id)->get();
        $experiences = Experience::where('app_id', $id)->get();
        $trainings = Training::where('app_id', $id)->get();
        $cv_apps = CvApps::where('id', $data->id)->first();

        return view('show_all', compact('data', 'educations', 'experiences', 'trainings', 'cv_apps'));
    }

    public function edit($id)
    {
        $data = CvApps::find($id);
        $educations = Education::where('app_id', $id)->get();
        $experiences = Experience::where('app_id', $id)->get();
        $trainings = Training::where('app_id', $id)->get();
        $list = Category::where('status', 1)->pluck('category_name', 'id');
        return view('edit_cv', compact('data', 'educations', 'experiences', 'trainings', 'list'));
    }

    public function update(Request $request, $id)
    {
        // Validate the form data
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'father_name' => 'required|string|max:50',
            'mother_name' => 'required|string|max:50',
            'phone_number' => 'required|digits_between:10,15',
            'email' => 'required|email|max:50',
            'dob' => 'required|date',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'attachment' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Find the CV record
            $data = CvApps::findOrFail($id);

            // Update personal information
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->father_name = $request->father_name;
            $data->mother_name = $request->mother_name;
            $data->phone_number = $request->phone_number;
            $data->email = $request->email;
            $data->dob = date('Y-m-d', strtotime($request->dob));
            $data->present_address = $request->present_address;
            $data->permanent_address = $request->permanent_address;
            $data->nationality = $request->nationality;
            $data->gender = $request->gender;
            $data->identity_type = $request->identity_type;
            $data->nid = $request->nid;
            $data->bid = $request->bid;
            $data->status = $request->input('status'); // -1 for draft, 1 for submit


            // Handle the cropped image
            if ($request->has('cropped_image') && !empty($request->cropped_image)) {
                $croppedImageData = str_replace(['data:image/jpeg;base64,', ' '], ['', '+'], $request->cropped_image);
                $imageData = base64_decode($croppedImageData);
                $fileName = 'cropped_' . time() . '.jpg';
                Storage::disk('public')->put('profile_photos/' . $fileName, $imageData);

                // Remove old photo if exists
                if (!empty($data->profile_photo) && Storage::disk('public')->exists('profile_photos/' . $data->profile_photo)) {
                    Storage::disk('public')->delete('profile_photos/' . $data->profile_photo);
                }

                $data->profile_photo = $fileName;
            }
            // Handle normal file upload (non-cropped)
            elseif ($request->hasFile('profile_photo')) {
                if ($request->file('profile_photo')->isValid()) {
                    $fileName = time() . '.' . $request->profile_photo->getClientOriginalExtension();
                    $request->profile_photo->storeAs('public/profile_photos', $fileName);

                    // Remove old photo if exists
                    if (!empty($data->profile_photo) && Storage::disk('public')->exists('profile_photos/' . $data->profile_photo)) {
                        Storage::disk('public')->delete('profile_photos/' . $data->profile_photo);
                    }

                    $data->profile_photo = $fileName;
                }
            }

            // Handle attachment update
            if ($request->hasFile('attachment')) {
                if ($data->attachment && Storage::exists('public/' . $data->attachment)) {
                    Storage::delete('public/' . $data->attachment);
                }

                $attachmentFile = $request->file('attachment');
                $attachmentName = 'attach_' . time() . '.' . $attachmentFile->getClientOriginalExtension();
                $attachmentPath = $attachmentFile->storeAs('attachments', $attachmentName, 'public');
                $data->attachment = $attachmentPath;
            }

            // Save updated CV information
            $data->save();

            // Update related records
            $this->updateEducationData($id, $request);
            $this->updateExperienceData($id, $request);
            $this->updateTrainingData($id, $request);

            DB::commit();
            Session::flash('success', 'Data updated successfully!');
            return redirect()->route('show_data');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating CV: ' . $e->getMessage() . ' at line ' . $e->getLine());
            Session::flash('error', 'An error occurred while updating your data.');
            return redirect()->back()->withInput();
        }
    }





    private function updateEducationData($appId, $request)
    {
        // Delete old records before adding new ones
        Education::where('app_id', $appId)->delete();

        // Store new education data
        $this->storeEducationData($appId, $request);
    }

    private function updateExperienceData($appId, $request)
    {
        // Delete old records before adding new ones
        Experience::where('app_id', $appId)->delete();

        // Store new experience data
        $this->storeExperienceData($appId, $request);
    }

    private function updateTrainingData($appId, $request)
    {
        // Delete old records before adding new ones
        Training::where('app_id', $appId)->delete();

        // Store new training data
        $this->storeTrainingData($appId, $request);
    }


    // Method to view the PDF of the CV
    public function viewPdf($id)
    {
        $cv_data = CvApps::findOrFail($id);
        $educations = Education::where('app_id', $id)->get();
        $experiences = Experience::where('app_id', $id)->get();
        $trainings = Training::where('app_id', $id)->get();

        $data = [
            'cv_data' => [
                'profile_photo' => $cv_data->profile_photo,
                'first_name' => $cv_data->first_name,
                'last_name' => $cv_data->last_name,
                'father_name' => $cv_data->father_name,
                'mother_name' => $cv_data->mother_name,
                'phone_number' => $cv_data->phone_number,
                'email' => $cv_data->email,
                'dob' => $cv_data->dob,
                'present_address' => $cv_data->present_address,
                'permanent_address' => $cv_data->permanent_address,
                'nationality' => $cv_data->nationality,
                'gender' => $cv_data->gender,
                'option' => $cv_data->identity_type,
                'nid' => $cv_data->nid,
                'bid' => $cv_data->bid,
                'education' => $educations->toArray(),
                'experience' => $experiences->toArray(),
                'training' => $trainings->toArray(),
            ],
        ];

        $pdf = PDF::loadView('pdf', $data);
        return $pdf->stream('applicant_cv.pdf'); // Stream the PDF for viewing in the browser
    }

    // Method to download the PDF of the CV
    public function downloadPdf($id)
    {
        $cv_data = CvApps::findOrFail($id);
        $educations = Education::where('app_id', $id)->get();
        $experiences = Experience::where('app_id', $id)->get();
        $trainings = Training::where('app_id', $id)->get();

        $data = [
            'cv_data' => [
                'first_name' => $cv_data->first_name,
                'last_name' => $cv_data->last_name,
                'father_name' => $cv_data->father_name,
                'mother_name' => $cv_data->mother_name,
                'phone_number' => $cv_data->phone_number,
                'email' => $cv_data->email,
                'dob' => $cv_data->dob,
                'present_address' => $cv_data->present_address,
                'permanent_address' => $cv_data->permanent_address,
                'nationality' => $cv_data->nationality,
                'gender' => $cv_data->gender,
                'option' => $cv_data->identity_type,
                'nid' => $cv_data->nid,
                'bid' => $cv_data->bid,
                'education' => $educations->toArray(),
                'experience' => $experiences->toArray(),
                'training' => $trainings->toArray(),
                'profile_photo' => $cv_data->profile_photo,
            ],
        ];

        $pdf = PDF::loadView('pdf', $data);
        return $pdf->download('applicant_cv.pdf');
    }

    public function updateCV(Request $request, $id)
    {
        // Validate the form data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'phone_number' => 'required|numeric|digits_between:10,15',
            'email' => 'required|email|max:255',
            'dob' => 'required|date|before:today',
            'present_address' => 'required|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'option' => 'required|in:nid,bid',
            'attachment' => 'nullable|file|mimes:pdf|max:2048', // For the PDF attachment
            'education_level.*' => 'nullable|string|max:255',
            'institution.*' => 'nullable|string|max:255',
            'group_program.*' => 'nullable|string|max:255',
            'passing_year.*' => 'nullable|numeric|min:1900|max:' . date('Y'),
            'gpa_cgpa.*' => 'nullable|numeric|between:0,5',
            'company_name.*' => 'nullable|string|max:255',
            'designation.*' => 'nullable|string|max:255',
            'company_location.*' => 'nullable|string|max:255',
            'start.*' => 'nullable|date',
            'end.*' => 'nullable|date|after_or_equal:start.*',
            'title.*' => 'nullable|string|max:255',
            'institute_name.*' => 'nullable|string|max:255',
            'duration.*' => 'nullable|string|max:255',
            'year.*' => 'nullable|numeric|min:1900|max:' . date('Y'),
            'training_location.*' => 'nullable|string|max:255',
        ]);

        // Find the CV record
        $cv = CV::findOrFail($id);

        // Update the personal information
        $cv->first_name = $request->input('first_name');
        $cv->last_name = $request->input('last_name');
        $cv->father_name = $request->input('father_name');
        $cv->mother_name = $request->input('mother_name');
        $cv->phone_number = $request->input('phone_number');
        $cv->email = $request->input('email');
        $cv->dob = $request->input('dob');
        $cv->present_address = $request->input('present_address');
        $cv->permanent_address = $request->input('permanent_address');
        $cv->nationality = $request->input('nationality');
        $cv->gender = $request->input('gender');
        $cv->identity_type = $request->input('option');

        // Handle file attachment
        if ($request->hasFile('attachment')) {
            // Delete the old attachment if it exists
            if ($cv->attachment && Storage::exists($cv->attachment)) {
                Storage::delete($cv->attachment);
            }
            // Store the new attachment
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
            $cv->attachment = $attachmentPath;
        }

        // Update education data
        if ($request->has('education_level')) {
            foreach ($request->input('education_level') as $index => $level) {
                if ($level) {
                    $education = $cv->educations()->updateOrCreate(
                        ['id' => $request->input('education_id')[$index] ?? null],
                        [
                            'education_level' => $level,
                            'educational_institute_name' => $request->input('institution')[$index],
                            'department' => $request->input('group_program')[$index],
                            'pass_year' => $request->input('passing_year')[$index],
                            'cgpa' => $request->input('gpa_cgpa')[$index],
                        ]
                    );
                }
            }
        }

        // Update experience data
        if ($request->has('company_name')) {
            foreach ($request->input('company_name') as $index => $company) {
                if ($company) {
                    $experience = $cv->experiences()->updateOrCreate(
                        ['id' => $request->input('experience_id')[$index] ?? null],
                        [
                            'company_name' => $company,
                            'designation' => $request->input('designation')[$index],
                            'company_location' => $request->input('company_location')[$index],
                            'start_date' => $request->input('start')[$index],
                            'end_date' => $request->input('end')[$index],
                            'total_experience' => $request->input('total')[$index], // assuming total experience is calculated on the frontend
                        ]
                    );
                }
            }
        }

        // Update training data
        if ($request->has('title')) {
            foreach ($request->input('title') as $index => $title) {
                if ($title) {
                    $training = $cv->trainings()->updateOrCreate(
                        ['id' => $request->input('training_id')[$index] ?? null],
                        [
                            'title' => $title,
                            'institute_name' => $request->input('institute_name')[$index],
                            'duration' => $request->input('duration')[$index],
                            'year' => $request->input('year')[$index],
                            'training_location' => $request->input('training_location')[$index],
                        ]
                    );
                }
            }
        }

        // Save the updated CV
        $cv->save();

        // Redirect back with success message
        return redirect()->route('edit_cv', $cv->id)->with('success', 'CV updated successfully.');
    }
}
