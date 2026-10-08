<?php

namespace App\Services;

use App\Models\ScoringRule;
use App\Models\ScoringComponent;
use App\Models\Attempt;
use App\Models\IqRange;
use App\Models\AttemptTypeTest;
use App\Models\AttemptSubTest;
use Exception;
use Illuminate\Support\Facades\Log;

class ScoringService
{
    public static function calculateScore($testId, $variables = [])
    {
        $scoringRule = ScoringRule::with('components')->where('test_id', $testId)->first();
        if (!$scoringRule || empty(trim($scoringRule->formula))) {
            return self::calculateDefaultScore($variables);
        }
        
        try {
            $formula = trim($scoringRule->formula);
            if (empty($formula) || $formula === '* 1' || $formula === '*1') {
                return self::calculateDefaultScore($variables);
            }     
            $originalFormula = $formula;
            $formula = self::normalizeFormula($formula);
            $variableMappings = self::buildVariableMappings($variables);
            $components = $scoringRule->components;
            foreach ($components as $component) {
                $key = $component->key;
                if (isset($variables[$key])) {
                    $value = $variables[$key];
                    $keyVariations = self::generateVariableVariations($key);
                    foreach ($keyVariations as $variation) {
                        $variableMappings[$variation] = $value;
                    }
                }
            }
            $variableMappings = self::addSpecialVariations($variableMappings, $variables);
            uksort($variableMappings, function($a, $b) {
                return strlen($b) - strlen($a);
            });
            $formula = self::replaceVariablesInFormula($formula, $variableMappings);
            $formula = self::cleanFormula($formula);
            if (self::isFormulaInvalid($formula)) {
                return self::calculateDefaultScore($variables);
            }
            $result = self::evaluateMathExpression($formula);
            
            if (!is_numeric($result)) {
                return self::calculateDefaultScore($variables);
            }
            
            return floatval($result);
            
        } catch (Exception $e) {
            return self::calculateDefaultScore($variables);
        }
    }

    private static function extractVariablesFromFormula($formula)
    {
        preg_match_all('/[a-zA-Z_][a-zA-Z0-9_\s]*[a-zA-Z0-9_]/', $formula, $matches);
        return array_unique($matches[0] ?? []);
    }

