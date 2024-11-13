<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class DoctorRecommendationService
{
    public function getRecommendedDoctors(string $symptoms): Collection
    {
        // This is a simple implementation. You can make it more sophisticated
        // by using keywords matching, ML algorithms, etc.
        $keywords = $this->extractKeywords($symptoms);

        return User::where('role', 'doctor')
            ->whereHas('doctorProfile', function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->where('specialization', 'like', "%{$keyword}%")
                        ->orWhere('bio', 'like', "%{$keyword}%");
                }
            })
            ->with('doctorProfile')
            ->get();
    }

    private function extractKeywords(string $symptoms): array
    {
        // Simple keyword extraction - can be improved
        $commonWords = ['the', 'and', 'or', 'in', 'on', 'at', 'to'];
        $words = str_word_count(strtolower($symptoms), 1);
        return array_diff($words, $commonWords);
    }
}
