<script setup>
import { ref } from 'vue';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    user: Object
});

const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    email: props.user.email,
    photo: null
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput()
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        }
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};

const isCopied = ref(false)
const page = usePage()

const calendarLink = `${window.location.origin}/calendar/generate_calendar/${page.props.auth.user.id}`;
const copyToClipboard = () => {
    const calendarLink = `${window.location.origin}/calendar/generate_calendar/${page.props.auth.user.id}`;
    navigator.clipboard.writeText(calendarLink).then(() => {
        isCopied.value = true;
        setTimeout(() => {
            isCopied.value = false;
        }, 2000); // Hide the tick icon after 2 seconds
    });
};
</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title>
            {{ $t('profileForm.title') }}
        </template>

        <template #description>
            {{ $t('profileForm.description') }}
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input
                    id="photo"
                    ref="photoInput"
                    class="hidden"
                    type="file"
                    @change="updatePhotoPreview"
                >

                <InputLabel :value="$t('profileForm.avatar')" for="photo"/>

                <!-- Current Profile Photo -->
                <div v-show="!photoPreview" class="mt-2">
                    <img :alt="user.name" :src="user.profile_photo_url" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview" class="mt-2">
                    <span
                        :style="'background-image: url(\'' + photoPreview + '\');'"
                        class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                    />
                </div>

                <SecondaryButton class="mt-2 me-2" type="button" @click.prevent="selectNewPhoto">
                    {{ $t('profileForm.selectNewAvatar') }}
                </SecondaryButton>

                <SecondaryButton
                    v-if="user.profile_photo_path"
                    class="mt-2"
                    type="button"
                    @click.prevent="deletePhoto"
                >
                    {{ $t('profileForm.removeAvatar') }}
                </SecondaryButton>

                <InputError :message="form.errors.photo" class="mt-2"/>
            </div>

            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel :value="$t('profileForm.name')" for="name"/>
                <TextInput
                    id="name"
                    v-model="form.name"
                    autocomplete="name"
                    class="mt-1 block w-full"
                    required
                    type="text"
                />
                <InputError :message="form.errors.name" class="mt-2"/>
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel :value="$t('profileForm.email')" for="email"/>
                <TextInput
                    id="email"
                    v-model="form.email"
                    autocomplete="username"
                    class="mt-1 block w-full"
                    required
                    type="email"
                />
                <InputError :message="form.errors.email" class="mt-2"/>

                <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                    <p class="text-sm mt-2 dark:text-white">
                        {{ $t('profileForm.emailNotVerified') }}

                        <Link
                            :href="route('verification.send')"
                            as="button"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                            method="post"
                            @click.prevent="sendEmailVerification"
                        >
                            {{ $t('profileForm.resendVerification') }}
                        </Link>
                    </p>

                    <div v-show="verificationLinkSent"
                         class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ $t('profileForm.verificationSent') }}
                    </div>
                </div>

            </div>

            <div class="col-span-6 sm:col-span-4">
                <InputLabel :value="$t('common.calendar')" for="calendar_link"/>
                <div class="flex items-center">
                    <input id="calendar_link" :value="calendarLink" class="cursor-pointer border rounded p-2 w-full" readonly type="text" @click="copyToClipboard" />
                    <span v-if="isCopied" class="ml-2 text-green-500">✔️</span>
                </div>

            </div>


        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                {{ $t('profileForm.saved') }}
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ $t('profileForm.save') }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
