<?php

namespace MtrDesign\Krait;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface CustomPreview
{
    public function previewConfigurations(): HasMany;
}
