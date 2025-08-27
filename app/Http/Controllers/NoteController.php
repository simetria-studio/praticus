<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Student;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Note::with(['student', 'student.school']);

        // Se for coordenador, filtrar apenas pela escola responsável
        if ($user->isCoordinator()) {
            $query->whereHas('student', function($q) use ($user) {
                $q->where('school_id', $user->coordinator->school_id);
            });
        }

        // Aplicar filtros de busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('enrollment', 'like', "%{$search}%");
            });
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        if ($request->filled('period')) {
            $query->where('period', $request->period);
        }

        if ($request->filled('school_year')) {
            $query->where('school_year', $request->school_year);
        }

        if ($request->filled('evaluation_type')) {
            $query->where('evaluation_type', $request->evaluation_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $notes = $query->orderBy('evaluation_date', 'desc')
                      ->orderBy('created_at', 'desc')
                      ->paginate(15);

        // Dados para os filtros - coordenadores só veem estudantes da sua escola
        $students = $user->isCoordinator()
            ? Student::where('school_id', $user->coordinator->school_id)->orderBy('name')->get(['id', 'name', 'enrollment'])
            : Student::orderBy('name')->get(['id', 'name', 'enrollment']);

        $subjects = Note::getSubjects();
        $periods = Note::getPeriods();
        $evaluationTypes = Note::getEvaluationTypes();
        $schoolYears = range(date('Y') - 5, date('Y') + 1);

        return view('notes.index', compact(
            'notes', 'students', 'subjects', 'periods', 'evaluationTypes', 'schoolYears'
        ));
    }

    /**
     * Exibir página de históricos escolares
     */
    public function historicalIndex(Request $request)
    {
        $user = Auth::user();

        // Buscar apenas estudantes que têm notas registradas
        $query = Student::whereHas('notes')
            ->with(['school', 'notes' => function($q) {
                $q->select('student_id', 'school_year')
                  ->distinct();
            }]);

        // Se for coordenador, filtrar apenas pela escola responsável
        if ($user->isCoordinator()) {
            $query->where('school_id', $user->coordinator->school_id);
        }

        // Aplicar filtros de busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('enrollment', 'like', "%{$search}%");
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
            $query->whereHas('notes', function($q) use ($request) {
                $q->where('school_year', $request->school_year);
            });
        }

        $students = $query->orderBy('name')->paginate(12);

        // Coordenadores só veem escolas que coordenam
        $schools = $user->isCoordinator()
            ? collect([$user->coordinator->school])
            : School::active()->orderBy('name')->get();

        $schoolYears = range(date('Y') - 5, date('Y') + 1);

        // Estatísticas gerais - coordenadores só veem dados da sua escola
        if ($user->isCoordinator()) {
            $totalStudentsWithHistory = Student::where('school_id', $user->coordinator->school_id)->whereHas('notes')->count();
            $totalSchoolsWithHistory = 1; // Coordenadores só coordenam uma escola
        } else {
            $totalStudentsWithHistory = Student::whereHas('notes')->count();
            $totalSchoolsWithHistory = School::whereHas('students.notes')->count();
        }

        return view('notes.historical-index', compact(
            'students', 'schools', 'schoolYears', 'totalStudentsWithHistory', 'totalSchoolsWithHistory'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        // Coordenadores só veem estudantes da sua escola
        $students = $user->isCoordinator()
            ? Student::where('school_id', $user->coordinator->school_id)->active()->orderBy('name')->get()
            : Student::active()->orderBy('name')->get();

        $subjects = Note::getSubjects();
        $periods = Note::getPeriods();
        $evaluationTypes = Note::getEvaluationTypes();

        // Se vier um student_id na URL, pré-selecionar
        $selectedStudent = null;
        if ($request->filled('student_id')) {
            $selectedStudent = Student::find($request->student_id);
            // Coordenadores só podem ver estudantes da sua escola
            if ($user->isCoordinator() && $selectedStudent && $selectedStudent->school_id !== $user->coordinator->school_id) {
                $selectedStudent = null;
            }
        }

        return view('notes.create', compact(
            'students', 'subjects', 'periods', 'evaluationTypes', 'selectedStudent'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject' => 'required|string|max:100',
            'period' => 'required|string|max:50',
            'grade' => 'required|numeric|min:0|max:999.99',
            'max_grade' => 'required|numeric|min:0.01|max:999.99',
            'evaluation_type' => 'required|string|max:50',
            'evaluation_date' => 'required|date',
            'school_year' => 'required|integer|min:2020|max:2030',
            'class' => 'nullable|string|max:50',
            'weight' => 'nullable|numeric|min:0.01|max:10.00',
            'observations' => 'nullable|string|max:1000',
        ], [
            'student_id.required' => 'Selecione um aluno.',
            'student_id.exists' => 'Aluno selecionado não existe.',
            'subject.required' => 'A disciplina é obrigatória.',
            'period.required' => 'O período é obrigatório.',
            'grade.required' => 'A nota é obrigatória.',
            'grade.numeric' => 'A nota deve ser um número.',
            'grade.min' => 'A nota não pode ser negativa.',
            'max_grade.required' => 'A nota máxima é obrigatória.',
            'max_grade.numeric' => 'A nota máxima deve ser um número.',
            'max_grade.min' => 'A nota máxima deve ser maior que zero.',
            'evaluation_type.required' => 'O tipo de avaliação é obrigatório.',
            'evaluation_date.required' => 'A data da avaliação é obrigatória.',
            'evaluation_date.date' => 'Data da avaliação inválida.',
            'school_year.required' => 'O ano letivo é obrigatório.',
            'school_year.integer' => 'O ano letivo deve ser um número inteiro.',
            'weight.numeric' => 'O peso deve ser um número.',
            'weight.min' => 'O peso deve ser maior que zero.',
        ]);

        // Conversão automática de nota se necessário
        $gradeConversion = $this->convertGradeIfNeeded($validated['grade'], $validated['max_grade']);
        $validated['grade'] = $gradeConversion['grade'];
        $conversionMessage = $gradeConversion['message'];

        try {
            DB::beginTransaction();

            $validated['weight'] = $validated['weight'] ?? 1.00;
            $validated['created_by'] = Auth::user()->name ?? 'Sistema';

            $note = Note::create($validated);

            DB::commit();

            $successMessage = 'Nota cadastrada com sucesso!';
            if ($conversionMessage) {
                $successMessage .= ' ' . $conversionMessage;
            }

            return redirect()->route('notes.show', $note)
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar nota: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        $note->load(['student', 'student.school']);

        // Buscar outras notas do mesmo aluno na mesma disciplina e período
        $relatedNotes = Note::where('student_id', $note->student_id)
            ->where('subject', $note->subject)
            ->where('period', $note->period)
            ->where('school_year', $note->school_year)
            ->where('id', '!=', $note->id)
            ->with('student')
            ->get();

        // Calcular média da disciplina no período
        $average = Note::calculateAverage(
            $note->student_id,
            $note->subject,
            $note->period,
            $note->school_year
        );

        return view('notes.show', compact('note', 'relatedNotes', 'average'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        $note->load(['student']);

        $students = Student::active()->orderBy('name')->get();
        $subjects = Note::getSubjects();
        $periods = Note::getPeriods();
        $evaluationTypes = Note::getEvaluationTypes();

        return view('notes.edit', compact(
            'note', 'students', 'subjects', 'periods', 'evaluationTypes'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject' => 'required|string|max:100',
            'period' => 'required|string|max:50',
            'grade' => 'required|numeric|min:0|max:999.99',
            'max_grade' => 'required|numeric|min:0.01|max:999.99',
            'evaluation_type' => 'required|string|max:50',
            'evaluation_date' => 'required|date',
            'school_year' => 'required|integer|min:2020|max:2030',
            'class' => 'nullable|string|max:50',
            'weight' => 'nullable|numeric|min:0.01|max:10.00',
            'observations' => 'nullable|string|max:1000',
            'status' => 'required|in:ativa,cancelada,corrigida',
        ], [
            'student_id.required' => 'Selecione um aluno.',
            'student_id.exists' => 'Aluno selecionado não existe.',
            'subject.required' => 'A disciplina é obrigatória.',
            'period.required' => 'O período é obrigatório.',
            'grade.required' => 'A nota é obrigatória.',
            'grade.numeric' => 'A nota deve ser um número.',
            'grade.min' => 'A nota não pode ser negativa.',
            'max_grade.required' => 'A nota máxima é obrigatória.',
            'max_grade.numeric' => 'A nota máxima deve ser um número.',
            'max_grade.min' => 'A nota máxima deve ser maior que zero.',
            'evaluation_type.required' => 'O tipo de avaliação é obrigatório.',
            'evaluation_date.required' => 'A data da avaliação é obrigatória.',
            'evaluation_date.date' => 'Data da avaliação inválida.',
            'school_year.required' => 'O ano letivo é obrigatório.',
            'school_year.integer' => 'O ano letivo deve ser um número inteiro.',
            'weight.numeric' => 'O peso deve ser um número.',
            'weight.min' => 'O peso deve ser maior que zero.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'Status inválido.',
        ]);

        // Conversão automática de nota se necessário
        $gradeConversion = $this->convertGradeIfNeeded($validated['grade'], $validated['max_grade']);
        $validated['grade'] = $gradeConversion['grade'];
        $conversionMessage = $gradeConversion['message'];

        try {
            DB::beginTransaction();

            $validated['weight'] = $validated['weight'] ?? 1.00;
            $validated['updated_by'] = Auth::user()->name ?? 'Sistema';

            $note->update($validated);

            DB::commit();

            $successMessage = 'Nota atualizada com sucesso!';
            if ($conversionMessage) {
                $successMessage .= ' ' . $conversionMessage;
            }

            return redirect()->route('notes.show', $note)
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar nota: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        try {
            DB::beginTransaction();

            $studentName = $note->student->name;
            $subject = $note->subject;

            $note->delete();

            DB::commit();

            return redirect()->route('notes.index')
                ->with('success', "Nota de {$studentName} em {$subject} foi excluída com sucesso!");

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Erro ao excluir nota: ' . $e->getMessage());
        }
    }

    /**
     * Exibir boletim do aluno
     */
    public function studentReport(Student $student, Request $request)
    {
        $schoolYear = $request->get('school_year', date('Y'));

        $student->load(['school']);

        // Buscar todas as notas do aluno no ano
        $notes = $student->notes()
            ->where('school_year', $schoolYear)
            ->active()
            ->orderBy('subject')
            ->orderBy('period')
            ->orderBy('evaluation_date')
            ->get();

        // Organizar notas por disciplina e período
        $subjects = Note::getSubjects();
        $periods = Note::getPeriods();

        $reportData = [];
        foreach ($subjects as $subjectKey => $subjectName) {
            $reportData[$subjectKey] = [
                'name' => $subjectName,
                'periods' => [],
                'average' => $student->getSubjectAverage($subjectKey, null, $schoolYear),
                'status' => $student->getSubjectStatus($subjectKey, $schoolYear)
            ];

            foreach ($periods as $periodKey => $periodName) {
                $periodNotes = $notes->where('subject', $subjectKey)
                                   ->where('period', $periodKey);

                $reportData[$subjectKey]['periods'][$periodKey] = [
                    'name' => $periodName,
                    'notes' => $periodNotes,
                    'average' => $student->getSubjectAverage($subjectKey, $periodKey, $schoolYear)
                ];
            }
        }

        // Calcular estatísticas gerais
        $generalAverage = $student->getGeneralAverage($schoolYear);
        $academicStatus = $student->getAcademicStatus($schoolYear);

        $schoolYears = range(date('Y') - 5, date('Y'));

        return view('notes.student-report', compact(
            'student', 'reportData', 'generalAverage', 'academicStatus',
            'schoolYear', 'schoolYears', 'subjects', 'periods'
        ));
    }

    /**
     * Gerar histórico escolar completo do aluno
     */
    public function historicalReport(Student $student, Request $request)
    {
        // Verificar se o estudante está ativo
        if ($student->status !== 'ativo') {
            abort(404, 'Estudante não encontrado ou inativo.');
        }

        $historicalData = $this->generateHistoricalData($student);

        return view('notes.historical-report', $historicalData);
    }

    /**
     * Download do histórico escolar em PDF
     */
    public function downloadHistoricalPdf(Student $student)
    {
        // Verificar se o estudante está ativo
        if ($student->status !== 'ativo') {
            abort(404, 'Estudante não encontrado ou inativo.');
        }

        // Verificar se o estudante tem notas para gerar o histórico
        if (!$student->notes()->exists()) {
            return redirect()->back()->with('error', 'Não é possível gerar o histórico escolar. O estudante não possui notas registradas.');
        }

        $historicalData = $this->generateHistoricalData($student);

        // Gerar PDF
        $pdf = PDF::loadView('notes.historical-report-pdf', $historicalData)
            ->setPaper('a4', 'portrait');

        $filename = 'historico_escolar_' . str_replace(' ', '_', $student->name) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Gerar dados do histórico escolar
     */
    private function generateHistoricalData(Student $student)
    {
        $student->load(['school']);

        // Verificar se o estudante tem uma escola associada
        if (!$student->school) {
            throw new \Exception('Estudante não possui uma escola associada.');
        }

        // Buscar todos os anos letivos que o aluno tem notas
        $schoolYears = $student->notes()
            ->select('school_year')
            ->distinct()
            ->orderBy('school_year', 'desc')
            ->pluck('school_year')
            ->toArray();

        if (empty($schoolYears)) {
            $schoolYears = [date('Y')];
        }

        // Organizar dados por ano letivo
        $subjects = Note::getSubjects();
        $periods = Note::getPeriods();

        $historicalData = [];
        $academicSummary = [];

        foreach ($schoolYears as $year) {
            // Buscar todas as notas do ano
            $yearNotes = $student->notes()
                ->where('school_year', $year)
                ->active()
                ->orderBy('subject')
                ->orderBy('period')
                ->get();

            $yearData = [
                'year' => $year,
                'subjects' => [],
                'general_average' => $student->getGeneralAverage($year),
                'academic_status' => $student->getAcademicStatus($year),
                'total_subjects' => 0,
                'approved_subjects' => 0,
                'failed_subjects' => 0,
            ];

            foreach ($subjects as $subjectKey => $subjectName) {
                $subjectAverage = $student->getSubjectAverage($subjectKey, null, $year);
                $subjectStatus = $student->getSubjectStatus($subjectKey, $year);

                // Só incluir disciplinas que têm notas no ano
                $subjectNotes = $yearNotes->where('subject', $subjectKey);

                if ($subjectNotes->count() > 0) {
                    $yearData['subjects'][$subjectKey] = [
                        'name' => $subjectName,
                        'average' => $subjectAverage,
                        'status' => $subjectStatus,
                        'notes_count' => $subjectNotes->count(),
                        'periods' => []
                    ];

                    // Organizar notas por período
                    foreach ($periods as $periodKey => $periodName) {
                        $periodNotes = $subjectNotes->where('period', $periodKey);
                        if ($periodNotes->count() > 0) {
                            $periodAverage = $student->getSubjectAverage($subjectKey, $periodKey, $year);
                            $yearData['subjects'][$subjectKey]['periods'][$periodKey] = [
                                'name' => $periodName,
                                'average' => $periodAverage,
                                'notes' => $periodNotes
                            ];
                        }
                    }

                    $yearData['total_subjects']++;
                    if ($subjectStatus === 'aprovado') {
                        $yearData['approved_subjects']++;
                    } else {
                        $yearData['failed_subjects']++;
                    }
                }
            }

            $historicalData[$year] = $yearData;

            // Resumo acadêmico
            $academicSummary[] = [
                'year' => $year,
                'status' => $yearData['academic_status'],
                'average' => $yearData['general_average'],
                'subjects_count' => $yearData['total_subjects'],
                'approved_count' => $yearData['approved_subjects'],
                'failed_count' => $yearData['failed_subjects']
            ];
        }

        // Estatísticas gerais
        $overallStats = [
            'total_years' => count($schoolYears),
            'current_status' => $student->status,
            'overall_average' => collect($academicSummary)->avg('average'),
            'total_subjects_studied' => collect($academicSummary)->sum('subjects_count'),
            'total_approved' => collect($academicSummary)->sum('approved_count'),
            'total_failed' => collect($academicSummary)->sum('failed_count'),
        ];

        return compact('student', 'historicalData', 'academicSummary', 'overallStats', 'subjects', 'periods', 'schoolYears');
    }

    /**
     * Buscar alunos por AJAX
     */
    public function searchStudents(Request $request)
    {
        $search = $request->get('search');

        $students = Student::where('name', 'like', "%{$search}%")
            ->orWhere('enrollment', 'like', "%{$search}%")
            ->active()
            ->limit(10)
            ->get(['id', 'name', 'enrollment']);

        return response()->json($students);
    }

    /**
     * Converte automaticamente a nota se ela for maior que a nota máxima
     */
    private function convertGradeIfNeeded($grade, $maxGrade)
    {
        $originalGrade = $grade;
        $message = null;

        // Se a nota for maior que a máxima, tentar conversão automática
        if ($grade > $maxGrade) {
            // Dividir por 10 até a nota ficar dentro do limite ou até 3 tentativas
            $attempts = 0;
            $maxAttempts = 3;

            while ($grade > $maxGrade && $attempts < $maxAttempts) {
                $grade = $grade / 10;
                $attempts++;
            }

            // Se ainda estiver acima do limite após tentativas, usar a nota máxima
            if ($grade > $maxGrade) {
                $grade = $maxGrade;
                $message = "⚠️ A nota foi ajustada automaticamente de {$originalGrade} para {$grade} (nota máxima permitida).";
            } else {
                $message = "✨ A nota foi convertida automaticamente de {$originalGrade} para {$grade}.";
            }
        }

        return [
            'grade' => round($grade, 2),
            'message' => $message
        ];
    }
}
