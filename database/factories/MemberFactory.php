<?php

namespace Database\Factories;

use App\Enums\MemberGender;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\File as FileFacade;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(MemberGender::cases())->value;

        return [
            'name' => fake()->name($gender),
            'gender' => $gender,
            'birth_date' => fake()->date(),
            'photo' => rand(1, 10) <= 8 ? $this->photoPath($gender) : null,
        ];
    }

    /**
     * Get a random photo path for a given gender.
     */
    public function photoPath(string $gender): string|null
    {
        $sourceDir = storage_path("app/public/member-photos/default/{$gender}");

        if (! FileFacade::exists($sourceDir))
            return null;

        $files = FileFacade::files($sourceDir);

        if (empty($files))
            return null;

        $randomFile = fake()->randomElement($files);
        // Copy the file to 'storage/app/public/member-photos' with a unique hash name
        return Storage::disk('public')
                ->putFile('member-photos', new File($randomFile->getPathname()));
    }
}
