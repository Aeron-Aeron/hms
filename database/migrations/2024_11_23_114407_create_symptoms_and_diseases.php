<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('disease_symptom');
        Schema::dropIfExists('diseases');
        Schema::dropIfExists('symptoms');

        // Create symptoms table
        Schema::create('symptoms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Create diseases table
        Schema::create('diseases', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Create pivot table
        Schema::create('disease_symptom', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disease_id')->constrained()->onDelete('cascade');
            $table->foreignId('symptom_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Read the CSV and populate the database
        $csvFile = storage_path('app/symtoms_df.csv');
        if (file_exists($csvFile)) {
            $file = fopen($csvFile, 'r');
            fgetcsv($file); // Skip header row

            $symptoms = collect();
            $diseases = collect();

            while (($data = fgetcsv($file)) !== false) {
                $diseaseName = trim($data[1]);
                $diseaseSymptoms = array_filter(array_map('trim', array_slice($data, 2)));

                // Create disease if it doesn't exist
                if (!$diseases->has($diseaseName)) {
                    $disease = DB::table('diseases')->insertGetId([
                        'name' => $diseaseName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $diseases->put($diseaseName, $disease);
                }

                // Create symptoms and relationships
                foreach ($diseaseSymptoms as $symptomName) {
                    if (!empty($symptomName)) {
                        if (!$symptoms->has($symptomName)) {
                            $symptom = DB::table('symptoms')->insertGetId([
                                'name' => $symptomName,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            $symptoms->put($symptomName, $symptom);
                        }

                        // Create relationship
                        DB::table('disease_symptom')->insertOrIgnore([
                            'disease_id' => $diseases->get($diseaseName),
                            'symptom_id' => $symptoms->get($symptomName),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            fclose($file);
        }
    }

    public function down()
    {
        Schema::dropIfExists('disease_symptom');
        Schema::dropIfExists('diseases');
        Schema::dropIfExists('symptoms');
    }
};
