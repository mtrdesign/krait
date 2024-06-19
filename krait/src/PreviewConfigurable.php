<?php

namespace MtrDesign\Krait;

use Illuminate\Database\Eloquent\Relations\HasMany;
use MtrDesign\Krait\Models\KraitPreviewConfiguration;

trait PreviewConfigurable
{
    public function previewConfigurations(): HasMany
    {
        return $this->hasMany(KraitPreviewConfiguration::class);
    }
}
