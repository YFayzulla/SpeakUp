<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StoreRequest;
use App\Http\Requests\Student\UpdateRequest;
use App\Models\Attendance;
use App\Models\DeptStudent;
use App\Models\Group;
use App\Models\StudentInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Barcha talabalar ro'yxati.
     */
    public function index()
    {
        try {
            // OPTIMIZATSIYA:
            // 1. paginate(20) - 1000 ta talaba bo'lsa sahifa qotmaydi.
            // 2. with('group') - Har bir talaba uchun guruh nomini olishda ortiqcha so'rov yuborilmaydi (N+1 fixed).
            // 3. select(...) - Faqat kerakli ustunlarni olamiz.
            $students = User::role('student')
                ->with('group:id,name') // Group modelidan faqat id va name ni oladi
                ->orderBy("name")
                ->paginate(20);

            return view('admin.student.index', compact('students'));
        } catch (\Exception $e) {
            Log::error('StudentController@index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Talabalar ro\'yxatini yuklashda xatolik.');
        }
    }

    /**
     * Yangi talaba qo'shish sahifasi.
     */
    public function create()
    {
        try {
            $groups = Group::with('room:id,room') // Agar room relation bo'lsa
            ->orderBy('room_id')
                ->get();
            return view('admin.student.create', compact('groups'));
        } catch (\Exception $e) {
            Log::error('StudentController@create error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Sahifani yuklashda xatolik.');
        }
    }

    /**
     * Yangi talabani saqlash.
     */
    public function store(StoreRequest $request)
    {
        // StoreRequest da validatsiya o'tgan deb hisoblaymiz.

        $uploadedFilePath = null;

        // 1. Faylni yuklash (Tranzaksiyadan oldin, chunki storage operatsiyasi DB ga bog'liq emas)
        if ($request->hasFile('photo')) {
            try {
                $fileName = time() . '.' . $request->file('photo')->getClientOriginalExtension();
                // 'public/Photo' yoki shunchaki 'Photo' - disk sozlamasiga qarab
                $uploadedFilePath = $request->file('photo')->storeAs('Photo', $fileName);
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Rasmni yuklashda xatolik: ' . $e->getMessage());
            }
        }

        DB::beginTransaction();

        try {
            $group = Group::findOrFail($request->group_id);

            // 2. User yaratish
            $user = User::create([
                'name'         => $request->name,
                'password'     => Hash::make($request->phone), // Telefon raqam parol sifatida
                'passport'     => $request->passport,
                'phone'        => $request->phone,
                'parents_name' => $request->parents_name,
                'parents_tel'  => $request->parents_tel,
                'group_id'     => $group->id,
                'location'     => $request->location,
                'photo'        => $uploadedFilePath,
                'should_pay'   => (int) $request->should_pay,
                'description'  => $request->description,
                'status'       => ($group->id == 1) ? null : 0, // 1-guruh uchun maxsus logika
                'room_id'      => $group->room_id,
            ]);

            // Role berish
            $user->assignRole('student');

            // 3. Qo'shimcha ma'lumotlar (Tarix)
            StudentInformation::create([
                'user_id'  => $user->id,
                'group_id' => $group->id,
                'group'    => $group->name,
            ]);

            // 4. Moliya (Qarz)
            DeptStudent::create([
                'user_id'      => $user->id,
                'payed'        => 0,
                'dept'         => $request->should_pay,
                'status_month' => 0
            ]);

            DB::commit();

            return redirect()->route('student.index')->with('success', 'Talaba muvaffaqiyatli qo\'shildi.');

        } catch (\Exception $e) {
            DB::rollBack();

            // XAVFSIZLIK: Agar bazaga yozishda xato bo'lsa, yuklangan rasmni o'chirib tashlaymiz.
            // Aks holda serverda egasiz fayllar ko'payib ketadi.
            if ($uploadedFilePath && Storage::exists($uploadedFilePath)) {
                Storage::delete($uploadedFilePath);
            }

            Log::error('StudentController@store error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Saqlashda tizim xatoligi yuz berdi.');
        }
    }

    /**
     * Talaba ma'lumotlarini ko'rsatish.
     */
    public function show($id)
    {
        try {
            $student = User::with('group')->findOrFail($id);

            // Davomat ko'p bo'lishi mumkin, shuning uchun paginate yoki limit qo'yish yaxshi
            $attendances = Attendance::where('user_id', $id)->latest()->get();

            // Edit modal uchun guruhlar kerak bo'lishi mumkin
            $groups = Group::select('id', 'name')->orderBy('name')->get();

            return view('admin.student.show', compact('student', 'attendances', 'groups'));
        } catch (\Exception $e) {
            Log::error('StudentController@show error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Talaba ma\'lumotlarini yuklashda xatolik.');
        }
    }

    /**
     * Tahrirlash sahifasi.
     */
    public function edit($id)
    {
        try {
            $student = User::findOrFail($id);
            $groups = Group::orderBy('name')->get();
            return view('admin.student.edit', compact('student', 'groups'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Talaba topilmadi.');
        }
    }

    /**
     * Talabani yangilash (Eng nozik qism).
     */
    public function update(UpdateRequest $request, $id)
    {
        $newPhotoPath = null;
        $oldPhotoPath = null;

        DB::beginTransaction();

        try {
            $student = User::findOrFail($id);
            $group   = Group::findOrFail($request->group_id);

            // 1. Rasm bilan ishlash logikasi
            $oldPhotoPath = $student->photo;

            if ($request->hasFile('photo')) {
                // Yangi rasmni vaqtincha yuklaymiz
                $fileName = time() . '.' . $request->file('photo')->getClientOriginalExtension();
                $newPhotoPath = $request->file('photo')->storeAs('Photo', $fileName);
            } else {
                // Rasm o'zgarmadi
                $newPhotoPath = $oldPhotoPath;
            }

            // 2. Guruh o'zgargan bo'lsa, tarixga yozish
            if ($student->group_id != $request->group_id) {
                StudentInformation::create([
                    'user_id'  => $student->id,
                    'group_id' => $request->group_id,
                    'group'    => $group->name
                ]);

                // DIQQAT: Davomatlarni yangi guruhga o'tkazish.
                // Bu biznes logika bo'yicha to'g'riligiga ishonch hosil qiling.
                // Odatda eski davomat eski guruhda qoladi. Lekin talab bo'yicha qoldirdim.
                Attendance::where('user_id', $student->id)->update(['group_id' => $request->group_id]);
            }

            // 3. Update ma'lumotlarini tayyorlash
            $updateData = [
                'name'         => $request->name,
                'phone'        => $request->phone,
                'passport'     => $request->passport,
                'group_id'     => $request->group_id,
                'parents_name' => $request->parents_name,
                'parents_tel'  => $request->parents_tel,
                'location'     => $request->location,
                'should_pay'   => $request->should_pay,
                'photo'        => $newPhotoPath, // Yangi rasm (yoki eski)
                'description'  => $request->description,
                'room_id'      => $group->room_id,
                // Status logikasi: Agar 1-guruh bo'lsa null, aks holda eski status qoladi
                'status'       => ($group->id == 1) ? null : $student->status,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            // 4. Asosiy yangilash
            $student->update($updateData);

            // 5. DeptStudent (Qarzdorlik shartnomasi) yangilash yoki yaratish
            $student->deptStudent()->updateOrCreate(
                ['user_id' => $student->id],
                ['dept' => $request->should_pay]
            );

            DB::commit();

            // MUVAFFAQIYATLI UPDATE DAN SO'NG:
            // Agar yangi rasm yuklangan bo'lsa, ESKI rasmni o'chiramiz.
            if ($request->hasFile('photo') && $oldPhotoPath && Storage::exists($oldPhotoPath)) {
                Storage::delete($oldPhotoPath);
            }

            return redirect()->route('student.index')->with('success', 'Ma\'lumotlar muvaffaqiyatli yangilandi.');

        } catch (\Exception $e) {
            DB::rollBack();

            // XATOLIK BO'LSA:
            // Agar biz yangi rasm yuklagan bo'lsak, lekin baza update bo'lmasa,
            // o'sha YANGI yuklangan faylni o'chirib tashlash kerak.
            if ($request->hasFile('photo') && $newPhotoPath && Storage::exists($newPhotoPath)) {
                Storage::delete($newPhotoPath);
            }

            Log::error('StudentController@update error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Yangilashda xatolik yuz berdi.');
        }
    }

    /**
     * Talabani o'chirish.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $student = User::findOrFail($id);
            $photoPath = $student->photo; // O'chirishdan oldin rasm yo'lini olib qo'yamiz

            // 1. Bog'liq jadvallarni o'chirish
            // Agar bazada 'ON DELETE CASCADE' bo'lmasa, qo'lda o'chirish shart.
            StudentInformation::where('user_id', $student->id)->delete();
            DeptStudent::where('user_id', $student->id)->delete();
            Attendance::where('user_id', $student->id)->delete();
            // Yana boshqa bog'liq jadvallar bo'lsa shu yerda o'chiriladi (masalan, to'lovlar tarixi)

            // 2. Userni o'chirish
            $student->delete();

            DB::commit();

            // 3. Faylni o'chirish (Faqat baza muvaffaqiyatli tozalangandan keyin)
            if ($photoPath && Storage::exists($photoPath)) {
                Storage::delete($photoPath);
            }

            return redirect()->back()->with('success', 'Talaba va unga tegishli barcha ma\'lumotlar o\'chirildi.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('StudentController@destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'O\'chirish jarayonida xatolik yuz berdi. Balki talabaga bog\'liq boshqa ma\'lumotlar mavjuddir.');
        }
    }
}