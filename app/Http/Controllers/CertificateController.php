<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\School;
use App\Models\Student;
use App\Models\Certificate;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Spatie\Browsershot\Browsershot;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Certificate::with(['student', 'school']);

        // Se for coordenador, filtrar apenas pela escola responsável
        if ($user->isCoordinator()) {
            $query->where('school_id', $user->coordinator->school_id);
        }

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                  ->orWhere('student_name', 'like', "%{$search}%")
                  ->orWhere('verification_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('school_id')) {
            // Coordenadores só podem filtrar pela sua escola
            if ($user->isCoordinator()) {
                $query->where('school_id', $user->coordinator->school_id);
            } else {
                $query->where('school_id', $request->school_id);
            }
        }

        if ($request->filled('certificate_type')) {
            $query->byCertificateType($request->certificate_type);
        }

        if ($request->filled('course_level')) {
            $query->byCourseLevel($request->course_level);
        }

        if ($request->filled('completion_year')) {
            $query->byYear($request->completion_year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $certificates = $query->orderBy('created_at', 'desc')->paginate(10);

        // Coordenadores só veem escolas que coordenam
        $schools = $user->isCoordinator()
            ? collect([$user->coordinator->school])
            : School::active()->orderBy('name')->get();

        return view('certificates.index', compact('certificates', 'schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $schools = School::active()->orderBy('name')->get();
        $students = collect();

        if ($request->filled('student_id')) {
            $student = Student::with('school')->find($request->student_id);
            if ($student) {
                $students = collect([$student]);
            }
        }

        return view('certificates.create', compact('schools', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'certificate_type' => 'required|in:conclusao,historico,declaracao',
            'course_level' => 'required|in:ensino_medio,ensino_fundamental,educacao_infantil,ensino_tecnico,ensino_superior',
            'course_name' => 'nullable|string|max:255',
            'completion_year' => 'required|integer|min:1990|max:2030',
            'completion_date' => 'required|date',
            'director_name' => 'required|string|max:255',
            'director_title' => 'nullable|string|max:100',
            'secretary_name' => 'nullable|string|max:255',
            'secretary_title' => 'nullable|string|max:100',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'required|string|max:255',
            'student_birth_place' => 'required|string|max:255',
            'school_authorization' => 'nullable|string|max:255',
            'school_inep' => 'nullable|string|max:20',
            'observations' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $student = Student::with('school')->findOrFail($validated['student_id']);
            $school = $student->school;

            // Gerar dados automáticos
            $certificate = new Certificate();
            $certificateNumber = $certificate->generateCertificateNumber();
            $verificationCode = $certificate->generateVerificationCode();

            // Preencher dados do certificado
            $certificateData = array_merge($validated, [
                'school_id' => $school->id,
                'certificate_number' => $certificateNumber,
                'issue_date' => now(),
                'verification_code' => $verificationCode,

                // Dados da escola
                'school_name' => $school->name,
                'school_cnpj' => $school->cnpj,
                'school_address' => $school->full_address,

                // Dados do aluno
                'student_name' => $student->name,
                'student_cpf' => $student->cpf,
                'student_birth_date' => $student->birth_date,
                'student_nationality' => 'BRASILEIRA',

                // Dados de emissão
                'issue_city' => $school->city,
                'issue_state' => $school->state,

                // Valores padrão
                'director_title' => $validated['director_title'] ?? 'DIRETOR(A)',
                'secretary_title' => $validated['secretary_title'] ?? 'SECRETÁRIO(A)',
            ]);

            $certificate = Certificate::create($certificateData);

            // Gerar hash após criação
            $certificate->hash = $certificate->generateHash();
            $certificate->save();

            // Gerar PDF
            $this->generatePDF($certificate);

            // Registrar atividade
            ActivityLog::log(
                'create',
                'Certificate',
                "Certificado '{$certificate->certificate_number}' gerado para {$certificate->student_name}",
                $certificate->id
            );

            DB::commit();

            return redirect()->route('certificates.show', $certificate)
                ->with('success', 'Certificado gerado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao gerar certificado: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
        $certificate->load(['student', 'school']);
        return view('certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Certificate $certificate)
    {
        $schools = School::active()->orderBy('name')->get();
        $certificate->load(['student', 'school']);
        return view('certificates.edit', compact('certificate', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'director_name' => 'required|string|max:255',
            'director_title' => 'nullable|string|max:100',
            'secretary_name' => 'nullable|string|max:255',
            'secretary_title' => 'nullable|string|max:100',
            'observations' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $certificate->update($validated);

            // Regenerar PDF se necessário
            $this->generatePDF($certificate);

            DB::commit();

            return redirect()->route('certificates.show', $certificate)
                ->with('success', 'Certificado atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar certificado: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {
        try {
            DB::beginTransaction();

            // Remove PDF se existir
            if ($certificate->pdf_path && Storage::disk('public')->exists($certificate->pdf_path)) {
                Storage::disk('public')->delete($certificate->pdf_path);
            }

            $certificate->delete();

            DB::commit();

            return redirect()->route('certificates.index')
                ->with('success', 'Certificado excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Erro ao excluir certificado: ' . $e->getMessage());
        }
    }

    /**
     * Preview certificate before download
     */
    public function preview(Certificate $certificate)
    {
        $certificate->load(['student', 'school']);

        // Array de meses em português
        $months = [
            1 => 'JANEIRO', 2 => 'FEVEREIRO', 3 => 'MARÇO', 4 => 'ABRIL',
            5 => 'MAIO', 6 => 'JUNHO', 7 => 'JULHO', 8 => 'AGOSTO',
            9 => 'SETEMBRO', 10 => 'OUTUBRO', 11 => 'NOVEMBRO', 12 => 'DEZEMBRO'
        ];

        // Preparar dados para o template
        $data = [
            'school_name' => $certificate->school_name,
            'school_cnpj' => $certificate->formatted_school_cnpj,
            'school_inep' => $certificate->school_inep,
            'school_address' => $certificate->school_address,
            'school_authorization' => $certificate->school_authorization,
            'authorization_date' => $certificate->authorization_date ?? '02/12/2022',
            'student_name' => $certificate->student_name,
            'student_cpf' => $certificate->formatted_student_cpf,
            'student_birth_day' => $certificate->student_birth_date ? $certificate->student_birth_date->format('d') : '30',
            'student_birth_month' => $certificate->student_birth_date_written,
            'student_birth_year' => $certificate->student_birth_date ? $certificate->student_birth_date->format('Y') : '2006',
            'student_birthplace' => $certificate->student_birth_place,
            'student_birth_state' => 'PIAUÍ',
            'student_nationality' => $certificate->student_nationality,
            'student_father' => $certificate->father_name,
            'student_mother' => $certificate->mother_name,
            'completion_year' => $certificate->completion_year,
            'course_level' => $certificate->course_level_label,
            'issue_location' => $certificate->issue_city,
            'issue_state' => $certificate->issue_state,
            'issue_day' => $certificate->issue_date ? $certificate->issue_date->format('d') : '08',
            'issue_month' => $certificate->issue_date ? $months[$certificate->issue_date->format('n')] : 'FEVEREIRO',
            'issue_year' => $certificate->issue_date ? $certificate->issue_date->format('Y') : '2024',
            'verification_code' => $certificate->verification_code,
            'school_type' => 'Centro Estadual de Tempo Integral - CETI',
            'school_short_name' => 'FRANCISCA TRINDADE',
            'authorization' => $certificate->school_authorization ?? '224/2022',
            'authorization_date' => '02/12/2022'
        ];

        return view('certificates.preview', compact('certificate', 'data'));
    }

    /**
     * View PDF template directly for CSS adjustments
     */
    public function viewPdf(Certificate $certificate = null)
    {
        // Se não tiver certificado, usar dados de exemplo
        if (!$certificate) {
            $certificate = Certificate::first();
        }

        if ($certificate) {
            $certificate->load(['student', 'school']);
        }

        // Array de meses em português
        $months = [
            1 => 'JANEIRO', 2 => 'FEVEREIRO', 3 => 'MARÇO', 4 => 'ABRIL',
            5 => 'MAIO', 6 => 'JUNHO', 7 => 'JULHO', 8 => 'AGOSTO',
            9 => 'SETEMBRO', 10 => 'OUTUBRO', 11 => 'NOVEMBRO', 12 => 'DEZEMBRO'
        ];

        // Preparar dados para o template
        $data = [
            'school_name' => $certificate->school_name ?? 'CENTRO ESTADUAL DE TEMPO INTEGRAL FRANCISCA TRINDADE',
            'school_cnpj' => $certificate->formatted_school_cnpj ?? '08.055.298/0001-49',
            'school_inep' => $certificate->school_inep ?? '22136703',
            'school_address' => $certificate->school_address ?? 'RUA DO ARAME, S/N – BAIRRO SANTINHO – CEP: 64.100-000 – BARRAS-PI',
            'school_authorization' => $certificate->school_authorization ?? '224/2022',
            'authorization_date' => '02/12/2022',
            'student_name' => $certificate->student_name ?? 'ALUNO EXEMPLO DA SILVA',
            'student_cpf' => $certificate->formatted_student_cpf ?? '000.000.000-00',
            'student_birth_day' => $certificate->student_birth_date ? $certificate->student_birth_date->format('d') : '30',
            'student_birth_month' => $certificate->student_birth_date_written ?? 'JULHO',
            'student_birth_year' => $certificate->student_birth_date ? $certificate->student_birth_date->format('Y') : '2006',
            'student_birthplace' => $certificate->student_birth_place ?? 'BARRAS',
            'student_birth_state' => 'PIAUÍ',
            'student_nationality' => $certificate->student_nationality ?? 'BRASILEIRA',
            'student_father' => $certificate->father_name ?? 'FRANCISCO DAS CHAGAS FURTADO MACHADO',
            'student_mother' => $certificate->mother_name ?? 'MARIA DA CONCEIÇÃO FERREIRA DA SILVA',
            'completion_year' => $certificate->completion_year ?? '2023',
            'course_level' => $certificate->course_level_label ?? 'MÉDIO',
            'issue_location' => $certificate->issue_city ?? 'BARRAS',
            'issue_state' => $certificate->issue_state ?? 'PI',
            'issue_day' => $certificate->issue_date ? $certificate->issue_date->format('d') : '08',
            'issue_month' => $certificate->issue_date ? $months[$certificate->issue_date->format('n')] : 'FEVEREIRO',
            'issue_year' => $certificate->issue_date ? $certificate->issue_date->format('Y') : '2024',
            'verification_code' => $certificate->verification_code ?? 'CERT-2024-12345',
            'school_type' => 'Centro Estadual de Tempo Integral - CETI',
            'school_short_name' => 'FRANCISCA TRINDADE',
            'authorization' => '224/2022',
            'authorization_date' => '02/12/2022'
        ];

        // Passar tanto os dados individuais quanto o objeto certificate
        $data['certificate'] = $certificate;

        // Retornar diretamente a view do PDF
        return view('certificates.pdf', $data);
    }

    /**
     * Download certificate PDF
     */
    public function downloadPDF(Certificate $certificate)
    {
        if (!$certificate->pdf_path || !Storage::disk('public')->exists($certificate->pdf_path)) {
            $this->generatePDF($certificate);
        }

        $fileName = "certificado_{$certificate->certificate_number}.pdf";

        // Registrar atividade de download
        ActivityLog::log(
            'download',
            'Certificate',
            "PDF do certificado '{$certificate->certificate_number}' foi baixado",
            $certificate->id
        );

        return response()->download(Storage::disk('public')->path($certificate->pdf_path), $fileName);
    }

    /**
     * Generate certificate PDF using Browsershot (Better quality)
     */
    public function generateBrowsershotPDF(Certificate $certificate)
    {
        $certificate->load(['student', 'school']);

        // Array de meses em português
        $months = [
            1 => 'JANEIRO', 2 => 'FEVEREIRO', 3 => 'MARÇO', 4 => 'ABRIL',
            5 => 'MAIO', 6 => 'JUNHO', 7 => 'JULHO', 8 => 'AGOSTO',
            9 => 'SETEMBRO', 10 => 'OUTUBRO', 11 => 'NOVEMBRO', 12 => 'DEZEMBRO'
        ];

        // Preparar dados para o template
        $data = [
            'school_name' => $certificate->school_name,
            'school_cnpj' => $certificate->formatted_school_cnpj,
            'school_inep' => $certificate->school_inep,
            'school_address' => $certificate->school_address,
            'school_authorization' => $certificate->school_authorization,
            'authorization_date' => $certificate->authorization_date ?? '02/12/2022',
            'student_name' => $certificate->student_name,
            'student_cpf' => $certificate->formatted_student_cpf,
            'student_birth_day' => $certificate->student_birth_date ? $certificate->student_birth_date->format('d') : '30',
            'student_birth_month' => $certificate->student_birth_date_written,
            'student_birth_year' => $certificate->student_birth_date ? $certificate->student_birth_date->format('Y') : '2006',
            'student_birthplace' => $certificate->student_birth_place,
            'student_birth_state' => 'PIAUÍ',
            'student_nationality' => $certificate->student_nationality,
            'student_father' => $certificate->father_name,
            'student_mother' => $certificate->mother_name,
            'completion_year' => $certificate->completion_year,
            'course_level' => $certificate->course_level_label,
            'issue_location' => $certificate->issue_city,
            'issue_state' => $certificate->issue_state,
            'issue_day' => $certificate->issue_date ? $certificate->issue_date->format('d') : '08',
            'issue_month' => $certificate->issue_date ? $months[$certificate->issue_date->format('n')] : 'FEVEREIRO',
            'issue_year' => $certificate->issue_date ? $certificate->issue_date->format('Y') : '2024',
            'verification_code' => $certificate->verification_code,
            'school_type' => 'Centro Estadual de Tempo Integral - CETI',
            'school_short_name' => 'FRANCISCA TRINDADE',
            'authorization' => $certificate->school_authorization ?? '224/2022',
            'authorization_date' => '02/12/2022'
        ];

        // Passar tanto os dados individuais quanto o objeto certificate
        $data['certificate'] = $certificate;

        // Gerar HTML
        $html = view('certificates.pdf', $data)->render();

        try {
            // Gerar PDF com Browsershot
            $pdf = Browsershot::html($html)
                ->paperSize(297, 210) // A4 Landscape em mm
                ->margins(0, 0, 0, 0)
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->pdf();

            // Criar diretório se não existir
            $directory = 'certificates/' . date('Y');
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Salvar PDF
            $fileName = $directory . "/certificado_browsershot_{$certificate->certificate_number}.pdf";
            Storage::disk('public')->put($fileName, $pdf);

            // Atualizar caminho no banco
            $certificate->update(['pdf_path' => $fileName]);

            return $fileName;

        } catch (\Exception $e) {
            // Fallback para DomPDF se Browsershot falhar
            return $this->generatePDFWithDomPDF($certificate);
        }
    }

    /**
     * Generate certificate PDF using mPDF (Better quality, no external dependencies)
     */
    public function generateMPDF(Certificate $certificate)
    {
        $certificate->load(['student', 'school']);

        // Array de meses em português
        $months = [
            1 => 'JANEIRO', 2 => 'FEVEREIRO', 3 => 'MARÇO', 4 => 'ABRIL',
            5 => 'MAIO', 6 => 'JUNHO', 7 => 'JULHO', 8 => 'AGOSTO',
            9 => 'SETEMBRO', 10 => 'OUTUBRO', 11 => 'NOVEMBRO', 12 => 'DEZEMBRO'
        ];

        // Preparar dados para o template
        $data = [
            'school_name' => $certificate->school_name,
            'school_cnpj' => $certificate->formatted_school_cnpj,
            'school_inep' => $certificate->school_inep,
            'school_address' => $certificate->school_address,
            'school_authorization' => $certificate->school_authorization,
            'authorization_date' => $certificate->authorization_date ?? '02/12/2022',
            'student_name' => $certificate->student_name,
            'student_cpf' => $certificate->formatted_student_cpf,
            'student_birth_day' => $certificate->student_birth_date ? $certificate->student_birth_date->format('d') : '30',
            'student_birth_month' => $certificate->student_birth_date_written,
            'student_birth_year' => $certificate->student_birth_date ? $certificate->student_birth_date->format('Y') : '2006',
            'student_birthplace' => $certificate->student_birth_place,
            'student_birth_state' => 'PIAUÍ',
            'student_nationality' => $certificate->student_nationality,
            'student_father' => $certificate->father_name,
            'student_mother' => $certificate->mother_name,
            'completion_year' => $certificate->completion_year,
            'course_level' => $certificate->course_level_label,
            'issue_location' => $certificate->issue_city,
            'issue_state' => $certificate->issue_state,
            'issue_day' => $certificate->issue_date ? $certificate->issue_date->format('d') : '08',
            'issue_month' => $certificate->issue_date ? $months[$certificate->issue_date->format('n')] : 'FEVEREIRO',
            'issue_year' => $certificate->issue_date ? $certificate->issue_date->format('Y') : '2024',
            'verification_code' => $certificate->verification_code,
            'school_type' => 'Centro Estadual de Tempo Integral - CETI',
            'school_short_name' => 'FRANCISCA TRINDADE',
            'authorization' => $certificate->school_authorization ?? '224/2022',
            'authorization_date' => '02/12/2022'
        ];

        // Passar tanto os dados individuais quanto o objeto certificate
        $data['certificate'] = $certificate;

        // Gerar HTML
        $html = view('certificates.pdf-mpdf', $data)->render();

        try {
            // Configurar mPDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L', // A4 Landscape
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'default_font' => 'arial',
                'debug' => false,
                'autoPageBreak' => false, // Desabilita quebra automática de página
                'keep_table_proportions' => true
            ]);

            // Desabilitar quebra de página
            $mpdf->autoPageBreak = false;

            // Definir metadados
            $mpdf->SetTitle('Certificado de Conclusão');
            $mpdf->SetAuthor($certificate->school_name);

            // Escrever HTML
            $mpdf->WriteHTML($html);

            // Gerar PDF
            $pdfContent = $mpdf->Output('', 'S');

            // Criar diretório se não existir
            $directory = 'certificates/' . date('Y');
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Salvar PDF
            $fileName = $directory . "/certificado_mpdf_{$certificate->certificate_number}.pdf";
            Storage::disk('public')->put($fileName, $pdfContent);

            // Atualizar caminho no banco
            $certificate->update(['pdf_path' => $fileName]);

            return $fileName;

        } catch (\Exception $e) {
            // Fallback para DomPDF se mPDF falhar
            return $this->generatePDFWithDomPDF($certificate);
        }
    }

    /**
     * Gera PDF usando o estilo idêntico ao preview
     */
    public function generatePreviewStylePDF(Certificate $certificate)
    {
        $certificate->load(['student', 'school']);

        // Array de meses em português
        $months = [
            1 => 'JANEIRO', 2 => 'FEVEREIRO', 3 => 'MARÇO', 4 => 'ABRIL',
            5 => 'MAIO', 6 => 'JUNHO', 7 => 'JULHO', 8 => 'AGOSTO',
            9 => 'SETEMBRO', 10 => 'OUTUBRO', 11 => 'NOVEMBRO', 12 => 'DEZEMBRO'
        ];

        // Preparar dados idênticos ao preview
        $data = [
            'school_name' => $certificate->school_name,
            'school_cnpj' => $certificate->formatted_school_cnpj,
            'school_inep' => $certificate->school_inep,
            'school_address' => $certificate->school_address,
            'school_authorization' => $certificate->school_authorization,
            'authorization_date' => $certificate->authorization_date ?? '02/12/2022',
            'student_name' => $certificate->student_name,
            'student_cpf' => $certificate->formatted_student_cpf,
            'student_birth_day' => $certificate->student_birth_date ? $certificate->student_birth_date->format('d') : '30',
            'student_birth_month' => $certificate->student_birth_date_written,
            'student_birth_year' => $certificate->student_birth_date ? $certificate->student_birth_date->format('Y') : '2006',
            'student_birthplace' => $certificate->student_birth_place,
            'student_birth_state' => 'PIAUÍ',
            'student_nationality' => $certificate->student_nationality,
            'student_father' => $certificate->father_name,
            'student_mother' => $certificate->mother_name,
            'completion_year' => $certificate->completion_year,
            'course_level' => $certificate->course_level_label,
            'issue_location' => $certificate->issue_city,
            'issue_state' => $certificate->issue_state,
            'issue_day' => $certificate->issue_date ? $certificate->issue_date->format('d') : '08',
            'issue_month' => $certificate->issue_date ? $months[$certificate->issue_date->format('n')] : 'FEVEREIRO',
            'issue_year' => $certificate->issue_date ? $certificate->issue_date->format('Y') : '2024',
            'verification_code' => $certificate->verification_code,
            'school_type' => 'Centro Estadual de Tempo Integral - CETI',
            'school_short_name' => 'FRANCISCA TRINDADE',
            'authorization' => $certificate->school_authorization ?? '224/2022'
        ];

        // Gerar HTML
        $html = view('certificates.pdf-preview-style', $data)->render();

        try {
            // Configurar mPDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 0,
                'margin_bottom' => 0,
                'default_font' => 'arial',
                'autoPageBreak' => false
            ]);

            $mpdf->WriteHTML($html);
            $pdfContent = $mpdf->Output('', 'S');

            // Salvar PDF
            $directory = 'certificates/' . date('Y');
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $fileName = $directory . "/certificado_preview_{$certificate->certificate_number}.pdf";
            Storage::disk('public')->put($fileName, $pdfContent);

            $certificate->update(['pdf_path' => $fileName]);
            return $fileName;

        } catch (\Exception $e) {
            return $this->generateMPDF($certificate);
        }
    }

    /**
     * Gera PDF usando microserviço Node.js (Puppeteer)
     */
    public function generateNodejsPDF(Certificate $certificate)
    {
        $certificate->load(['student', 'school']);

        // Array de meses em português
        $months = [
            1 => 'JANEIRO', 2 => 'FEVEREIRO', 3 => 'MARÇO', 4 => 'ABRIL',
            5 => 'MAIO', 6 => 'JUNHO', 7 => 'JULHO', 8 => 'AGOSTO',
            9 => 'SETEMBRO', 10 => 'OUTUBRO', 11 => 'NOVEMBRO', 12 => 'DEZEMBRO'
        ];

        // Preparar dados
        $data = [
            'school_name' => $certificate->school_name,
            'school_cnpj' => $certificate->formatted_school_cnpj,
            'school_inep' => $certificate->school_inep,
            'school_address' => $certificate->school_address,
            'school_authorization' => $certificate->school_authorization,
            'authorization_date' => $certificate->authorization_date ?? '02/12/2022',
            'student_name' => $certificate->student_name,
            'student_cpf' => $certificate->formatted_student_cpf,
            'student_birth_day' => $certificate->student_birth_date ? $certificate->student_birth_date->format('d') : '30',
            'student_birth_month' => $certificate->student_birth_date_written,
            'student_birth_year' => $certificate->student_birth_date ? $certificate->student_birth_date->format('Y') : '2006',
            'student_birthplace' => $certificate->student_birth_place,
            'student_birth_state' => 'PIAUÍ',
            'student_nationality' => $certificate->student_nationality,
            'student_father' => $certificate->father_name,
            'student_mother' => $certificate->mother_name,
            'completion_year' => $certificate->completion_year,
            'course_level' => $certificate->course_level_label,
            'issue_location' => $certificate->issue_city,
            'issue_state' => $certificate->issue_state,
            'issue_day' => $certificate->issue_date ? $certificate->issue_date->format('d') : '08',
            'issue_month' => $certificate->issue_date ? $months[$certificate->issue_date->format('n')] : 'FEVEREIRO',
            'issue_year' => $certificate->issue_date ? $certificate->issue_date->format('Y') : '2024',
            'verification_code' => $certificate->verification_code,
            'school_type' => 'Centro Estadual de Tempo Integral - CETI',
            'school_short_name' => 'FRANCISCA TRINDADE',
            'authorization' => $certificate->school_authorization ?? '224/2022'
        ];

        // Criar HTML diretamente (mais confiável que usar a view complexa)
        $html = $this->generateCleanCertificateHtml($data);

        try {
            // Determinar URL do microserviço baseado no ambiente
            $pdfServiceUrl = $this->getPdfServiceUrl();

            // Log para debug
            Log::info('Enviando HTML para Node.js PDF service', [
                'html_length' => strlen($html),
                'certificate_id' => $certificate->id,
                'service_url' => $pdfServiceUrl
            ]);

            // Chamar o microserviço Node.js
            $response = Http::timeout(60)->post($pdfServiceUrl . '/generate-pdf', [
                'html' => $html,
                'options' => [
                    'format' => 'A4',
                    'landscape' => true,
                    'margin' => [
                        'top' => '10mm',
                        'right' => '10mm',
                        'bottom' => '10mm',
                        'left' => '10mm'
                    ],
                    'printBackground' => true,
                    'preferCSSPageSize' => true
                ]
            ]);

            if (!$response->successful()) {
                throw new \Exception('PDF service returned error: ' . $response->body());
            }

            $result = $response->json();

            if (!isset($result['success']) || !$result['success']) {
                throw new \Exception('PDF generation failed: ' . ($result['message'] ?? 'Unknown error'));
            }

            // Decodificar PDF base64
            $pdfContent = base64_decode($result['pdf']);

            // Salvar PDF
            $directory = 'certificates/' . date('Y');
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $fileName = $directory . "/certificado_nodejs_{$certificate->certificate_number}.pdf";
            Storage::disk('public')->put($fileName, $pdfContent);

            $certificate->update(['pdf_path' => $fileName]);

            Log::info('Node.js PDF generated successfully', [
                'file_name' => $fileName,
                'certificate_id' => $certificate->id,
                'service_url' => $pdfServiceUrl
            ]);

            return $fileName;

        } catch (\Exception $e) {
            // Log do erro
            Log::error('Nodejs PDF generation failed: ' . $e->getMessage(), [
                'certificate_id' => $certificate->id,
                'service_url' => $pdfServiceUrl ?? 'unknown'
            ]);

            // Fallback para mPDF
            return $this->generatePreviewStylePDF($certificate);
        }
    }

    /**
     * Determina a URL do microserviço PDF baseado no ambiente
     */
    private function getPdfServiceUrl()
    {
        // Verificar variável de ambiente primeiro
        $envUrl = env('PDF_SERVICE_URL');
        if ($envUrl) {
            return rtrim($envUrl, '/');
        }

        // Detectar ambiente baseado na URL da aplicação
        $appUrl = config('app.url');

        if (str_contains($appUrl, 'localhost') || str_contains($appUrl, '127.0.0.1')) {
            // Desenvolvimento local
            return 'http://localhost:3001';
        } else {
            // Produção - assumir que o microserviço está no mesmo servidor
            $parsedUrl = parse_url($appUrl);
            $host = $parsedUrl['host'];
            return "http://{$host}:3001";
        }
    }

    /**
     * Gera HTML limpo e funcional para o certificado
     */
    private function generateCleanCertificateHtml($data)
    {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificado</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: white;
        }

        .certificate-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            background: white;
        }

        .certificate-border {
            border: 30px solid #1e40af;
            padding: 20px;
            position: relative;
            min-height: 500px;
        }

        .certificate-border::before {
            content: "";
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border: 6px solid #1e40af;
        }

        .decorative-circles {
            position: absolute;
            width: 15px;
            height: 15px;
            background: #1e40af;
            border-radius: 50%;
        }

        .circle-top-left { top: 45px; left: 45px; }
        .circle-top-right { top: 45px; right: 45px; }
        .circle-bottom-left { bottom: 45px; left: 45px; }
        .circle-bottom-right { bottom: 45px; right: 45px; }

        .brasao {
            position: absolute;
            top: 50px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 30px;
            background: radial-gradient(circle, #1e40af 0%, #1d4ed8 50%, #1e3a8a 100%);
            border-radius: 50%;
            border: 3px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 4px;
            text-align: center;
            line-height: 1;
        }

        .content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 60px 40px 20px;
        }

        h1 {
            color: #1e40af;
            font-size: 11px;
            font-weight: bold;
            margin: 2px 0;
            line-height: 1.2;
        }

        h2 {
            color: #1e40af;
            font-size: 14px;
            font-weight: bold;
            margin: 15px 0;
            text-transform: uppercase;
        }

        .school-info {
            font-size: 8px;
            margin: 8px 0;
            font-weight: bold;
        }

        .main-content {
            font-size: 9px;
            text-align: justify;
            margin: 15px 0;
            line-height: 1.4;
        }

        .student-name {
            font-weight: bold;
            text-decoration: underline;
            color: #1e40af;
        }

        .location-date {
            font-size: 9px;
            margin: 15px 0;
            font-weight: bold;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            margin-bottom: 10px;
        }

        .signature {
            flex: 1;
            text-align: center;
            margin: 0 10px;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            height: 20px;
            margin-bottom: 5px;
        }

        .signature-title {
            font-size: 7px;
            font-weight: bold;
        }

        .verification {
            text-align: right;
            font-size: 6px;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-border">
            <!-- Círculos decorativos -->
            <div class="decorative-circles circle-top-left"></div>
            <div class="decorative-circles circle-top-right"></div>
            <div class="decorative-circles circle-bottom-left"></div>
            <div class="decorative-circles circle-bottom-right"></div>

            <!-- Brasão -->
            <div class="brasao">
                BRASÃO<br>DO<br>BRASIL
            </div>

            <div class="content">
                <h1>REPÚBLICA FEDERATIVA DO BRASIL</h1>
                <h1>ESTADO DO PIAUÍ</h1>
                <h1>SECRETARIA DE ESTADO DA EDUCAÇÃO</h1>

                <div class="school-info">
                    CNPJ N° ' . ($data['school_cnpj'] ?? '08.055.298/0001-49') . ' - INEP N° ' . ($data['school_inep'] ?? '22136703') . '
                </div>

                <div class="school-info">
                    ' . ($data['school_name'] ?? 'CENTRO ESTADUAL DE TEMPO INTEGRAL FRANCISCA TRINDADE') . '<br>
                    ' . ($data['school_address'] ?? 'RUA DO ARAME, S/N – BAIRRO SANTINHO – CEP: 64.100-000 – BARRAS-PI') . '
                </div>

                <div class="school-info">
                    Autorização de Funcionamento pela resolução CEE/PI N°__ ' . ($data['authorization'] ?? '224/2022') . ' de ' . ($data['authorization_date'] ?? '02/12/2022') . '
                </div>

                <h2>CERTIFICADO DE CONCLUSÃO DO ENSINO ' . ($data['course_level'] ?? 'MÉDIO') . '</h2>

                <div class="main-content">
                    A Direção do ' . ($data['school_type'] ?? 'Centro Estadual de Tempo Integral - CETI') . ' <strong>' . ($data['school_short_name'] ?? 'FRANCISCA TRINDADE') . '</strong> no uso de suas atribuições legais confere a <span class="student-name">' . ($data['student_name'] ?? 'ALUNO EXEMPLO DA SILVA') . '</span>, CPF ' . ($data['student_cpf'] ?? '000.000.000-00') . ', nascido (a) em ' . ($data['student_birth_day'] ?? '30') . ' de ' . ($data['student_birth_month'] ?? 'JULHO') . ' de ' . ($data['student_birth_year'] ?? '2006') . ', natural de ' . ($data['student_birthplace'] ?? 'BARRAS') . ', Estado de (o) ' . ($data['student_birth_state'] ?? 'PIAUÍ') . ', nacionalidade ' . ($data['student_nationality'] ?? 'BRASILEIRA') . ', filho (a) de ' . ($data['student_father'] ?? 'FRANCISCO DAS CHAGAS FURTADO MACHADO') . ' e de ' . ($data['student_mother'] ?? 'MARIA DA CONCEIÇÃO FERREIRA DA SILVA') . ', o presente certificado por ter concluído no ano ' . ($data['completion_year'] ?? '2023') . ' o Ensino ' . ($data['course_level'] ?? 'MÉDIO') . ', para que possa gozar de todos os direitos e prerrogativas concedidas pelas leis do País.
                </div>

                <div class="location-date">
                    ' . ($data['issue_location'] ?? 'BARRAS') . ' - ' . ($data['issue_state'] ?? 'PI') . ', ' . ($data['issue_day'] ?? '08') . ' de ' . ($data['issue_month'] ?? 'FEVEREIRO') . ' de ' . ($data['issue_year'] ?? '2024') . '.
                </div>

                <div class="signatures">
                    <div class="signature">
                        <div class="signature-line"></div>
                        <div class="signature-title">SECRETÁRIO(A)</div>
                    </div>
                    <div class="signature">
                        <div class="signature-line"></div>
                        <div class="signature-title">DIRETOR(A)</div>
                    </div>
                    <div class="signature">
                        <div class="signature-line"></div>
                        <div class="signature-title">CONCLUINTE</div>
                    </div>
                </div>

                <div class="verification">
                    Código de verificação: ' . ($data['verification_code'] ?? 'CERT-' . date('Y') . '-12345') . '
                </div>
            </div>
        </div>
    </div>
</body>
</html>';

        return $html;
    }

    /**
     * Generate certificate PDF (Nova hierarquia)
     */
    public function generatePDF(Certificate $certificate)
    {
        // 1º. Tentar Node.js (melhor qualidade)
        try {
            return $this->generateNodejsPDF($certificate);
        } catch (\Exception $e) {
            // 2º. Fallback para preview style
            try {
                return $this->generatePreviewStylePDF($certificate);
            } catch (\Exception $e2) {
                // 3º. Fallback para mPDF simples
                try {
                    return $this->generateMPDF($certificate);
                } catch (\Exception $e3) {
                    // 4º. Fallback final para DomPDF
                    return $this->generatePDFWithDomPDF($certificate);
                }
            }
        }
    }

    /**
     * Generate certificate PDF with DomPDF (Fallback)
     */
    public function generatePDFWithDomPDF(Certificate $certificate)
    {
        $certificate->load(['student', 'school']);

        // Array de meses em português
        $months = [
            1 => 'JANEIRO', 2 => 'FEVEREIRO', 3 => 'MARÇO', 4 => 'ABRIL',
            5 => 'MAIO', 6 => 'JUNHO', 7 => 'JULHO', 8 => 'AGOSTO',
            9 => 'SETEMBRO', 10 => 'OUTUBRO', 11 => 'NOVEMBRO', 12 => 'DEZEMBRO'
        ];

        // Preparar dados para o template
        $data = [
            'school_name' => $certificate->school_name,
            'school_cnpj' => $certificate->formatted_school_cnpj,
            'school_inep' => $certificate->school_inep,
            'school_address' => $certificate->school_address,
            'school_authorization' => $certificate->school_authorization,
            'authorization_date' => $certificate->authorization_date ?? '02/12/2022',
            'student_name' => $certificate->student_name,
            'student_cpf' => $certificate->formatted_student_cpf,
            'student_birth_day' => $certificate->student_birth_date ? $certificate->student_birth_date->format('d') : '30',
            'student_birth_month' => $certificate->student_birth_date_written ?? 'JULHO',
            'student_birth_year' => $certificate->student_birth_date ? $certificate->student_birth_date->format('Y') : '2006',
            'student_birthplace' => $certificate->student_birth_place ?? 'BARRAS',
            'student_birth_state' => 'PIAUÍ',
            'student_nationality' => $certificate->student_nationality ?? 'BRASILEIRA',
            'student_father' => $certificate->father_name ?? 'FRANCISCO DAS CHAGAS FURTADO MACHADO',
            'student_mother' => $certificate->mother_name ?? 'MARIA DA CONCEIÇÃO FERREIRA DA SILVA',
            'completion_year' => $certificate->completion_year ?? '2023',
            'course_level' => $certificate->course_level_label ?? 'MÉDIO',
            'issue_location' => $certificate->issue_city ?? 'BARRAS',
            'issue_state' => $certificate->issue_state ?? 'PI',
            'issue_day' => $certificate->issue_date ? $certificate->issue_date->format('d') : '08',
            'issue_month' => $certificate->issue_date ? $months[$certificate->issue_date->format('n')] : 'FEVEREIRO',
            'issue_year' => $certificate->issue_date ? $certificate->issue_date->format('Y') : '2024',
            'verification_code' => $certificate->verification_code ?? 'CERT-2024-12345',
            'school_type' => 'Centro Estadual de Tempo Integral - CETI',
            'school_short_name' => 'FRANCISCA TRINDADE',
            'authorization' => '224/2022',
            'authorization_date' => '02/12/2022'
        ];

        $dompdf = new Dompdf();

        // Passar tanto os dados individuais quanto o objeto certificate
        $data['certificate'] = $certificate;

        $html = view('certificates.pdf', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->render();

        // Criar diretório se não existir
        $directory = 'certificates/' . date('Y');
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Salvar PDF
        $fileName = $directory . "/certificado_{$certificate->certificate_number}.pdf";
        Storage::disk('public')->put($fileName, $dompdf->output());

        // Atualizar caminho no banco
        $certificate->update(['pdf_path' => $fileName]);

        return $fileName;
    }

    /**
     * Verify certificate by code
     */
    public function verify(Request $request)
    {
        if ($request->method() === 'POST') {
            $request->validate([
                'verification_code' => 'required|string|max:50',
            ]);

            $certificate = Certificate::findByVerificationCode($request->verification_code);

            if (!$certificate) {
                return redirect()->back()
                    ->with('error', 'Código de verificação não encontrado.')
                    ->withInput();
            }

            return view('certificates.verify-result', compact('certificate'));
        }

        return view('certificates.verify');
    }

    /**
     * Cancel certificate
     */
    public function cancel(Certificate $certificate)
    {
        try {
            $certificate->update(['status' => 'cancelado']);

            return redirect()->back()
                ->with('success', 'Certificado cancelado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao cancelar certificado: ' . $e->getMessage());
        }
    }

    /**
     * Reissue certificate
     */
    public function reissue(Certificate $certificate)
    {
        try {
            DB::beginTransaction();

            // Marcar certificado original como reemitido
            $certificate->update(['status' => 'reemitido']);

            // Criar novo certificado
            $newCertificateData = $certificate->toArray();
            unset($newCertificateData['id'], $newCertificateData['created_at'], $newCertificateData['updated_at']);

            $newCertificate = new Certificate();
            $newCertificateData['certificate_number'] = $newCertificate->generateCertificateNumber();
            $newCertificateData['verification_code'] = $newCertificate->generateVerificationCode();
            $newCertificateData['issue_date'] = now();
            $newCertificateData['status'] = 'emitido';
            $newCertificateData['pdf_path'] = null;

            $newCertificate = Certificate::create($newCertificateData);
            $newCertificate->hash = $newCertificate->generateHash();
            $newCertificate->save();

            // Gerar PDF do novo certificado
            $this->generatePDF($newCertificate);

            DB::commit();

            return redirect()->route('certificates.show', $newCertificate)
                ->with('success', 'Certificado reemitido com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Erro ao reemitir certificado: ' . $e->getMessage());
        }
    }

    /**
     * Get students by school (AJAX)
     */
    public function getStudentsBySchool(Request $request, School $school)
    {
        $students = $school->students()
            ->where('status', 'formado')
            ->orderBy('name')
            ->get(['id', 'name', 'enrollment', 'grade']);

        return response()->json($students);
    }

        /**
     * Exibe o certificado em formato landscape (orientação horizontal)
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showLandscape($id)
    {
        $certificate = Certificate::findOrFail($id);

        // Carrega relacionamentos necessários
        $certificate->load(['student', 'school']);

        return view('certificates.new-pdf-landscape', compact('certificate'));
    }

        /**
     * Gera PDF do certificado em formato landscape
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function downloadLandscapePdf($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->load(['student', 'school']);

        $pdf = PDF::loadView('certificates.new-pdf-landscape', compact('certificate'))
                  ->setPaper('a4', 'landscape')
                  ->setOptions([
                      'dpi' => 150,
                      'defaultFont' => 'serif',
                      'isRemoteEnabled' => true,
                      'enable-local-file-access' => true
                  ]);

        $filename = 'certificado_' . $certificate->student->name . '_landscape.pdf';

        return $pdf->download($filename);
    }

        /**
     * Visualiza o certificado em formato landscape via stream
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function streamLandscape($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->load(['student', 'school']);

        $pdf = PDF::loadView('certificates.new-pdf-landscape', compact('certificate'))
                  ->setPaper('a4', 'landscape')
                  ->setOptions([
                      'dpi' => 150,
                      'defaultFont' => 'serif'
                  ]);

        return $pdf->stream('certificado_landscape.pdf');
    }
}
