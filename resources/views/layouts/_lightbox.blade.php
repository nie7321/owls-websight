<div x-data="{ imgModal: false, imgModalSrc: '', imgModalDesc: '', imgModalAlt: '' }" @keyup.escape.window="imgModal = false;">
    <template @img-modal.window="imgModal = true; imgModalSrc = $event.detail.imgModalSrc; imgModalDesc = $event.detail.imgModalDesc; imgModalAlt = $event.detail.imgModalAlt;" x-if="imgModal">
        <div
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90"
            class="p-2 fixed w-full h-100 inset-0 z-50 overflow-hidden flex justify-center items-center bg-black bg-opacity-75"
        >
            <div class="flex flex-col max-w-3xl max-h-full overflow-auto" x-on:click.outside="imgModal = false">
                <div class="z-50">
                    <button @click="imgModal = false" class="float-right pt-2 pr-2 outline-none focus:outline-none" aria-label="Close Image">
                        <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                        </svg>
                    </button>
                </div>
                <figure class="p-2 flex flex-col gap-y-4 justify-center">
                    <img :alt="imgModalAlt" class="object-contain max-h-[90vh]" :src="imgModalSrc">
                    <figcaption x-html="imgModalDesc" class="text-white"></figcaption>
                </figure>
            </div>
        </div>
    </template>
</div>
