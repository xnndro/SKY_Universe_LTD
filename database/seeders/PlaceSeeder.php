<?php

namespace Database\Seeders;

use App\Models\Places;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::connection()->getPdo();
            echo "Connected successfully to the database!\n";
        } catch (\Exception $e) {
            die("Could not connect to the database. Error: " . $e->getMessage());
        }

        $faker = Faker::create('id_ID');
        $location = ['Singapore', 'Tangerang', 'Jakarta'];

        for ($i = 1; $i <= 15; $i++) {
            // each location 5 items
            $index = $i % 3;
            if ($index == 0) {
                $index = 3;
            }

            $imageUrl = 'https://source.unsplash.com/450x250/?' . urlencode($location[$index - 1]);

            // Download the image and store it in the storage folder
            $imagePath = $this->downloadImage($imageUrl);

            if ($imagePath) {
                $data = [
                    'name' => $faker->streetName,
                    'address' => $faker->address,
                    'location' => $location[$index - 1],
                    'price' => $faker->numberBetween(100000, 1000000),
                    'picture' => $imagePath, // Save the path to the image in the database
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                try {
                    $place = new Places($data);
                    $place->save();
                    echo "Data inserted successfully!\n";
                } catch (\Exception $e) {
                    // Log the exception to identify the error
                    echo "Data failed to insert. Error: " . $e->getMessage() . "\n";
                }
            } else {
                echo "Failed to download image for location: " . $location[$index - 1] . "\n";
            }
        }
    }

    /**
     * Download the image from the given URL and save it in the storage folder.
     *
     * @param string $imageUrl
     * @return string|false The file path of the downloaded image, or false if download fails.
     */
    private function downloadImage(string $imageUrl)
    {
        $imageData = file_get_contents($imageUrl);
        if ($imageData) {
            // Generate a random filename for the image
            $filename = 'unsplash_' . uniqid() . '.jpg';

            // Save the image in the storage folder
            $imagePath = 'public/uploads/places/' . $filename;
            Storage::put($imagePath, $imageData);

            return $filename; // Return the image path relative to the storage folder
        }

        return false;
    }
}
