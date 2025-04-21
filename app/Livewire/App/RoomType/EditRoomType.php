<?php

namespace App\Livewire\App\RoomType;

use App\Models\RoomType;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditRoomType extends Component
{
    use DispatchesToast, WithFilePond;

    protected $listeners = [
        'room-type-edited' => '$refresh',
        'inclusion-created' => '$refresh',
        'inclusion-deleted' => '$refresh',
    ];

    #[Validate] public $name;
    #[Validate] public $min_rate;
    #[Validate] public $max_rate;
    #[Validate] public $image_1_path;
    #[Validate] public $image_2_path;
    #[Validate] public $image_3_path;
    #[Validate] public $image_4_path;
    #[Validate] public $description;
    public $temp_image_1_path;
    public $temp_image_2_path;
    public $temp_image_3_path;
    public $temp_image_4_path;
    public $room_type;
    
    public function rules() {
        return RoomType::rules();
    }

    public function messages() {
        return RoomType::messages();
    }

    public function mount(RoomType $room_type) {
        $this->room_type = $room_type;
        $this->name = $room_type->name;
        $this->description = $room_type->description;
        $this->min_rate = $room_type->min_rate;
        $this->max_rate = $room_type->max_rate;
    }

    public function submit() {
        $validated = $this->validate();

        if (!empty($this->image_1_path)) {
            if (!empty($this->room_type->image_1_path)) {
                // Delete the current image then store the new one
                Storage::disk('public')->delete($this->room_type->image_1_path);
            }

            $this->room_type->image_1_path = $this->image_1_path->store('room-types', 'public');
        }
        if (!empty($this->image_2_path)) {
            if (!empty($this->room_type->image_2_path)) {
                // Delete the current image then store the new one
                Storage::disk('public')->delete($this->room_type->image_2_path);
            }
            
            $this->room_type->image_2_path = $this->image_2_path->store('room-types', 'public');
        }
        if (!empty($this->image_3_path)) {
            if (!empty($this->room_type->image_3_path)) {
                // Delete the current image then store the new one
                Storage::disk('public')->delete($this->room_type->image_3_path);
            }

            $this->room_type->image_3_path = $this->image_3_path->store('room-types', 'public');
        }
        if (!empty($this->image_4_path)) {
            if (!empty($this->room_type->image_4_path)) {
                // Delete the current image then store the new one
                Storage::disk('public')->delete($this->room_type->image_4_path);
            }

            $this->room_type->image_4_path = $this->image_4_path->store('room-types', 'public');
        }

        $this->room_type->name = $validated['name'];
        $this->room_type->description = $validated['description'];
        $this->room_type->min_rate = $validated['min_rate'];
        $this->room_type->max_rate = $validated['max_rate'];
        $this->room_type->save();

        $this->toast('Room Edited Successfully', 'success', 'Updated room type successfully!');
        $this->dispatch('pond-reset');
        $this->dispatch('room-type-edited');
        $this->reset([
            'image_1_path',
            'image_2_path',
            'image_3_path',
            'image_4_path',
        ]);
    }

    public function render()
    {
        $this->temp_image_1_path = $this->room_type->image_1_path;        
        $this->temp_image_2_path = $this->room_type->image_2_path;        
        $this->temp_image_3_path = $this->room_type->image_3_path;        
        $this->temp_image_4_path = $this->room_type->image_4_path;        

        return view('livewire.app.room-type.edit-room-type');
    }
}
