<?php

declare(strict_types=1);

use Livewire\Component;
use Spatie\Image\Image;
use Livewire\WithFileUploads;
use Livewire\Attributes\Defer;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

new #[Defer] class extends Component
{
    use WithFileUploads;

    public TemporaryUploadedFile|string|null $avatar = null;

    public string $s3_path = 'avatars';

    public bool $show_crop_avatar_modal = false;

    protected function messages(): array
    {
        return [
            'avatar.image' => 'Avatar must be a valid image',
            'avatar.max' => 'Avatar must be less than 2MB',
            'avatar.mimes' => 'Avatar must be of type: jpg, jpeg, png',
        ];
    }

    protected function rules(): array
    {
        return [
            'avatar' => ['nullable', 'image', 'max:190000', 'mimes:jpg,jpeg,png']
        ];
    }

    public function mount(): void
    {
        $this->avatar = auth()->user()->avatar;
    }

    #[Computed]
    public function avatarUrl(): string
    {
        if ($this->avatar instanceof TemporaryUploadedFile) {
            return $this->avatar->temporaryUrl();
        }

        if (is_string($this->avatar) && Storage::disk('s3')->exists("{$this->s3_path}/{$this->avatar}")) {
            return Storage::disk('s3')->url("{$this->s3_path}/{$this->avatar}");
        }

        return '';
    }

    public function updatedAvatar(): void
    {
        $this->validate();

        $this->show_crop_avatar_modal = true;
    }

    public function save(array $crop_region): void
    {
        $crop_region = array_map('intval', $crop_region);

        Image::load($this->avatar->getRealPath())
            ->manualCrop(
                $crop_region['width'],
                $crop_region['height'],
                $crop_region['x'],
                $crop_region['y'],
            )
            ->save();

        /** @var TemporaryUploadedFile $avatar */
        $avatar = $this->avatar;

        $filename = $avatar->getClientOriginalName();

        $avatar->storePubliclyAs($this->s3_path, $filename, 's3');

        auth()
            ->user()
            ->update(['avatar' => $filename]);

        $this->redirectRoute('settings.profile', navigate: true);
    }

    public function clearAvatar(): void
    {
        $this->reset('avatar');

        $this->redirectRoute('settings.profile', navigate: true);
    }

    public function removeAvatar(): void
    {
        $user = auth()->user();

        if ($user->avatar && Storage::disk('s3')->exists("{$this->s3_path}/{$user->avatar}")) {
            Storage::disk('s3')->delete("{$this->s3_path}/{$user->avatar}");
        }

        $user->update(['avatar' => null]);

        $this->redirectRoute('settings.profile', navigate: true);
    }
};
?>

@placeholder
    <div class="flex flex-col">
        <h4 class="text-sm font-medium select-none text-gray-800 dark:text-white">
            Avatar
        </h4>

        <flux:skeleton animate="shimmer" class="size-24 mt-2 rounded-xl!" />
    </div>
@endplaceholder

<div class="flex w-fit flex-col">
    <h4 class="text-sm font-medium select-none text-gray-800 dark:text-white">
        Avatar
    </h4>

    <div class="flex items-center space-x-3">
        <label for="avatar" class="relative @if ($avatar) cursor-normal! @else cursor-pointer @endif">
            @if (!$avatar)
                <flux:input type="file" wire:model="avatar" class="sr-only!" id="avatar" />
            @endif

            @if ($avatar)
                <img src="{{ $this->avatarUrl() }}" alt="Avatar" class="rounded-xl size-24 mt-2" id="avatar" />
            @else
                <div
                    class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 duration-100 ease-in-out rounded-xl border dark:border-white/10 dark:bg-gray-800/70 dark:hover:bg-gray-700/70 size-24 mt-2">
                    <svg wire:loading.remove wire:target="avatar" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                    </svg>

                    <flux:icon.loading wire:loading wire:target="avatar" />
                </div>
            @endif
        </label>

        @if ($avatar)
            <flux:button variant="outline" type="button" wire:click="removeAvatar" size="sm" class="mt-1.5">
                Remove
            </flux:button>
        @endif
    </div>

    <template x-cloak x-if="$wire.avatar">
        <flux:modal wire:model.self='show_crop_avatar_modal' :dismissible="false" :closable="false">
            <form wire:submit.prevent="$js.saveCroppedImage" class="space-y-6">
                <flux:heading size="lg" class="font-semibold -mt-1.5!">
                    Crop Avatar
                </flux:heading>

                <div>
                    <img id="crop-avatar" src="{{ $this->avatarUrl() }}" alt="Avatar" class="w-full max-w-full" />
                </div>             

                <div class="flex gap-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button  variant="ghost" size="sm">
                            Cancel
                        </flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" wire:loading.attr='disabled' wire:target='save' variant="primary" size="sm">
                        Save
                    </flux:button>
                </div>
            </form>
        </flux:modal>
    </template>

    <flux:error name="avatar" />
</div>

<script>
    let cropper = null;
    let cropRegion = null;

    this.$js.saveCroppedImage = () => {
        $wire.save(cropRegion);
    };

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && $wire.show_crop_avatar_modal) {
            e.stopPropagation();
            e.preventDefault();
        }
    });

    $wire.$watch('show_crop_avatar_modal', () => {                     
        cropper = new Cropper(document.getElementById('crop-avatar'), {
            autoCropArea: 1,
            viewMode: 1,
            aspectRatio: 1/1,

            crop (e) {
                cropRegion = {
                    x: e.detail.x,
                    y: e.detail.y,
                    width: e.detail.width,
                    height: e.detail.height,
                };
            }
        });
    });
</script>
