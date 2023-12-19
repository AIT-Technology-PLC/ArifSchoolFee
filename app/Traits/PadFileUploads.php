<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait PadFileUploads
{
    private function validatedUploads($master, $details)
    {
        $data['master'] = $master;
        $data['details'] = $details;

        foreach ($data['master'] as $key => $value) {
            if (!$this->masterPadFieldsTypeFile->where('id', $key)->count()) {
                unset($data['master'][$key]);
                continue;
            }
            if ($this->masterPadFieldsTypeFile->where('id', $key)->count() && is_string($value) && Storage::exists('public\\' . $value)) {
                continue;
            }
            if ($this->masterPadFieldsTypeFile->where('id', $key)->count() && is_object($value)) {
                $this->validateOnly('master.' . $key, [
                    'master.' . $key => 'sometimes|file|mimes:jpg,jpeg,png,pdf|max:4000',
                ]);
                continue;
            }

            unset($data['master'][$key]);
        }

        foreach ($data['details'] as $i => &$detail) {
            foreach ($detail as $key => $value) {
                if (!$this->detailPadFieldsTypeFile->where('id', $key)->count()) {
                    unset($detail[$key]);
                    continue;
                }
                if ($this->detailPadFieldsTypeFile->where('id', $key)->count() && is_string($value) && Storage::exists('public\\' . $value)) {
                    continue;
                }
                if ($this->detailPadFieldsTypeFile->where('id', $key)->count() && is_object($value)) {
                    $this->validateOnly('details.' . $i . '.' . $key, [
                        'details.*.' . $key => 'sometimes|file|mimes:jpg,jpeg,png,pdf|max:4000',
                    ]);
                    continue;
                }

                unset($detail[$key]);
            }
        }

        return $data;
    }
}
