<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Disease;
use App\Models\Symptom;

class DiseaseSymptomSeeder extends Seeder
{
    public function run()
    {
        $csvFile = storage_path('app/symtoms_df.csv');
        $file = fopen($csvFile, 'r');

        // Skip header row
        fgetcsv($file);

        $symptoms = collect();
        $diseases = collect();

        while (($data = fgetcsv($file)) !== false) {
            $diseaseName = trim($data[1]);
            $diseaseSymptoms = array_filter(array_map('trim', array_slice($data, 2, 4)));

            // Create or get disease
            if (!$diseases->has($diseaseName)) {
                $disease = Disease::firstOrCreate(['name' => $diseaseName]);
                $diseases->put($diseaseName, $disease);
            }

            // Create or get symptoms
            foreach ($diseaseSymptoms as $symptomName) {
                if (!empty($symptomName)) {
                    if (!$symptoms->has($symptomName)) {
                        $symptom = Symptom::firstOrCreate(['name' => $symptomName]);
                        $symptoms->put($symptomName, $symptom);
                    }

                    // Associate symptom with disease
                    $diseases->get($diseaseName)->symptoms()->syncWithoutDetaching($symptoms->get($symptomName)->id);
                }
            }
        }

        fclose($file);
    }
}
