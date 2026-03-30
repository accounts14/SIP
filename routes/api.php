<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\RegencyController;
use App\Http\Controllers\Admin\SchoolLevelConroller;
use App\Http\Controllers\Admin\SubdistrictController;
use App\Http\Controllers\Api\AchievementController;
use App\Http\Controllers\Api\AgendaController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ExtracurricularController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\FacilityTypeController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\TestimonyController;
use App\Http\Controllers\Api\RegistrationFormController;
use App\Http\Controllers\Api\StudentRegistrationController;
use App\Http\Controllers\Api\UserCandidateController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserMemberController;
use App\Http\Controllers\Api\ExtracurricularTypeController;
use App\Http\Controllers\Api\PaymentProofController;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (tanpa login)
|--------------------------------------------------------------------------
*/
Route::post('register', RegisterController::class);
Route::post('login/{role}', 'AuthController@login')
    ->where('role', 'superadmin|admin|member')
    ->name('login');

/*
|--------------------------------------------------------------------------
| Public Routes - Pendaftaran Siswa Baru (tanpa login)
| Digunakan oleh form pendaftaran siswa untuk:
| - Menampilkan daftar sekolah
| - Dropdown province, city, district, subdistrict
|--------------------------------------------------------------------------
*/
Route::prefix('public')->group(function () {
    // Sekolah - bisa dicari & difilter tanpa login
    Route::get('schools', [SchoolController::class, 'index']);
    Route::get('schools/{identifier}', [SchoolController::class, 'show'])
        ->where('identifier', '[0-9]+|[a-z0-9-]+');

    // Wilayah - untuk dropdown form pendaftaran
    Route::get('provinces', [ProvinceController::class, 'index']);
    Route::get('provinces/{province}', [ProvinceController::class, 'show']);
    Route::get('cities', [CityController::class, 'index']);
    Route::get('cities/{city}', [CityController::class, 'show']);
    Route::get('districts', [DistrictController::class, 'index']);
    Route::get('districts/{district}', [DistrictController::class, 'show']);
    Route::get('subdistricts', [SubdistrictController::class, 'index']);
    Route::get('subdistricts/{subdistrict}', [SubdistrictController::class, 'show']);

    // School levels - untuk dropdown jenjang sekolah
    Route::get('school-levels', [SchoolLevelConroller::class, 'index']);

    // Formulir pendaftaran per sekolah - untuk step 4 form pendaftaran siswa
    Route::get('registration-form/sch/{sch_id}', [RegistrationFormController::class, 'fromSchool']);
    

    // Submit pendaftaran mandiri siswa - tanpa login
    Route::post('student',      [UserCandidateController::class,      'store']);
    Route::post('registration', [StudentRegistrationController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api'])->prefix('api')->group(function () {

    // Logout & Me - semua role
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', function () {
        return ['user' => new UserResource(Auth::user())];
    });

    /*
    |----------------------------------------------------------------------
    | Wilayah
    | GET    : superadmin, admin:sip, admin:school_admin, admin:school_head
    | CRUD   : admin:sip only
    |----------------------------------------------------------------------
    */
    Route::get('locations/{type}', 'LocationsController@getByType')
        ->where('type', 'province|city|district|subdistrict')
        ->name('locations.get')
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');

    // GET wilayah - semua admin
    Route::get('provinces', [ProvinceController::class, 'index'])->middleware('role:superadmin,admin:sip');
    Route::get('provinces/{province}', [ProvinceController::class, 'show'])->middleware('role:superadmin,admin:sip');
    Route::get('regencies', [RegencyController::class, 'index'])->middleware('role:superadmin,admin:sip');
    Route::get('regencies/{regency}', [RegencyController::class, 'show'])->middleware('role:superadmin,admin:sip');
    Route::get('cities', [CityController::class, 'index'])->middleware('role:superadmin,admin:sip');
    Route::get('cities/{city}', [CityController::class, 'show'])->middleware('role:superadmin,admin:sip');
    Route::get('districts', [DistrictController::class, 'index'])->middleware('role:superadmin,admin:sip');
    Route::get('districts/{district}', [DistrictController::class, 'show'])->middleware('role:superadmin,admin:sip');
    Route::get('subdistricts', [SubdistrictController::class, 'index'])->middleware('role:superadmin,admin:sip');
    Route::get('subdistricts/{subdistrict}', [SubdistrictController::class, 'show'])->middleware('role:superadmin,admin:sip');

    // POST/PUT/DELETE wilayah - admin:sip only
    Route::post('provinces', [ProvinceController::class, 'store'])->middleware('role:admin:sip');
    Route::put('provinces/{province}', [ProvinceController::class, 'update'])->middleware('role:admin:sip');
    Route::delete('provinces/{province}', [ProvinceController::class, 'destroy'])->middleware('role:admin:sip');
    Route::post('regencies', [RegencyController::class, 'store'])->middleware('role:admin:sip');
    Route::put('regencies/{regency}', [RegencyController::class, 'update'])->middleware('role:admin:sip');
    Route::delete('regencies/{regency}', [RegencyController::class, 'destroy'])->middleware('role:admin:sip');
    Route::post('cities', [CityController::class, 'store'])->middleware('role:admin:sip');
    Route::put('cities/{city}', [CityController::class, 'update'])->middleware('role:admin:sip');
    Route::delete('cities/{city}', [CityController::class, 'destroy'])->middleware('role:admin:sip');
    Route::post('districts', [DistrictController::class, 'store'])->middleware('role:admin:sip');
    Route::put('districts/{district}', [DistrictController::class, 'update'])->middleware('role:admin:sip');
    Route::delete('districts/{district}', [DistrictController::class, 'destroy'])->middleware('role:admin:sip');
    Route::post('subdistricts', [SubdistrictController::class, 'store'])->middleware('role:admin:sip');
    Route::put('subdistricts/{subdistrict}', [SubdistrictController::class, 'update'])->middleware('role:admin:sip');
    Route::delete('subdistricts/{subdistrict}', [SubdistrictController::class, 'destroy'])->middleware('role:admin:sip');

    /*
    |----------------------------------------------------------------------
    | School Levels
    | GET  : superadmin, admin:sip
    | CRUD : admin:sip only
    |----------------------------------------------------------------------
    */
    Route::get('school-levels', [SchoolLevelConroller::class, 'index'])->middleware('role:superadmin,admin:sip');
    Route::get('school-levels/{school_level}', [SchoolLevelConroller::class, 'show'])->middleware('role:superadmin,admin:sip');
    Route::post('school-levels', [SchoolLevelConroller::class, 'store'])->middleware('role:admin:sip');
    Route::put('school-levels/{school_level}', [SchoolLevelConroller::class, 'update'])->middleware('role:admin:sip');
    Route::delete('school-levels/{school_level}', [SchoolLevelConroller::class, 'destroy'])->middleware('role:admin:sip');

    /*
    |----------------------------------------------------------------------
    | Schools
    | GET    : semua role
    | CRUD   : admin:sip (semua sekolah), admin:school_admin (sekolahnya sendiri)
    | DELETE : admin:sip only
    |----------------------------------------------------------------------
    */
    Route::get('schools', [SchoolController::class, 'index'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::get('schools/{identifier}', [SchoolController::class, 'show'])
        ->where('identifier', '[0-9]+|[a-z0-9-]+')
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::post('schools', [SchoolController::class, 'store'])
        ->middleware('role:admin:sip');
    Route::put('schools/{id}', [SchoolController::class, 'update'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);
    Route::delete('schools/{id}', [SchoolController::class, 'destroy'])
        ->middleware('role:admin:sip');
    Route::put('schools/generate-user/{id}', [SchoolController::class, 'genUser'])
        ->middleware('role:admin:sip');
    Route::post('schools/upload/{type}', [SchoolController::class, 'uploadImg'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);

    /*
    |----------------------------------------------------------------------
    | Data Sekolah (Teachers, Facility, Gallery, dll)
    | GET    : superadmin, admin:sip, admin:school_admin, admin:school_head, member
    | CRUD   : admin:sip, admin:school_admin (sekolahnya sendiri)
    |----------------------------------------------------------------------
    */

    // Teachers
    Route::get('schools/teachers/{schID}', [TeacherController::class, 'bySchool'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::get('teachers', [TeacherController::class, 'index'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::get('teachers/{teacher}', [TeacherController::class, 'show'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::post('teachers', [TeacherController::class, 'store'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);
    Route::put('teachers/{teacher}', [TeacherController::class, 'update'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);
    Route::delete('teachers/{teacher}', [TeacherController::class, 'destroy'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);

    // Facility
    Route::get('schools/facility/{schID}', [FacilityController::class, 'bySchool'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::get('facility', [FacilityController::class, 'index'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::get('facility/{facility}', [FacilityController::class, 'show'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::post('facility', [FacilityController::class, 'store'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);
    Route::put('facility/{facility}', [FacilityController::class, 'update'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);
    Route::delete('facility/{facility}', [FacilityController::class, 'destroy'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);
    Route::post('facility/upload/{id}', [FacilityController::class, 'uploadImg'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);

    // Facility Type
    Route::get('facility-type', [FacilityTypeController::class, 'index'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::get('facility-type/{facility_type}', [FacilityTypeController::class, 'show'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::post('facility-type', [FacilityTypeController::class, 'store'])
        ->middleware('role:admin:sip,admin:school_admin');
    Route::put('facility-type/{facility_type}', [FacilityTypeController::class, 'update'])
        ->middleware('role:admin:sip,admin:school_admin');
    Route::delete('facility-type/{facility_type}', [FacilityTypeController::class, 'destroy'])
        ->middleware('role:admin:sip,admin:school_admin');

    // Extracurricular
    Route::get('schools/extracurricular/{schID}', [ExtracurricularController::class, 'bySchool'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::get('extracurricular', [ExtracurricularController::class, 'index'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::get('extracurricular/{extracurricular}', [ExtracurricularController::class, 'show'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::post('extracurricular', [ExtracurricularController::class, 'store'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);
    Route::put('extracurricular/{extracurricular}', [ExtracurricularController::class, 'update'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);
    Route::delete('extracurricular/{extracurricular}', [ExtracurricularController::class, 'destroy'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);

    // Extracurricular Type
    Route::get('extracurricular-type', [ExtracurricularTypeController::class, 'index'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::get('extracurricular-type/{extracurricular_type}', [ExtracurricularTypeController::class, 'show'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::post('extracurricular-type', [ExtracurricularTypeController::class, 'store'])
        ->middleware('role:admin:sip,admin:school_admin');
    Route::put('extracurricular-type/{extracurricular_type}', [ExtracurricularTypeController::class, 'update'])
        ->middleware('role:admin:sip,admin:school_admin');
    Route::delete('extracurricular-type/{extracurricular_type}', [ExtracurricularTypeController::class, 'destroy'])
        ->middleware('role:admin:sip,admin:school_admin');

    // Achievement
    Route::get('schools/achievement/{schID}', [AchievementController::class, 'bySchool'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::get('achievement', [AchievementController::class, 'index'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::get('achievement/{achievement}', [AchievementController::class, 'show'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::post('achievement', [AchievementController::class, 'store'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);
    Route::put('achievement/{achievement}', [AchievementController::class, 'update'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);
    Route::delete('achievement/{achievement}', [AchievementController::class, 'destroy'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);

    // Gallery
    Route::get('schools/gallery/{schID}', [GalleryController::class, 'bySchool'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::get('gallery', [GalleryController::class, 'index'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::get('gallery/{gallery}', [GalleryController::class, 'show'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::post('gallery', [GalleryController::class, 'store'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);
    Route::put('gallery/{gallery}', [GalleryController::class, 'update'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);
    Route::delete('gallery/{gallery}', [GalleryController::class, 'destroy'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);

    // News
    Route::get('schools/news/{schID}', [BlogController::class, 'bySchool'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::get('news', [BlogController::class, 'index'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::get('news/{news}', [BlogController::class, 'show'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::post('news', [BlogController::class, 'store'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);
    Route::put('news/{news}', [BlogController::class, 'update'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);
    Route::delete('news/{news}', [BlogController::class, 'destroy'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);

    // Agenda
    Route::get('schools/agenda/{schID}', [AgendaController::class, 'bySchool'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');

    /*
    |----------------------------------------------------------------------
    | Users & Registration
    | GET    : superadmin, admin:sip
    | CRUD   : admin:sip only
    | member : akses registrasi sendiri
    |----------------------------------------------------------------------
    */
    Route::get('users', [UserController::class, 'index'])->middleware('role:superadmin,admin:sip');
    Route::get('users/{user}', [UserController::class, 'show'])->middleware('role:superadmin,admin:sip');
    Route::post('users', [UserController::class, 'store'])->middleware('role:admin:sip');
    Route::put('users/{user}', [UserController::class, 'update'])->middleware('role:admin:sip');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('role:admin:sip');
    Route::post('users/upload-avatar', [UserController::class, 'uploadAvatar'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');

    Route::get('user-member', [UserMemberController::class, 'index'])->middleware('role:superadmin,admin:sip');
    Route::get('user-member/{user_member}', [UserMemberController::class, 'show'])->middleware('role:superadmin,admin:sip');
    Route::post('user-member', [UserMemberController::class, 'store'])->middleware('role:admin:sip');
    Route::put('user-member/{user_member}', [UserMemberController::class, 'update'])->middleware('role:admin:sip');
    Route::delete('user-member/{user_member}', [UserMemberController::class, 'destroy'])->middleware('role:admin:sip');

    Route::get('student', [UserCandidateController::class, 'index'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::get('student/{student}', [UserCandidateController::class, 'show'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::post('student', [UserCandidateController::class, 'store'])
        ->middleware('role:admin:sip,admin:school_admin');
    Route::put('student/{student}', [UserCandidateController::class, 'update'])
        ->middleware('role:admin:sip,admin:school_admin');
    Route::delete('student/{student}', [UserCandidateController::class, 'destroy'])
        ->middleware('role:admin:sip,admin:school_admin');

    Route::get('registration', [StudentRegistrationController::class, 'index'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::get('registration/{registration}', [StudentRegistrationController::class, 'show'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::post('registration', [StudentRegistrationController::class, 'store'])
        ->middleware('role:admin:sip,admin:school_admin,member');
    Route::put('registration/{registration}', [StudentRegistrationController::class, 'update'])
        ->middleware('role:admin:sip,admin:school_admin');
    Route::delete('registration/{registration}', [StudentRegistrationController::class, 'destroy'])
        ->middleware('role:admin:sip,admin:school_admin');

    Route::get('registration-form', [RegistrationFormController::class, 'index'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::get('registration-form/sch/{sch_id}', [RegistrationFormController::class, 'fromSchool'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::get('registration-form/{registration_form}', [RegistrationFormController::class, 'show'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::post('registration-form', [RegistrationFormController::class, 'store'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);
    Route::put('registration-form/{registration_form}', [RegistrationFormController::class, 'update'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);
    Route::delete('registration-form/{registration_form}', [RegistrationFormController::class, 'destroy'])
        ->middleware(['role:admin:sip,admin:school_admin', 'school.access']);

    /*
    |----------------------------------------------------------------------
    | Chat/Messages - semua role yang login
    |----------------------------------------------------------------------
    */
    Route::get('messages', [ChatController::class, 'index'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::get('messages/{message}', [ChatController::class, 'show'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::post('messages', [ChatController::class, 'store'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::put('messages/{message}', [ChatController::class, 'update'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::delete('messages/{message}', [ChatController::class, 'destroy'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::get('message-threads', [ChatController::class, 'getThreads'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');

    /*
    |----------------------------------------------------------------------
    | Testimony - semua role
    |----------------------------------------------------------------------
    */
    Route::post('testimonies', [TestimonyController::class, 'store'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');

    /*
    |----------------------------------------------------------------------
    | Nearest Schools - member
    |----------------------------------------------------------------------
    */
    Route::get('get-nearest-schools-coord', [SchoolController::class, 'getNearestSchoolsByCoord'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::get('get-nearest-schools-location', [SchoolController::class, 'getNearestSchoolsByLocation'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');


        // ============================================================
// TAMBAHKAN route-route ini di dalam blok Route::middleware(['auth:api'])
// tepat SEBELUM baris penutup `});`
// ============================================================
 
    /*
    |----------------------------------------------------------------------
    | Payment Proofs — bukti pembayaran pendaftaran siswa
    |   GET    : member (siswa), admin:school_admin, admin:school_head, admin:sip
    |   POST   : member (upload sendiri), admin:school_admin (upload offline)
    |   PUT    : admin:school_admin, admin:school_head (verifikasi/tolak)
    |   DELETE : member (hapus miliknya), admin:school_admin
    |----------------------------------------------------------------------
    */
    Route::get('payment-proof', [PaymentProofController::class, 'index'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::post('payment-proof', [PaymentProofController::class, 'store'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head,member');
    Route::put('payment-proof/{paymentProof}', [PaymentProofController::class, 'update'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,admin:school_head');
    Route::delete('payment-proof/{paymentProof}', [PaymentProofController::class, 'destroy'])
        ->middleware('role:superadmin,admin:sip,admin:school_admin,member');
});

