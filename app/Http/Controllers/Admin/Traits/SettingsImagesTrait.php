<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

trait SettingsImagesTrait
{
    /**
     * @throws ValidationException
     */
    public function uploadLogo(): void
    {
        $this->validate(request(), [
            'file' => 'required|image|mimes:png|max:256',
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
            'file' => 'required|image|mimes:png|max:64',
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

        $this->deleteImage($name);

        $path = $file->storePublicly('public/general');
        $url = Storage::url($path);
        Setting::query()->where('name', $name)->update(['value' => $url]);
    }

    private function deleteImage($name): void
    {
        Storage::disk('public')->delete(
            str_replace('/storage', '', Setting::query()->where('name', $name)->value('value'))
        );

        Setting::query()->where('name', $name)->update(['value' => null]);
    }
}
