<?php

namespace App\Traits;

trait DispatchesToast
{
    public function toast($message = null, $type = 'success', $description = null)
    {
        $toastData = json_encode([
            'message' => $message,
            'type' => $type,
            'description' => $description
        ]);

        $this->dispatch('toast', $toastData);
    }
}
