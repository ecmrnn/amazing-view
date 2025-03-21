<?php

namespace App\Livewire\App\RoomType;

use App\Models\RoomType;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class CreateRoomType extends Component
{
    use DispatchesToast, WithFilePond;

    #[Validate] public $name;
    #[Validate] public $min_rate;
    #[Validate] public $max_rate;
    #[Validate] public $image_1_path;
    #[Validate] public $image_2_path;
    #[Validate] public $image_3_path;
    #[Validate] public $image_4_path;
    #[Validate] public $description = 'Add a brief description of your new room';
    public $image_count = 0;

    public function rules() {
        return RoomType::rules();
    }

    public function messages() {
        return RoomType::messages();
    }

    public function submit() {
        $validated = $this->validate();

        // Store images
        !$validated['image_1_path'] ?: $validated['image_1_path'] = $this->image_1_path->store('room-types', 'public');
        !$validated['image_2_path'] ?: $validated['image_2_path'] = $this->image_2_path->store('room-types', 'public');
        !$validated['image_3_path'] ?: $validated['image_3_path'] = $this->image_3_path->store('room-types', 'public');
        !$validated['image_4_path'] ?: $validated['image_4_path'] = $this->image_4_path->store('room-types', 'public');

        // Store to database
        RoomType::create($validated);
        
        $this->toast('Room Type Created!', 'success', 'Successfully created a room type');
        $this->reset();
        $this->dispatch('pond-reset');
    }

    public function render()
    {
        return view('livewire.app.room-type.create-room-type');
    }
}
