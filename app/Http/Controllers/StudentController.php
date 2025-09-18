<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\School;
use App\Models\ActivityLog;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Student::with(['school', 'schoolClass']);

        // Se for coordenador, filtrar apenas pela escola responsável
        if ($user->isCoordinator()) {
            $query->where('school_id', $user->coordinator->school_id);
        }

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('enrollment', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('school_id')) {
            // Coordenadores só podem filtrar pela sua escola
            if ($user->isCoordinator()) {
                $query->where('school_id', $user->coordinator->school_id);
            } else {
                $query->bySchool($request->school_id);
            }
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('grade')) {
            $query->byGrade($request->grade);
        }

        if ($request->filled('school_year')) {
            $query->bySchoolYear($request->school_year);
        }

        $students = $query->orderBy('name')->paginate(10);

        // Coordenadores só veem escolas que coordenam
        $schools = $user->isCoordinator()
            ? collect([$user->coordinator->school])
            : School::active()->orderBy('name')->get();

        return view('students.index', compact('students', 'schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Coordenadores só veem escolas que coordenam
        $schools = $user->isCoordinator()
            ? collect([$user->coordinator->school])
            : School::active()->orderBy('name')->get();

        // Coordenadores só veem turmas da sua escola
        $classes = $user->isCoordinator()
            ? $user->coordinator->school->classes()->active()->orderBy('year', 'desc')->orderBy('name')->get()
            : SchoolClass::active()->orderBy('year', 'desc')->orderBy('name')->get();

        return view('students.create', compact('schools', 'classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:14|unique:students,cpf',
            'rg' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:masculino,feminino,outro',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'enrollment' => 'required|string|max:50|unique:students,enrollment',
            'school_id' => 'required|exists:schools,id',
            'grade' => 'nullable|string|max:50',
            'class' => 'nullable|string|max:10',
            'class_id' => 'nullable|exists:classes,id',
            'school_year' => 'nullable|integer|min:2020|max:2030',
            'status' => 'required|in:ativo,inativo,transferido,formado,evadido',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|size:2',
            'postal_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_cpf' => 'nullable|string|max:14',
            'guardian_relationship' => 'nullable|in:pai,mae,avo,ava,tio,tia,responsavel_legal,outro',
            'medical_info' => 'nullable|string|max:1000',
            'observations' => 'nullable|string|max:1000',
            'enrollment_date' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            // Upload da foto se fornecida
            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('students', 'public');
            }

            $student = Student::create($validated);

            // Registrar atividade
            ActivityLog::log(
                'create',
                'Student',
                "Aluno '{$student->name}' foi cadastrado",
                $student->id
            );

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Aluno cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Remove foto se foi feito upload
            if (isset($validated['photo']) && Storage::disk('public')->exists($validated['photo'])) {
                Storage::disk('public')->delete($validated['photo']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar aluno: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load('school');
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $schools = School::active()->orderBy('name')->get();
        $classes = SchoolClass::active()->orderBy('year', 'desc')->orderBy('name')->get();
        $student->load('school');
        return view('students.edit', compact('student', 'schools', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('students', 'email')->ignore($student->id)],
            'phone' => 'nullable|string|max:20',
            'cpf' => ['nullable', 'string', 'max:14', Rule::unique('students', 'cpf')->ignore($student->id)],
            'rg' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:masculino,feminino,outro',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'enrollment' => ['required', 'string', 'max:50', Rule::unique('students', 'enrollment')->ignore($student->id)],
            'school_id' => 'required|exists:schools,id',
            'grade' => 'nullable|string|max:50',
            'class' => 'nullable|string|max:10',
            'class_id' => 'nullable|exists:classes,id',
            'school_year' => 'nullable|integer|min:2020|max:2030',
            'status' => 'required|in:ativo,inativo,transferido,formado,evadido',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|size:2',
            'postal_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_cpf' => 'nullable|string|max:14',
            'guardian_relationship' => 'nullable|in:pai,mae,avo,ava,tio,tia,responsavel_legal,outro',
            'medical_info' => 'nullable|string|max:1000',
            'observations' => 'nullable|string|max:1000',
            'enrollment_date' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            $oldPhoto = $student->photo;

            // Upload da nova foto se fornecida
            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('students', 'public');

                // Remove foto antiga
                if ($oldPhoto && Storage::disk('public')->exists($oldPhoto)) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            }

            $student->update($validated);

            // Registrar atividade
            ActivityLog::log(
                'update',
                'Student',
                "Aluno '{$student->name}' foi atualizado",
                $student->id
            );

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Aluno atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Remove nova foto se foi feito upload
            if (isset($validated['photo']) && Storage::disk('public')->exists($validated['photo'])) {
                Storage::disk('public')->delete($validated['photo']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar aluno: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        try {
            DB::beginTransaction();

            // Registrar atividade antes de excluir
            ActivityLog::log(
                'delete',
                'Student',
                "Aluno '{$student->name}' foi excluído",
                $student->id
            );

            // Remove foto se existir
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }

            $student->delete();

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Aluno excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Erro ao excluir aluno: ' . $e->getMessage());
        }
    }

    /**
     * Toggle student status
     */
    public function toggleStatus(Student $student)
    {
        try {
            $newStatus = $student->status === 'ativo' ? 'inativo' : 'ativo';
            $student->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => "Status do aluno alterado para {$newStatus}!",
                'status' => $newStatus,
                'status_label' => $student->status_label,
                'badge_class' => $student->status_badge_class
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status do aluno: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get students by school (for AJAX)
     */
    public function getBySchool(Request $request, School $school)
    {
        $students = $school->students()
            ->active()
            ->orderBy('name')
            ->get(['id', 'name', 'enrollment']);

        return response()->json($students);
    }

    /**
     * Get classes by school (for AJAX)
     */
    public function getClassesBySchool(Request $request, School $school)
    {
        $classes = $school->classes()
            ->active()
            ->orderBy('year', 'desc')
            ->orderBy('name')
            ->get(['id', 'name', 'year', 'grade']);

        return response()->json($classes);
    }

    /**
     * Exibir boletim de avaliações do aluno
     */
    public function reportCard(Student $student, Request $request)
    {
        try {
            $schoolYear = $request->get('year', date('Y'));

            // Buscar todas as notas do aluno para o ano letivo
            $notes = $student->notes()
                ->where('school_year', $schoolYear)
                ->where('status', 'ativa')
                ->orderBy('subject')
                ->orderBy('period')
                ->get();

            // Organizar notas por disciplina e período
            $reportData = $this->organizeReportData($notes, $schoolYear);

            // Debug: verificar se há dados
            if (empty($reportData)) {
                return view('students.report-card', compact('student', 'reportData', 'schoolYear'))
                    ->with('info', 'Nenhuma nota encontrada para este ano letivo.');
            }

            // Debug: adicionar timestamp para forçar reload
            $debugInfo = [
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'notes_count' => $notes->count(),
                'report_data_count' => count($reportData)
            ];

            return view('students.report-card', compact('student', 'reportData', 'schoolYear', 'debugInfo'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao carregar boletim: ' . $e->getMessage());
        }
    }

    /**
     * Gerar PDF do boletim de avaliações
     */
    public function reportCardPdf(Student $student, Request $request)
    {
        $schoolYear = $request->get('year', date('Y'));

        // Buscar todas as notas do aluno para o ano letivo
        $notes = $student->notes()
            ->where('school_year', $schoolYear)
            ->where('status', 'ativa')
            ->orderBy('subject')
            ->orderBy('period')
            ->get();

        // Organizar notas por disciplina e período
        $reportData = $this->organizeReportData($notes, $schoolYear);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('students.report-card-pdf', compact('student', 'reportData', 'schoolYear'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download("boletim-{$student->name}-{$schoolYear}.pdf");
    }

    /**
     * Organizar dados do boletim
     */
    private function organizeReportData($notes, $schoolYear)
    {
        try {
            $subjects = \App\Models\Note::getSubjects();
            $reportData = [];

            foreach ($subjects as $subjectKey => $subjectName) {
                $subjectNotes = $notes->where('subject', $subjectKey);

                if ($subjectNotes->isEmpty()) {
                    continue; // Pular disciplinas sem notas
                }

                $data = [
                    'subject' => $subjectName,
                    'subject_key' => $subjectKey,
                    'semester1' => $this->getSemesterData($subjectNotes, '1_semestre'),
                    'semester2' => $this->getSemesterData($subjectNotes, '2_semestre'),
                    'final_average' => $this->calculateFinalAverage($subjectNotes, $schoolYear),
                    'final_exam' => $this->getFinalExamGrade($subjectNotes),
                    'overall_average' => $this->calculateOverallAverage($subjectNotes, $schoolYear)
                ];

                $reportData[] = $data;
            }

            return $reportData;
        } catch (\Exception $e) {
            // Retornar dados vazios em caso de erro
            return [];
        }
    }

    /**
     * Obter dados de um semestre específico
     */
    private function getSemesterData($notes, $semester)
    {
        $evaluations = [];
        $recovery = null;
        $semesterAverage = null;

        // Buscar avaliações do semestre
        $evaluationPeriods = $semester === '1_semestre'
            ? ['1_ava', '2_ava', '3_ava', '4_ava']
            : ['5_ava', '6_ava', '7_ava', '8_ava'];

        foreach ($evaluationPeriods as $period) {
            $note = $notes->where('period', $period)->first();
            $evaluations[] = $note ? $note->grade : 0.0;
        }

        // Buscar recuperação
        $recoveryPeriod = $semester === '1_semestre' ? 'recuperacao_1_semestre' : 'recuperacao_2_semestre';
        $recoveryNote = $notes->where('period', $recoveryPeriod)->first();
        $recovery = $recoveryNote ? $recoveryNote->grade : null;

        // Calcular média semestral
        $semesterAverage = \App\Models\Note::calculateSemesterAverageWithRecovery(
            $notes->first()->student_id,
            $notes->first()->subject,
            $semester,
            $notes->first()->school_year
        );

        return [
            'evaluations' => $evaluations,
            'recovery' => $recovery,
            'average' => $semesterAverage ? round($semesterAverage, 1) : 0.0,
            'points' => array_sum($evaluations)
        ];
    }

    /**
     * Calcular média final da disciplina
     */
    private function calculateFinalAverage($notes, $schoolYear)
    {
        $semester1 = \App\Models\Note::calculateSemesterAverageWithRecovery(
            $notes->first()->student_id,
            $notes->first()->subject,
            '1_semestre',
            $schoolYear
        );

        $semester2 = \App\Models\Note::calculateSemesterAverageWithRecovery(
            $notes->first()->student_id,
            $notes->first()->subject,
            '2_semestre',
            $schoolYear
        );

        if ($semester1 === null && $semester2 === null) {
            return 0.0;
        }

        $averages = array_filter([$semester1, $semester2], function($avg) {
            return $avg !== null;
        });

        return !empty($averages) ? round(array_sum($averages) / count($averages), 1) : 0.0;
    }

    /**
     * Obter nota da prova final
     */
    private function getFinalExamGrade($notes)
    {
        $finalExam = $notes->where('period', 'prova_final')->first();
        return $finalExam ? $finalExam->grade : null;
    }

    /**
     * Calcular média geral final
     */
    private function calculateOverallAverage($notes, $schoolYear)
    {
        $finalAverage = $this->calculateFinalAverage($notes, $schoolYear);
        $finalExam = $this->getFinalExamGrade($notes);

        if ($finalExam !== null) {
            // Se há prova final, calcular média ponderada (70% média + 30% prova final)
            return round(($finalAverage * 0.7) + ($finalExam * 0.3), 1);
        }

        return $finalAverage;
    }
}