    private static function replaceVariablesInFormula($formula, $mappings)
    {
        uksort($mappings, function($a, $b) {
            return strlen($b) - strlen($a);
        });
        foreach ($mappings as $varName => $value) {
            if (!is_numeric($value)) continue;
            $patterns = [
                '/\b' . preg_quote($varName, '/') . '\b/i',
                '/\b' . preg_quote(str_replace('_', ' ', $varName), '/') . '\b/i',
                '/\b' . preg_quote(str_replace(' ', '_', $varName), '/') . '\b/i',
                '/\b' . preg_quote(preg_replace('/[_\s]/', '', $varName), '/') . '\b/i',
            ];
            
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $formula)) {
                    $formula = preg_replace($pattern, (string)$value, $formula);
                    break;
                }
            }
        }
        $formula = preg_replace_callback(
            '/[a-zA-Z_][a-zA-Z0-9_\s]*[a-zA-Z0-9_]/',
            function($matches) use ($mappings) {
                $varName = trim($matches[0]);
                foreach ($mappings as $mapKey => $mapValue) {
                    if (strcasecmp(trim($mapKey), trim($varName)) === 0) {
                        return (string)$mapValue;
                    }
                }
                
                foreach ($mappings as $mapKey => $mapValue) {
                    if (stripos($varName, $mapKey) !== false || stripos($mapKey, $varName) !== false) {
                        return (string)$mapValue;
                    }
                }
                
                return '0';
            },
            $formula
        );
        
        return $formula;
    }

    private static function normalizeFormula($formula)
    {
        $formula = str_replace(['（', '）', '〔', '〕', '［', '］', '{', '}'], ['(', ')', '(', ')', '(', ')', '(', ')'], $formula);
        $formula = preg_replace('/\s+/', ' ', $formula);
        $formula = preg_replace('/([a-zA-Z_])([a-zA-Z_])/', '$1 $2', $formula);
        $formula = preg_replace('/([a-zA-Z_])(\d)/', '$1 $2', $formula);
        
        return trim($formula);
    }
    
    private static function addSpecialVariations($mappings, $variables)
    {
        $criticalVariables = [
            'sinonim', 'antonim', 'analogi', 'pengelompokan_kata',
            'verbal_umum_pemahaman_wacana', 'logika_umum', 'silogisme',
            'logika_cerita', 'logika_diagram', 'aritmatika',
            'seri_angka', 'seri_huruf', 'logika_angka',
            'logika_dalam_cerita', 'seri_gambar',
            'hubungan_konsistensi_logis', 'pengelompokan_gambar',
            'spasial_bayangan_gambar', 'identifikasi_gambar'
        ];
        
        foreach ($criticalVariables as $var) {
            if (isset($mappings[$var])) continue;
            $foundValue = null;
            if (isset($variables[$var])) {
                $foundValue = $variables[$var];
            }
            if ($foundValue === null) {
                $variations = self::generateAllVariations($var);
                foreach ($variations as $variation) {
                    if (isset($variables[$variation])) {
                        $foundValue = $variables[$variation];
                        break;
                    }
                }
            }
            if ($foundValue === null) {
                foreach ($variables as $key => $value) {
                    if (stripos($key, $var) !== false) {
                        $foundValue = $value;
                        break;
                    }
                }
            }
            if ($foundValue !== null && is_numeric($foundValue)) {
                $mappings[$var] = $foundValue;
                $variations = self::generateAllVariations($var);
                foreach ($variations as $variation) {
                    $mappings[$variation] = $foundValue;
                }
            }
        }
        
        return $mappings;
    }

    private static function buildVariableMappings($variables)
    {
        $mappings = [];
        
        foreach ($variables as $key => $value) {
            if (is_numeric($value)) {
                $mappings[$key] = $value;
                $variations = self::generateAllVariations($key);
                foreach ($variations as $variation) {
                    $mappings[$variation] = $value;
                }
                self::addCategoryMappings($mappings, $key, $value);
            }
        }
        
        return $mappings;
    }
    
    private static function addCategoryMappings(&$mappings, $key, $value)
    {
        $keyLower = strtolower($key);
        if (strpos($keyLower, 'verbal') !== false || 
            strpos($keyLower, 'sinonim') !== false ||
            strpos($keyLower, 'antonim') !== false ||
            strpos($keyLower, 'analogi') !== false) {
            $mappings['verbal'] = $value;
            $mappings['verbal_score'] = $value;
        }
        if (strpos($keyLower, 'logika') !== false || 
            strpos($keyLower, 'logical') !== false ||
            strpos($keyLower, 'silogisme') !== false) {
            $mappings['logika'] = $value;
            $mappings['logika_score'] = $value;
            $mappings['logical'] = $value;
        }
        if (strpos($keyLower, 'numerik') !== false || 
            strpos($keyLower, 'numerical') !== false ||
            strpos($keyLower, 'angka') !== false ||
            strpos($keyLower, 'aritmatika') !== false) {
            $mappings['numerik'] = $value;
            $mappings['numerik_score'] = $value;
            $mappings['numerical'] = $value;
        }
        if (strpos($keyLower, 'gambar') !== false || 
            strpos($keyLower, 'image') !== false ||
            strpos($keyLower, 'visual') !== false ||
            strpos($keyLower, 'spasial') !== false) {
            $mappings['gambar'] = $value;
            $mappings['gambar_score'] = $value;
            $mappings['image'] = $value;
        }
    }

    private static function generateAllVariations($name)
    {
        if (empty($name)) return [];
        
        $variations = [];
        $name = trim($name);
        $variations[] = $name;
        $variations[] = strtolower($name);
        $variations[] = strtoupper($name);
        $normalized = self::normalizeVariableName($name);
        if ($normalized !== strtolower($name)) {
            $variations[] = $normalized;
        }
        $withSpaces = str_replace('_', ' ', $normalized);
        if ($withSpaces !== $normalized) {
            $variations[] = $withSpaces;
            $variations[] = ucwords($withSpaces);
        }
        $withoutSeparators = preg_replace('/[_\s]/', '', $normalized);
        if ($withoutSeparators !== $normalized) {
            $variations[] = $withoutSeparators;
        }
        $camelCase = lcfirst(str_replace(' ', '', ucwords(strtolower($withSpaces))));
        if ($camelCase !== $withoutSeparators) {
            $variations[] = $camelCase;
        }
        if (preg_match('/(.*?)(?:\s*[vV]1|\s*V\s*1)$/', $normalized, $matches)) {
            $withoutV1 = trim($matches[1]);
            if ($withoutV1) {
                $variations[] = $withoutV1;
                $variations[] = str_replace('_', ' ', $withoutV1);
            }
        }

        return array_values(array_unique(array_filter($variations)));
    }
    
    private static function generateVariableVariations($name)
    {
        return self::generateAllVariations($name);
    }
    
    private static function normalizeVariableName($name)
    {
        if (empty($name)) return $name;
        $name = strtolower(trim($name));
        $name = preg_replace('/\s+/', '_', $name);
        $name = preg_replace('/[^a-z0-9_]/', '', $name);
        $name = preg_replace('/_+/', '_', $name);
        
        return trim($name, '_');
    }

    private static function cleanFormula($formula)
    {
        $formula = preg_replace('/\s+/', ' ', $formula);
        $formula = preg_replace('/\s*([\+\-\*\/\(\)])\s*/', '$1', $formula);
        $formula = preg_replace('/\+\+/', '+', $formula);
        $formula = preg_replace('/\-\-/', '+', $formula);
        $formula = preg_replace('/\+\-/', '-', $formula);
        $formula = preg_replace('/\-\+/', '-', $formula);
        if (preg_match('/^[\*\/\+]/', $formula)) {
            $formula = '0' . $formula;
        }
        
        if (preg_match('/[\+\-\*\/]$/', $formula)) {
            $formula .= '0';
        }
        
        return trim($formula);
    }
    
    private static function isFormulaInvalid($formula)
    {
        if (empty($formula)) return true;
        if (!preg_match('/\d/', $formula)) return true;
        if (preg_match('/[a-zA-Z_]/', $formula)) return true; 
        return false;
    }

    public static function calculateAttemptScore(Attempt $attempt)
    {
        $testId = $attempt->test_id;
        $variables = self::collectAttemptVariables($attempt);
        $totalScore = self::calculateScore($testId, $variables);
        if ($totalScore == 0 && isset($variables['total_correct']) && $variables['total_correct'] > 0) {
            $totalScore = $variables['total_correct'];
        }

        $iqScore = null;
        $iqCategory = null;
        $testType = strtoupper(trim($attempt->tests->type ?? ''));
        $testLabel = ($attempt->tests->name ?? '') . ' ' . ($attempt->tests->slug ?? '');
        $isTpa = $testType !== ''
            ? $testType === 'TPA'
            : (bool) preg_match('/\btpa\b|potensi[\s-]+akademik/i', $testLabel);

        if ($isTpa && $totalScore > 0) {
            $iqRange = IqRange::where('test_type', 'TPA')
                ->where('raw_min', '<=', $totalScore)
                ->where('raw_max', '>=', $totalScore)
                ->first();

            if ($iqRange) {
                $iqScore = $iqRange->iq_score;
                $iqCategory = $iqRange->category;
            }
        }

        $attempt->update([
            'score' => $totalScore,
            'iq_score' => $iqScore,
            'iq_category' => $iqCategory,
            'total_correct' => $variables['total_correct'] ?? 0,
            'total_question' => $variables['total_question'] ?? 0,
        ]);

        return $totalScore;
    }

    private static function collectAttemptVariables(Attempt $attempt)
    {
        $typeAttempts = $attempt->typeAttempts()
            ->with(['typeTest', 'subAnswers.subTest'])
            ->get();
        
        $variables = [
            'total_correct' => 0,
            'total_question' => 0,
            'total_score' => 0,
        ];
        
        $allScores = [];
        
        foreach ($typeAttempts as $typeAttempt) {
            $typeVariables = self::collectTypeTestVariables($typeAttempt);
            $variables['total_correct'] += $typeVariables['correct'] ?? 0;
            $variables['total_question'] += $typeVariables['total'] ?? 0;
            $variables['total_score'] += $typeVariables['score'] ?? 0;
            if ($typeAttempt->typeTest) {
                $typeName = $typeAttempt->typeTest->name;
                $subAttempts = $typeAttempt->subAnswers;
                foreach ($subAttempts as $subAttempt) {
                    if ($subAttempt->subTest) {
                        $subName = $subAttempt->subTest->name;
                        $subCorrect = $subAttempt->correct_count ?? 0;
                        $nameVariations = self::generateAllVariations($subName);
                        foreach ($nameVariations as $variation) {
                            $variables[$variation] = $subCorrect;
                            $allScores[$variation] = $subCorrect;
                        }
                    }
                }
            }
        }
        
        return $variables;
    }

    private static function collectTypeTestVariables(AttemptTypeTest $attemptTypeTest)
    {
        $subAttempts = $attemptTypeTest->subAnswers()->with('subTest')->get();
        
        $variables = [
            'correct' => 0,
            'wrong' => 0,
            'total' => 0,
            'score' => 0,
        ];

        foreach ($subAttempts as $subAttempt) {
            $subVariables = self::collectSubTestVariables($subAttempt);
            
            $variables['correct'] += $subVariables['correct'] ?? 0;
            $variables['wrong'] += $subVariables['wrong'] ?? 0;
            $variables['total'] += $subVariables['total'] ?? 0;
            $variables['score'] += $subVariables['score'] ?? 0;
        }

        return $variables;
    }

    private static function collectSubTestVariables(AttemptSubTest $attemptSubTest)
    {
        $correct = $attemptSubTest->correct_count ?? 0;
        $wrong = $attemptSubTest->wrong_count ?? 0;
        $total = $correct + $wrong;
        
        $variables = [
            'correct' => $correct,
            'wrong' => $wrong,
            'total' => $total,
            'score' => $correct,
        ];
        
        if ($attemptSubTest->subTest) {
            $subName = $attemptSubTest->subTest->name;
            $subSlug = $attemptSubTest->subTest->slug;
            
            $nameVariations = self::generateAllVariations($subName);
            $slugVariations = self::generateAllVariations($subSlug);
            $allVariations = array_unique(array_merge($nameVariations, $slugVariations));
            
            foreach ($allVariations as $variation) {
                $variables[$variation] = $correct;
                $variables[$variation . '_score'] = $correct;
                $variables[$variation . '_correct'] = $correct;
            }
        }

        return $variables;
    }

    private static function evaluateMathExpression($expression)
    {
        $expression = trim($expression);
        
        if (empty($expression)) {
            return 0;
        }
        
        try {
            if (!preg_match('/^[0-9\+\-\*\/\(\)\.\s]+$/', $expression)) {
                throw new Exception('Invalid characters in expression');
            }
            $result = self::safeEval($expression);
            return $result;
            
        } catch (Exception $e) {
            try {
                return self::simpleMathEval($expression);
            } catch (Exception $e2) {
                return 0;
            }
        }
    }
    
    private static function safeEval($expression)
    {
        $expression = str_replace(' ', '', $expression);
        while (strpos($expression, '(') !== false) {
            $expression = preg_replace_callback(
                '/\(([^\(\)]+)\)/',
                function($matches) {
                    return self::calculateBasic($matches[1]);
                },
                $expression
            );
        }
        return self::calculateBasic($expression);
    }
    
    private static function simpleMathEval($expression)
    {
        $expression = str_replace(' ', '', $expression);
        while (preg_match('/(\d+(?:\.\d+)?)([\*\/])(\d+(?:\.\d+)?)/', $expression, $matches)) {
            $num1 = floatval($matches[1]);
            $num2 = floatval($matches[3]);
            $result = $matches[2] == '*' ? $num1 * $num2 : ($num2 != 0 ? $num1 / $num2 : 0);
            $expression = str_replace($matches[0], $result, $expression);
        }
        while (preg_match('/(\d+(?:\.\d+)?)([\+\-])(\d+(?:\.\d+)?)/', $expression, $matches)) {
            $num1 = floatval($matches[1]);
            $num2 = floatval($matches[3]);
            $result = $matches[2] == '+' ? $num1 + $num2 : $num1 - $num2;
            $expression = str_replace($matches[0], $result, $expression);
        }
        
        return floatval($expression);
    }

    private static function calculateBasic($expr)
    {
        if (empty($expr)) return 0;
        $expr = str_replace(' ', '', $expr);
        while (preg_match('/(\-?\d+(?:\.\d+)?)([\*\/])(\-?\d+(?:\.\d+)?)/', $expr, $matches)) {
            $num1 = floatval($matches[1]);
            $num2 = floatval($matches[3]);
            $operator = $matches[2];
            
            $result = $operator == '*' ? $num1 * $num2 : ($num2 != 0 ? $num1 / $num2 : 0);
            $expr = str_replace($matches[0], $result, $expr);
        }
        
        while (preg_match('/(\-?\d+(?:\.\d+)?)([\+\-])(\-?\d+(?:\.\d+)?)/', $expr, $matches)) {
            $num1 = floatval($matches[1]);
            $num2 = floatval($matches[3]);
            $operator = $matches[2];
            
            $result = $operator == '+' ? $num1 + $num2 : $num1 - $num2;
            $expr = str_replace($matches[0], $result, $expr);
        }
        
        return floatval($expr);
    }

    private static function calculateDefaultScore($variables)
    {
        if (isset($variables['total_correct']) && is_numeric($variables['total_correct'])) {
            return floatval($variables['total_correct']);
        }
        if (isset($variables['total_score']) && is_numeric($variables['total_score'])) {
            return floatval($variables['total_score']);
        }
        $formulaVariables = [
            'sinonim', 'antonim', 'analogi', 'pengelompokan_kata',
            'verbal_umum_pemahaman_wacana', 'logika_umum', 'silogisme',
            'logika_cerita', 'logika_diagram', 'aritmatika',
            'seri_angka', 'seri_huruf', 'logika_angka',
            'logika_dalam_cerita', 'seri_gambar',
            'hubungan_konsistensi_logis', 'pengelompokan_gambar',
            'spasial_bayangan_gambar', 'identifikasi_gambar'
        ];
        
        foreach ($formulaVariables as $var) {
            if (isset($variables[$var]) && is_numeric($variables[$var])) {
                return floatval($variables[$var]);
            }
        }
        foreach ($variables as $key => $value) {
            if (is_numeric($value) && $value > 0) {
                return floatval($value);
            }
        }
        
        return 0;
    }

    public static function calculateTypeTestScore(AttemptTypeTest $attemptTypeTest)
    {
        $testId = $attemptTypeTest->attempt->test_id;
        $variables = self::collectTypeTestVariables($attemptTypeTest);
        $score = self::calculateScore($testId, $variables);
        
        $attemptTypeTest->update([
            'score' => $score,
            'correct' => $variables['correct'] ?? 0,
            'total' => $variables['total'] ?? 0,
        ]);

        return $score;
    }

    public static function calculateSubTestScore(AttemptSubTest $attemptSubTest)
    {
        $testId = $attemptSubTest->attemptTypeTest->attempt->test_id;
        $variables = self::collectSubTestVariables($attemptSubTest);
        
        $score = self::calculateScore($testId, $variables);
        if ($score == 0 && isset($variables['correct'])) {
            $score = $variables['correct'];
        }
        
        $attemptSubTest->update([
            'score' => $score,
        ]);

        return $score;
    }

    public static function getVariableSuggestions($testId)
    {
        $test = \App\Models\Tests::with(['typeTests.subTests'])->find($testId);
        
        if (!$test) {
            return [];
        }
        
        $suggestions = [];
        
        foreach ($test->typeTests as $typeTest) {
            foreach ($typeTest->subTests as $subTest) {
                $variations = self::generateAllVariations($subTest->name);
                
                $suggestions[] = [
                    'name' => $subTest->name,
                    'slug' => $subTest->slug,
                    'variations' => $variations,
                    'example' => '(' . end($variations) . ' * 1)',
                    'description' => 'Sub test: ' . $subTest->name
                ];
            }
        }
        
        return [
            'subtest_variables' => $suggestions,
            'general_variables' => [
                'total_correct' => 'Total jawaban benar',
                'total_question' => 'Total pertanyaan',
                'total_score' => 'Total score',
                'correct' => 'Jumlah benar'
            ]
        ];
    }
}
