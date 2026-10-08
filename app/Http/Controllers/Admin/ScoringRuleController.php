<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScoringRule;
use App\Models\ScoringComponent;
use Illuminate\Http\Request;
use App\Models\Tests;

class ScoringRuleController extends Controller
{
    public function index()
    {
        $tests = Tests::all();
        $rules = ScoringRule::with(['components', 'test'])->get();
        
        return view('admin.scoring.index', compact('tests', 'rules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'test_id' => 'required|exists:tests,id',
            'formula' => 'required|string',
        ]);

        $rule = ScoringRule::create([
            'test_id' => $request->test_id,
            'formula' => $request->formula,
            'description' => $request->description,
        ]);

        if ($request->components) {
            foreach ($request->components as $component) {
                if (!empty($component)) {
                    ScoringComponent::create([
                        'scoring_rule_id' => $rule->id,
                        'key' => $component,
                        'description' => 'Variable ' . $component,
                    ]);
                }
            }
        }

        return redirect()->route('scoring.index')
            ->with('success', 'Scoring rule berhasil dibuat');
    }

    public function edit($id)
    {
        $rule = ScoringRule::with('components')->findOrFail($id);
        $tests = Tests::all();
        
        return view('admin.scoring.edit', compact('rule', 'tests'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'test_id' => 'required|exists:tests,id',
            'formula' => 'required|string',
        ]);

        $rule = ScoringRule::findOrFail($id);
        
        $rule->update([
            'test_id' => $request->test_id,
            'formula' => $request->formula,
            'description' => $request->description,
        ]);

        $rule->components()->delete();
        
        if ($request->components) {
            foreach ($request->components as $component) {
                if (!empty($component)) {
                    ScoringComponent::create([
                        'scoring_rule_id' => $rule->id,
                        'key' => $component,
                        'description' => 'Variable ' . $component,
                    ]);
                }
            }
        }

        return redirect()->route('scoring.index')
            ->with('success', 'Scoring rule berhasil diperbarui');
    }

    public function destroy($id)
    {
        $rule = ScoringRule::findOrFail($id);
        $rule->delete();
        
        return redirect()->route('scoring.index')
            ->with('success', 'Scoring rule berhasil dihapus');
    }
}