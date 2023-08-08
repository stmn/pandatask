<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\Setting;
use Illuminate\Validation\ValidationException;

trait SettingsImagesTrait
{
    /**
     * @throws ValidationException
     */
    public function uploadLogo(): void
    {
        $this->validate(request(), [
            'file' => 'required|image|mimes:png|max:512',
        ]);

        $this->uploadImage('brand_logo');
    }

    public function deleteLogo(): void
    {
        $this->deleteImage('brand_logo');
    }

    /**
     * @throws ValidationException
     */
    public function uploadFavicon(): void
    {
        $this->validate(request(), [
            'file' => 'required|image|mimes:png|max:128',
        ]);

        $this->uploadImage('brand_favicon');
    }

    public function deleteFavicon(): void
    {
        $this->deleteImage('brand_favicon');
    }

    private function uploadImage($name): void
    {
        $file = request()->file('file');
        $path = $file->storePublicly('public');
        Setting::query()->where('name', $name)->update(['value' => $path]);
    }

    private function deleteImage($name): void
    {
        Setting::query()->where('name', $name)->update(['value' => null]);
    }
}
