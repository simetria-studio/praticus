<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = School::query();

        // Se for coordenador, filtrar apenas pela escola responsável
        if ($user->isCoordinator()) {
            $query->where('id', $user->coordinator->school_id);
        }

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('cnpj', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('level')) {
            $query->byLevel($request->level);
        }

        if ($request->filled('active')) {
            $query->where('active', $request->active);
        }

        $schools = $query->orderBy('name')->paginate(10);

        return view('schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Coordenadores não podem criar escolas
        if ($user->isCoordinator()) {
            abort(403, 'Coordenadores não podem criar escolas.');
        }

        return view('schools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Coordenadores não podem criar escolas
        if ($user->isCoordinator()) {
            abort(403, 'Coordenadores não podem criar escolas.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:schools,code',
            'cnpj' => 'nullable|string|max:18|unique:schools,cnpj',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:publica,privada,federal,estadual,municipal',
            'level' => 'required|in:infantil,fundamental,medio,superior,tecnico,todos',
            'active' => 'boolean',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|size:2',
            'postal_code' => 'required|string|max:10',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        try {
            DB::beginTransaction();

            $school = School::create($validated);

            DB::commit();

            return redirect()->route('schools.index')
                ->with('success', 'Escola cadastrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar escola: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        $user = Auth::user();

        // Coordenadores só podem ver escolas que coordenam
        if ($user->isCoordinator() && $school->id !== $user->coordinator->school_id) {
            abort(403, 'Você não tem permissão para visualizar esta escola.');
        }

        return view('schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        $user = Auth::user();

        // Coordenadores só podem editar escolas que coordenam
        if ($user->isCoordinator() && $school->id !== $user->coordinator->school_id) {
            abort(403, 'Você não tem permissão para editar esta escola.');
        }

        return view('schools.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        $user = Auth::user();

        // Coordenadores só podem editar escolas que coordenam
        if ($user->isCoordinator() && $school->id !== $user->coordinator->school_id) {
            abort(403, 'Você não tem permissão para editar esta escola.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['nullable', 'string', 'max:50', Rule::unique('schools', 'code')->ignore($school->id)],
            'cnpj' => ['nullable', 'string', 'max:18', Rule::unique('schools', 'cnpj')->ignore($school->id)],
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:publica,privada,federal,estadual,municipal',
            'level' => 'required|in:infantil,fundamental,medio,superior,tecnico,todos',
            'active' => 'boolean',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|size:2',
            'postal_code' => 'required|string|max:10',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        try {
            DB::beginTransaction();

            $school->update($validated);

            DB::commit();

            return redirect()->route('schools.index')
                ->with('success', 'Escola atualizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar escola: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        $user = Auth::user();

        // Coordenadores só podem deletar escolas que coordenam
        if ($user->isCoordinator() && $school->id !== $user->coordinator->school_id) {
            abort(403, 'Você não tem permissão para deletar esta escola.');
        }

        try {
            DB::beginTransaction();

            $school->delete();

            DB::commit();

            return redirect()->route('schools.index')
                ->with('success', 'Escola excluída com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Erro ao excluir escola: ' . $e->getMessage());
        }
    }

    /**
     * Toggle school active status
     */
    public function toggleActive(School $school)
    {
        $user = Auth::user();

        // Coordenadores só podem alterar status de escolas que coordenam
        if ($user->isCoordinator() && $school->id !== $user->coordinator->school_id) {
            abort(403, 'Você não tem permissão para alterar o status desta escola.');
        }

        try {
            $school->update(['active' => !$school->active]);

            $status = $school->active ? 'ativada' : 'desativada';

            return response()->json([
                'success' => true,
                'message' => "Escola {$status} com sucesso!",
                'active' => $school->active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status da escola: ' . $e->getMessage()
            ], 500);
        }
    }
}
