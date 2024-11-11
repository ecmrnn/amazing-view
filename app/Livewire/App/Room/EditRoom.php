<?php

namespace App\Livewire\App\Room;

use App\Models\Room;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditRoom extends Component
{
    use DispatchesToast, WithFilePond;

    #[Validate] public $room;
    #[Validate] public $min_capacity;
    #[Validate] public $max_capacity;
    #[Validate] public $image_1_path;
    #[Validate] public $rate;

    public function rules() {
        return [
            'min_capacity' => 'required|numeric|min:1',
            'max_capacity' => 'required|numeric|gte:min_capacity',
            'image_1_path' => 'nullable|image|mimes:jpeg,jpg,png',
            'rate' => 'required|numeric|min:1000',
        ];
    }

    public function mount(Room $room) {
        $this->room = $room;
        $this->min_capacity = $room->min_capacity;
        $this->max_capacity = $room->max_capacity;
        $this->rate = $room->rate;
    }

    public function submit() {
        $this->validate([
            'min_capacity' => $this->rules()['min_capacity'],
            'max_capacity' => $this->rules()['max_capacity'],
            'image_1_path' => $this->rules()['image_1_path'],
            'rate' => $this->rules()['rate'],
        ]);

        $this->room->min_capacity = $this->min_capacity;
        $this->room->max_capacity = $this->max_capacity;
        $this->room->rate = $this->rate;
        $this->room->save();

        $this->toast('Success!', 'success', 'Room edited successfully!');
        $this->dispatch('room-edited');
    }

    public function render()
    {
        return view('livewire.app.room.edit-room');
    }
}
