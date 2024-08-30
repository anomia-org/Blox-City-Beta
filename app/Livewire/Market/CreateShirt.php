<?php

namespace App\Livewire\Market;

use App\Models\Inventory;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateShirt extends Component
{
    use WithFileUploads;

    public $image;
    public $name;
    public $desc;

    protected $rules = [
        'image' => 'required|image|mimes:png|max:2048', // 2MB Max
        'name' => 'required|string|min:3|max:64',
        'desc' => 'nullable|string|min:3|max:2048',
    ];

    public function submit()
    {
        $this->validate();

        $lockKey = auth()->user()->id.':market:create';
        $lockAcquired = Redis::set($lockKey, 'locked', 'NX', 'EX', 5);
        if (!$lockAcquired) {
            return $this->dispatch('toast:error', 'You are uploading too fast. Please wait a few seconds before uploading again.');
        }

        DB::beginTransaction();
        try {

            $realName = bin2hex(random_bytes(32));
            $imageName = $realName.'.'.$this->image->extension();

            $disk = Storage::build([
                'driver' => 'local',
                'root' => '/var/www/cdn',
            ]);

            $disk->putFileAs('', $this->image, $imageName);

            $item = Item::create([
                'name' => $this->name,
                'desc' => $this->desc,
                'creator_id' => auth()->user()->id,
                'updated_real' => Carbon::now(),
                'type' => 4,
                'source' => $realName,
                'hash' => 1,
            ]);

            Inventory::create([
                'user_id' => auth()->user()->id,
                'item_id' => $item->id,
                'type' => 4,
                'collection_number' => $this->generateSerial(),
            ]);

            app('App\Http\Controllers\Render\AvatarsController')->market($item);
            
            DB::commit();
            return redirect()->route('market.item', $item)->with('success', 'Shirt uploaded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Redis::del($lockKey);
            return $this->dispatch('toast:error', 'An error occurred while uploading your shirt. Please try again.');
        }
    }

    private function generateSerial(): string
    {
        $randomHash = bin2hex(random_bytes(5));
        return $randomHash;
    }

    public function render()
    {
        return view('livewire.market.create-shirt');
    }
}
